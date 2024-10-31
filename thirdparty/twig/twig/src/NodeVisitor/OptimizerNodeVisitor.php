<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\NodeVisitor;

use RS_Vendor\Twig\Environment;
use RS_Vendor\Twig\Node\BlockReferenceNode;
use RS_Vendor\Twig\Node\Expression\BlockReferenceExpression;
use RS_Vendor\Twig\Node\Expression\ConstantExpression;
use RS_Vendor\Twig\Node\Expression\FilterExpression;
use RS_Vendor\Twig\Node\Expression\FunctionExpression;
use RS_Vendor\Twig\Node\Expression\GetAttrExpression;
use RS_Vendor\Twig\Node\Expression\NameExpression;
use RS_Vendor\Twig\Node\Expression\ParentExpression;
use RS_Vendor\Twig\Node\ForNode;
use RS_Vendor\Twig\Node\IncludeNode;
use RS_Vendor\Twig\Node\Node;
use RS_Vendor\Twig\Node\PrintNode;
/**
 * Tries to optimize the AST.
 *
 * This visitor is always the last registered one.
 *
 * You can configure which optimizations you want to activate via the
 * optimizer mode.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class OptimizerNodeVisitor extends \RS_Vendor\Twig\NodeVisitor\AbstractNodeVisitor
{
    const OPTIMIZE_ALL = -1;
    const OPTIMIZE_NONE = 0;
    const OPTIMIZE_FOR = 2;
    const OPTIMIZE_RAW_FILTER = 4;
    // obsolete, does not do anything
    const OPTIMIZE_VAR_ACCESS = 8;
    private $loops = [];
    private $loopsTargets = [];
    private $optimizers;
    /**
     * @param int $optimizers The optimizer mode
     */
    public function __construct(int $optimizers = -1)
    {
        if (!\is_int($optimizers) || $optimizers > (self::OPTIMIZE_FOR | self::OPTIMIZE_RAW_FILTER | self::OPTIMIZE_VAR_ACCESS)) {
            throw new \InvalidArgumentException(\sprintf('Optimizer mode "%s" is not valid.', $optimizers));
        }
        $this->optimizers = $optimizers;
    }
    protected function doEnterNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        if (self::OPTIMIZE_FOR === (self::OPTIMIZE_FOR & $this->optimizers)) {
            $this->enterOptimizeFor($node, $env);
        }
        return $node;
    }
    protected function doLeaveNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        if (self::OPTIMIZE_FOR === (self::OPTIMIZE_FOR & $this->optimizers)) {
            $this->leaveOptimizeFor($node, $env);
        }
        if (self::OPTIMIZE_RAW_FILTER === (self::OPTIMIZE_RAW_FILTER & $this->optimizers)) {
            $node = $this->optimizeRawFilter($node, $env);
        }
        $node = $this->optimizePrintNode($node, $env);
        return $node;
    }
    /**
     * Optimizes print nodes.
     *
     * It replaces:
     *
     *   * "echo $this->render(Parent)Block()" with "$this->display(Parent)Block()"
     */
    private function optimizePrintNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env) : \RS_Vendor\Twig\Node\Node
    {
        if (!$node instanceof \RS_Vendor\Twig\Node\PrintNode) {
            return $node;
        }
        $exprNode = $node->getNode('expr');
        if ($exprNode instanceof \RS_Vendor\Twig\Node\Expression\BlockReferenceExpression || $exprNode instanceof \RS_Vendor\Twig\Node\Expression\ParentExpression) {
            $exprNode->setAttribute('output', \true);
            return $exprNode;
        }
        return $node;
    }
    /**
     * Removes "raw" filters.
     */
    private function optimizeRawFilter(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env) : \RS_Vendor\Twig\Node\Node
    {
        if ($node instanceof \RS_Vendor\Twig\Node\Expression\FilterExpression && 'raw' == $node->getNode('filter')->getAttribute('value')) {
            return $node->getNode('node');
        }
        return $node;
    }
    /**
     * Optimizes "for" tag by removing the "loop" variable creation whenever possible.
     */
    private function enterOptimizeFor(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        if ($node instanceof \RS_Vendor\Twig\Node\ForNode) {
            // disable the loop variable by default
            $node->setAttribute('with_loop', \false);
            \array_unshift($this->loops, $node);
            \array_unshift($this->loopsTargets, $node->getNode('value_target')->getAttribute('name'));
            \array_unshift($this->loopsTargets, $node->getNode('key_target')->getAttribute('name'));
        } elseif (!$this->loops) {
            // we are outside a loop
            return;
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\NameExpression && 'loop' === $node->getAttribute('name')) {
            $node->setAttribute('always_defined', \true);
            $this->addLoopToCurrent();
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\NameExpression && \in_array($node->getAttribute('name'), $this->loopsTargets)) {
            $node->setAttribute('always_defined', \true);
        } elseif ($node instanceof \RS_Vendor\Twig\Node\BlockReferenceNode || $node instanceof \RS_Vendor\Twig\Node\Expression\BlockReferenceExpression) {
            $this->addLoopToCurrent();
        } elseif ($node instanceof \RS_Vendor\Twig\Node\IncludeNode && !$node->getAttribute('only')) {
            $this->addLoopToAll();
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\FunctionExpression && 'include' === $node->getAttribute('name') && (!$node->getNode('arguments')->hasNode('with_context') || \false !== $node->getNode('arguments')->getNode('with_context')->getAttribute('value'))) {
            $this->addLoopToAll();
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\GetAttrExpression && (!$node->getNode('attribute') instanceof \RS_Vendor\Twig\Node\Expression\ConstantExpression || 'parent' === $node->getNode('attribute')->getAttribute('value')) && (\true === $this->loops[0]->getAttribute('with_loop') || $node->getNode('node') instanceof \RS_Vendor\Twig\Node\Expression\NameExpression && 'loop' === $node->getNode('node')->getAttribute('name'))) {
            $this->addLoopToAll();
        }
    }
    /**
     * Optimizes "for" tag by removing the "loop" variable creation whenever possible.
     */
    private function leaveOptimizeFor(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        if ($node instanceof \RS_Vendor\Twig\Node\ForNode) {
            \array_shift($this->loops);
            \array_shift($this->loopsTargets);
            \array_shift($this->loopsTargets);
        }
    }
    private function addLoopToCurrent()
    {
        $this->loops[0]->setAttribute('with_loop', \true);
    }
    private function addLoopToAll()
    {
        foreach ($this->loops as $loop) {
            $loop->setAttribute('with_loop', \true);
        }
    }
    public function getPriority()
    {
        return 255;
    }
}
\class_alias('RS_Vendor\\Twig\\NodeVisitor\\OptimizerNodeVisitor', 'RS_Vendor\\Twig_NodeVisitor_Optimizer');
