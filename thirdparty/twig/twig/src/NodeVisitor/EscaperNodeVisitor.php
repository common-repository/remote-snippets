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
use RS_Vendor\Twig\Extension\EscaperExtension;
use RS_Vendor\Twig\Node\AutoEscapeNode;
use RS_Vendor\Twig\Node\BlockNode;
use RS_Vendor\Twig\Node\BlockReferenceNode;
use RS_Vendor\Twig\Node\DoNode;
use RS_Vendor\Twig\Node\Expression\ConditionalExpression;
use RS_Vendor\Twig\Node\Expression\ConstantExpression;
use RS_Vendor\Twig\Node\Expression\FilterExpression;
use RS_Vendor\Twig\Node\Expression\InlinePrint;
use RS_Vendor\Twig\Node\ImportNode;
use RS_Vendor\Twig\Node\ModuleNode;
use RS_Vendor\Twig\Node\Node;
use RS_Vendor\Twig\Node\PrintNode;
use RS_Vendor\Twig\NodeTraverser;
/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class EscaperNodeVisitor extends \RS_Vendor\Twig\NodeVisitor\AbstractNodeVisitor
{
    private $statusStack = [];
    private $blocks = [];
    private $safeAnalysis;
    private $traverser;
    private $defaultStrategy = \false;
    private $safeVars = [];
    public function __construct()
    {
        $this->safeAnalysis = new \RS_Vendor\Twig\NodeVisitor\SafeAnalysisNodeVisitor();
    }
    protected function doEnterNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        if ($node instanceof \RS_Vendor\Twig\Node\ModuleNode) {
            if ($env->hasExtension(\RS_Vendor\Twig\Extension\EscaperExtension::class) && ($defaultStrategy = $env->getExtension(\RS_Vendor\Twig\Extension\EscaperExtension::class)->getDefaultStrategy($node->getTemplateName()))) {
                $this->defaultStrategy = $defaultStrategy;
            }
            $this->safeVars = [];
            $this->blocks = [];
        } elseif ($node instanceof \RS_Vendor\Twig\Node\AutoEscapeNode) {
            $this->statusStack[] = $node->getAttribute('value');
        } elseif ($node instanceof \RS_Vendor\Twig\Node\BlockNode) {
            $this->statusStack[] = isset($this->blocks[$node->getAttribute('name')]) ? $this->blocks[$node->getAttribute('name')] : $this->needEscaping($env);
        } elseif ($node instanceof \RS_Vendor\Twig\Node\ImportNode) {
            $this->safeVars[] = $node->getNode('var')->getAttribute('name');
        }
        return $node;
    }
    protected function doLeaveNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        if ($node instanceof \RS_Vendor\Twig\Node\ModuleNode) {
            $this->defaultStrategy = \false;
            $this->safeVars = [];
            $this->blocks = [];
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\FilterExpression) {
            return $this->preEscapeFilterNode($node, $env);
        } elseif ($node instanceof \RS_Vendor\Twig\Node\PrintNode && \false !== ($type = $this->needEscaping($env))) {
            $expression = $node->getNode('expr');
            if ($expression instanceof \RS_Vendor\Twig\Node\Expression\ConditionalExpression && $this->shouldUnwrapConditional($expression, $env, $type)) {
                return new \RS_Vendor\Twig\Node\DoNode($this->unwrapConditional($expression, $env, $type), $expression->getTemplateLine());
            }
            return $this->escapePrintNode($node, $env, $type);
        }
        if ($node instanceof \RS_Vendor\Twig\Node\AutoEscapeNode || $node instanceof \RS_Vendor\Twig\Node\BlockNode) {
            \array_pop($this->statusStack);
        } elseif ($node instanceof \RS_Vendor\Twig\Node\BlockReferenceNode) {
            $this->blocks[$node->getAttribute('name')] = $this->needEscaping($env);
        }
        return $node;
    }
    private function shouldUnwrapConditional(\RS_Vendor\Twig\Node\Expression\ConditionalExpression $expression, \RS_Vendor\Twig\Environment $env, $type)
    {
        $expr2Safe = $this->isSafeFor($type, $expression->getNode('expr2'), $env);
        $expr3Safe = $this->isSafeFor($type, $expression->getNode('expr3'), $env);
        return $expr2Safe !== $expr3Safe;
    }
    private function unwrapConditional(\RS_Vendor\Twig\Node\Expression\ConditionalExpression $expression, \RS_Vendor\Twig\Environment $env, $type)
    {
        // convert "echo a ? b : c" to "a ? echo b : echo c" recursively
        $expr2 = $expression->getNode('expr2');
        if ($expr2 instanceof \RS_Vendor\Twig\Node\Expression\ConditionalExpression && $this->shouldUnwrapConditional($expr2, $env, $type)) {
            $expr2 = $this->unwrapConditional($expr2, $env, $type);
        } else {
            $expr2 = $this->escapeInlinePrintNode(new \RS_Vendor\Twig\Node\Expression\InlinePrint($expr2, $expr2->getTemplateLine()), $env, $type);
        }
        $expr3 = $expression->getNode('expr3');
        if ($expr3 instanceof \RS_Vendor\Twig\Node\Expression\ConditionalExpression && $this->shouldUnwrapConditional($expr3, $env, $type)) {
            $expr3 = $this->unwrapConditional($expr3, $env, $type);
        } else {
            $expr3 = $this->escapeInlinePrintNode(new \RS_Vendor\Twig\Node\Expression\InlinePrint($expr3, $expr3->getTemplateLine()), $env, $type);
        }
        return new \RS_Vendor\Twig\Node\Expression\ConditionalExpression($expression->getNode('expr1'), $expr2, $expr3, $expression->getTemplateLine());
    }
    private function escapeInlinePrintNode(\RS_Vendor\Twig\Node\Expression\InlinePrint $node, \RS_Vendor\Twig\Environment $env, $type)
    {
        $expression = $node->getNode('node');
        if ($this->isSafeFor($type, $expression, $env)) {
            return $node;
        }
        return new \RS_Vendor\Twig\Node\Expression\InlinePrint($this->getEscaperFilter($type, $expression), $node->getTemplateLine());
    }
    private function escapePrintNode(\RS_Vendor\Twig\Node\PrintNode $node, \RS_Vendor\Twig\Environment $env, $type)
    {
        if (\false === $type) {
            return $node;
        }
        $expression = $node->getNode('expr');
        if ($this->isSafeFor($type, $expression, $env)) {
            return $node;
        }
        $class = \get_class($node);
        return new $class($this->getEscaperFilter($type, $expression), $node->getTemplateLine());
    }
    private function preEscapeFilterNode(\RS_Vendor\Twig\Node\Expression\FilterExpression $filter, \RS_Vendor\Twig\Environment $env)
    {
        $name = $filter->getNode('filter')->getAttribute('value');
        $type = $env->getFilter($name)->getPreEscape();
        if (null === $type) {
            return $filter;
        }
        $node = $filter->getNode('node');
        if ($this->isSafeFor($type, $node, $env)) {
            return $filter;
        }
        $filter->setNode('node', $this->getEscaperFilter($type, $node));
        return $filter;
    }
    private function isSafeFor($type, \RS_Vendor\Twig\Node\Node $expression, $env)
    {
        $safe = $this->safeAnalysis->getSafe($expression);
        if (null === $safe) {
            if (null === $this->traverser) {
                $this->traverser = new \RS_Vendor\Twig\NodeTraverser($env, [$this->safeAnalysis]);
            }
            $this->safeAnalysis->setSafeVars($this->safeVars);
            $this->traverser->traverse($expression);
            $safe = $this->safeAnalysis->getSafe($expression);
        }
        return \in_array($type, $safe) || \in_array('all', $safe);
    }
    private function needEscaping(\RS_Vendor\Twig\Environment $env)
    {
        if (\count($this->statusStack)) {
            return $this->statusStack[\count($this->statusStack) - 1];
        }
        return $this->defaultStrategy ? $this->defaultStrategy : \false;
    }
    private function getEscaperFilter(string $type, \RS_Vendor\Twig\Node\Node $node) : \RS_Vendor\Twig\Node\Expression\FilterExpression
    {
        $line = $node->getTemplateLine();
        $name = new \RS_Vendor\Twig\Node\Expression\ConstantExpression('escape', $line);
        $args = new \RS_Vendor\Twig\Node\Node([new \RS_Vendor\Twig\Node\Expression\ConstantExpression((string) $type, $line), new \RS_Vendor\Twig\Node\Expression\ConstantExpression(null, $line), new \RS_Vendor\Twig\Node\Expression\ConstantExpression(\true, $line)]);
        return new \RS_Vendor\Twig\Node\Expression\FilterExpression($node, $name, $args, $line);
    }
    public function getPriority()
    {
        return 0;
    }
}
\class_alias('RS_Vendor\\Twig\\NodeVisitor\\EscaperNodeVisitor', 'RS_Vendor\\Twig_NodeVisitor_Escaper');
