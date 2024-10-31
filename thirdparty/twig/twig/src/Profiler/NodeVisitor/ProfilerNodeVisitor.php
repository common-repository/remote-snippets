<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Profiler\NodeVisitor;

use RS_Vendor\Twig\Environment;
use RS_Vendor\Twig\Node\BlockNode;
use RS_Vendor\Twig\Node\BodyNode;
use RS_Vendor\Twig\Node\MacroNode;
use RS_Vendor\Twig\Node\ModuleNode;
use RS_Vendor\Twig\Node\Node;
use RS_Vendor\Twig\NodeVisitor\AbstractNodeVisitor;
use RS_Vendor\Twig\Profiler\Node\EnterProfileNode;
use RS_Vendor\Twig\Profiler\Node\LeaveProfileNode;
use RS_Vendor\Twig\Profiler\Profile;
/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class ProfilerNodeVisitor extends \RS_Vendor\Twig\NodeVisitor\AbstractNodeVisitor
{
    private $extensionName;
    public function __construct(string $extensionName)
    {
        $this->extensionName = $extensionName;
    }
    protected function doEnterNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        return $node;
    }
    protected function doLeaveNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        if ($node instanceof \RS_Vendor\Twig\Node\ModuleNode) {
            $varName = $this->getVarName();
            $node->setNode('display_start', new \RS_Vendor\Twig\Node\Node([new \RS_Vendor\Twig\Profiler\Node\EnterProfileNode($this->extensionName, \RS_Vendor\Twig\Profiler\Profile::TEMPLATE, $node->getTemplateName(), $varName), $node->getNode('display_start')]));
            $node->setNode('display_end', new \RS_Vendor\Twig\Node\Node([new \RS_Vendor\Twig\Profiler\Node\LeaveProfileNode($varName), $node->getNode('display_end')]));
        } elseif ($node instanceof \RS_Vendor\Twig\Node\BlockNode) {
            $varName = $this->getVarName();
            $node->setNode('body', new \RS_Vendor\Twig\Node\BodyNode([new \RS_Vendor\Twig\Profiler\Node\EnterProfileNode($this->extensionName, \RS_Vendor\Twig\Profiler\Profile::BLOCK, $node->getAttribute('name'), $varName), $node->getNode('body'), new \RS_Vendor\Twig\Profiler\Node\LeaveProfileNode($varName)]));
        } elseif ($node instanceof \RS_Vendor\Twig\Node\MacroNode) {
            $varName = $this->getVarName();
            $node->setNode('body', new \RS_Vendor\Twig\Node\BodyNode([new \RS_Vendor\Twig\Profiler\Node\EnterProfileNode($this->extensionName, \RS_Vendor\Twig\Profiler\Profile::MACRO, $node->getAttribute('name'), $varName), $node->getNode('body'), new \RS_Vendor\Twig\Profiler\Node\LeaveProfileNode($varName)]));
        }
        return $node;
    }
    private function getVarName() : string
    {
        return \sprintf('__internal_%s', \hash('sha256', $this->extensionName));
    }
    public function getPriority()
    {
        return 0;
    }
}
\class_alias('RS_Vendor\\Twig\\Profiler\\NodeVisitor\\ProfilerNodeVisitor', 'RS_Vendor\\Twig_Profiler_NodeVisitor_Profiler');
