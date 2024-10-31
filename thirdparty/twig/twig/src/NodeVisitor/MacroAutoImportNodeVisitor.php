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
use RS_Vendor\Twig\Node\Expression\AssignNameExpression;
use RS_Vendor\Twig\Node\Expression\ConstantExpression;
use RS_Vendor\Twig\Node\Expression\GetAttrExpression;
use RS_Vendor\Twig\Node\Expression\MethodCallExpression;
use RS_Vendor\Twig\Node\Expression\NameExpression;
use RS_Vendor\Twig\Node\ImportNode;
use RS_Vendor\Twig\Node\ModuleNode;
use RS_Vendor\Twig\Node\Node;
/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class MacroAutoImportNodeVisitor implements \RS_Vendor\Twig\NodeVisitor\NodeVisitorInterface
{
    private $inAModule = \false;
    private $hasMacroCalls = \false;
    public function enterNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        if ($node instanceof \RS_Vendor\Twig\Node\ModuleNode) {
            $this->inAModule = \true;
            $this->hasMacroCalls = \false;
        }
        return $node;
    }
    public function leaveNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        if ($node instanceof \RS_Vendor\Twig\Node\ModuleNode) {
            $this->inAModule = \false;
            if ($this->hasMacroCalls) {
                $node->getNode('constructor_end')->setNode('_auto_macro_import', new \RS_Vendor\Twig\Node\ImportNode(new \RS_Vendor\Twig\Node\Expression\NameExpression('_self', 0), new \RS_Vendor\Twig\Node\Expression\AssignNameExpression('_self', 0), 0, 'import', \true));
            }
        } elseif ($this->inAModule) {
            if ($node instanceof \RS_Vendor\Twig\Node\Expression\GetAttrExpression && $node->getNode('node') instanceof \RS_Vendor\Twig\Node\Expression\NameExpression && '_self' === $node->getNode('node')->getAttribute('name') && $node->getNode('attribute') instanceof \RS_Vendor\Twig\Node\Expression\ConstantExpression) {
                $this->hasMacroCalls = \true;
                $name = $node->getNode('attribute')->getAttribute('value');
                $node = new \RS_Vendor\Twig\Node\Expression\MethodCallExpression($node->getNode('node'), 'macro_' . $name, $node->getNode('arguments'), $node->getTemplateLine());
                $node->setAttribute('safe', \true);
            }
        }
        return $node;
    }
    public function getPriority()
    {
        // we must be ran before auto-escaping
        return -10;
    }
}
