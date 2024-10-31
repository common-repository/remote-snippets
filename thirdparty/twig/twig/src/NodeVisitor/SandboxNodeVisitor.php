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
use RS_Vendor\Twig\Node\CheckSecurityNode;
use RS_Vendor\Twig\Node\CheckToStringNode;
use RS_Vendor\Twig\Node\Expression\Binary\ConcatBinary;
use RS_Vendor\Twig\Node\Expression\Binary\RangeBinary;
use RS_Vendor\Twig\Node\Expression\FilterExpression;
use RS_Vendor\Twig\Node\Expression\FunctionExpression;
use RS_Vendor\Twig\Node\Expression\GetAttrExpression;
use RS_Vendor\Twig\Node\Expression\NameExpression;
use RS_Vendor\Twig\Node\ModuleNode;
use RS_Vendor\Twig\Node\Node;
use RS_Vendor\Twig\Node\PrintNode;
use RS_Vendor\Twig\Node\SetNode;
/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class SandboxNodeVisitor extends \RS_Vendor\Twig\NodeVisitor\AbstractNodeVisitor
{
    private $inAModule = \false;
    private $tags;
    private $filters;
    private $functions;
    private $needsToStringWrap = \false;
    protected function doEnterNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        if ($node instanceof \RS_Vendor\Twig\Node\ModuleNode) {
            $this->inAModule = \true;
            $this->tags = [];
            $this->filters = [];
            $this->functions = [];
            return $node;
        } elseif ($this->inAModule) {
            // look for tags
            if ($node->getNodeTag() && !isset($this->tags[$node->getNodeTag()])) {
                $this->tags[$node->getNodeTag()] = $node;
            }
            // look for filters
            if ($node instanceof \RS_Vendor\Twig\Node\Expression\FilterExpression && !isset($this->filters[$node->getNode('filter')->getAttribute('value')])) {
                $this->filters[$node->getNode('filter')->getAttribute('value')] = $node;
            }
            // look for functions
            if ($node instanceof \RS_Vendor\Twig\Node\Expression\FunctionExpression && !isset($this->functions[$node->getAttribute('name')])) {
                $this->functions[$node->getAttribute('name')] = $node;
            }
            // the .. operator is equivalent to the range() function
            if ($node instanceof \RS_Vendor\Twig\Node\Expression\Binary\RangeBinary && !isset($this->functions['range'])) {
                $this->functions['range'] = $node;
            }
            if ($node instanceof \RS_Vendor\Twig\Node\PrintNode) {
                $this->needsToStringWrap = \true;
                $this->wrapNode($node, 'expr');
            }
            if ($node instanceof \RS_Vendor\Twig\Node\SetNode && !$node->getAttribute('capture')) {
                $this->needsToStringWrap = \true;
            }
            // wrap outer nodes that can implicitly call __toString()
            if ($this->needsToStringWrap) {
                if ($node instanceof \RS_Vendor\Twig\Node\Expression\Binary\ConcatBinary) {
                    $this->wrapNode($node, 'left');
                    $this->wrapNode($node, 'right');
                }
                if ($node instanceof \RS_Vendor\Twig\Node\Expression\FilterExpression) {
                    $this->wrapNode($node, 'node');
                    $this->wrapArrayNode($node, 'arguments');
                }
                if ($node instanceof \RS_Vendor\Twig\Node\Expression\FunctionExpression) {
                    $this->wrapArrayNode($node, 'arguments');
                }
            }
        }
        return $node;
    }
    protected function doLeaveNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        if ($node instanceof \RS_Vendor\Twig\Node\ModuleNode) {
            $this->inAModule = \false;
            $node->getNode('constructor_end')->setNode('_security_check', new \RS_Vendor\Twig\Node\Node([new \RS_Vendor\Twig\Node\CheckSecurityNode($this->filters, $this->tags, $this->functions), $node->getNode('display_start')]));
        } elseif ($this->inAModule) {
            if ($node instanceof \RS_Vendor\Twig\Node\PrintNode || $node instanceof \RS_Vendor\Twig\Node\SetNode) {
                $this->needsToStringWrap = \false;
            }
        }
        return $node;
    }
    private function wrapNode(\RS_Vendor\Twig\Node\Node $node, string $name)
    {
        $expr = $node->getNode($name);
        if ($expr instanceof \RS_Vendor\Twig\Node\Expression\NameExpression || $expr instanceof \RS_Vendor\Twig\Node\Expression\GetAttrExpression) {
            $node->setNode($name, new \RS_Vendor\Twig\Node\CheckToStringNode($expr));
        }
    }
    private function wrapArrayNode(\RS_Vendor\Twig\Node\Node $node, string $name)
    {
        $args = $node->getNode($name);
        foreach ($args as $name => $_) {
            $this->wrapNode($args, $name);
        }
    }
    public function getPriority()
    {
        return 0;
    }
}
\class_alias('RS_Vendor\\Twig\\NodeVisitor\\SandboxNodeVisitor', 'RS_Vendor\\Twig_NodeVisitor_Sandbox');
