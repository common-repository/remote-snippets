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
use RS_Vendor\Twig\Node\Expression\BlockReferenceExpression;
use RS_Vendor\Twig\Node\Expression\ConditionalExpression;
use RS_Vendor\Twig\Node\Expression\ConstantExpression;
use RS_Vendor\Twig\Node\Expression\FilterExpression;
use RS_Vendor\Twig\Node\Expression\FunctionExpression;
use RS_Vendor\Twig\Node\Expression\GetAttrExpression;
use RS_Vendor\Twig\Node\Expression\MethodCallExpression;
use RS_Vendor\Twig\Node\Expression\NameExpression;
use RS_Vendor\Twig\Node\Expression\ParentExpression;
use RS_Vendor\Twig\Node\Node;
final class SafeAnalysisNodeVisitor extends \RS_Vendor\Twig\NodeVisitor\AbstractNodeVisitor
{
    private $data = [];
    private $safeVars = [];
    public function setSafeVars($safeVars)
    {
        $this->safeVars = $safeVars;
    }
    public function getSafe(\RS_Vendor\Twig\Node\Node $node)
    {
        $hash = \spl_object_hash($node);
        if (!isset($this->data[$hash])) {
            return;
        }
        foreach ($this->data[$hash] as $bucket) {
            if ($bucket['key'] !== $node) {
                continue;
            }
            if (\in_array('html_attr', $bucket['value'])) {
                $bucket['value'][] = 'html';
            }
            return $bucket['value'];
        }
    }
    private function setSafe(\RS_Vendor\Twig\Node\Node $node, array $safe)
    {
        $hash = \spl_object_hash($node);
        if (isset($this->data[$hash])) {
            foreach ($this->data[$hash] as &$bucket) {
                if ($bucket['key'] === $node) {
                    $bucket['value'] = $safe;
                    return;
                }
            }
        }
        $this->data[$hash][] = ['key' => $node, 'value' => $safe];
    }
    protected function doEnterNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        return $node;
    }
    protected function doLeaveNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        if ($node instanceof \RS_Vendor\Twig\Node\Expression\ConstantExpression) {
            // constants are marked safe for all
            $this->setSafe($node, ['all']);
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\BlockReferenceExpression) {
            // blocks are safe by definition
            $this->setSafe($node, ['all']);
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\ParentExpression) {
            // parent block is safe by definition
            $this->setSafe($node, ['all']);
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\ConditionalExpression) {
            // intersect safeness of both operands
            $safe = $this->intersectSafe($this->getSafe($node->getNode('expr2')), $this->getSafe($node->getNode('expr3')));
            $this->setSafe($node, $safe);
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\FilterExpression) {
            // filter expression is safe when the filter is safe
            $name = $node->getNode('filter')->getAttribute('value');
            $args = $node->getNode('arguments');
            if (\false !== ($filter = $env->getFilter($name))) {
                $safe = $filter->getSafe($args);
                if (null === $safe) {
                    $safe = $this->intersectSafe($this->getSafe($node->getNode('node')), $filter->getPreservesSafety());
                }
                $this->setSafe($node, $safe);
            } else {
                $this->setSafe($node, []);
            }
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\FunctionExpression) {
            // function expression is safe when the function is safe
            $name = $node->getAttribute('name');
            $args = $node->getNode('arguments');
            $function = $env->getFunction($name);
            if (\false !== $function) {
                $this->setSafe($node, $function->getSafe($args));
            } else {
                $this->setSafe($node, []);
            }
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\MethodCallExpression) {
            if ($node->getAttribute('safe')) {
                $this->setSafe($node, ['all']);
            } else {
                $this->setSafe($node, []);
            }
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\GetAttrExpression && $node->getNode('node') instanceof \RS_Vendor\Twig\Node\Expression\NameExpression) {
            $name = $node->getNode('node')->getAttribute('name');
            if (\in_array($name, $this->safeVars)) {
                $this->setSafe($node, ['all']);
            } else {
                $this->setSafe($node, []);
            }
        } else {
            $this->setSafe($node, []);
        }
        return $node;
    }
    private function intersectSafe(array $a = null, array $b = null) : array
    {
        if (null === $a || null === $b) {
            return [];
        }
        if (\in_array('all', $a)) {
            return $b;
        }
        if (\in_array('all', $b)) {
            return $a;
        }
        return \array_intersect($a, $b);
    }
    public function getPriority()
    {
        return 0;
    }
}
\class_alias('RS_Vendor\\Twig\\NodeVisitor\\SafeAnalysisNodeVisitor', 'RS_Vendor\\Twig_NodeVisitor_SafeAnalysis');
