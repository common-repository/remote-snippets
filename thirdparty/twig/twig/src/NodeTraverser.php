<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig;

use RS_Vendor\Twig\Node\Node;
use RS_Vendor\Twig\NodeVisitor\NodeVisitorInterface;
/**
 * A node traverser.
 *
 * It visits all nodes and their children and calls the given visitor for each.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class NodeTraverser
{
    private $env;
    private $visitors = [];
    /**
     * @param NodeVisitorInterface[] $visitors
     */
    public function __construct(\RS_Vendor\Twig\Environment $env, array $visitors = [])
    {
        $this->env = $env;
        foreach ($visitors as $visitor) {
            $this->addVisitor($visitor);
        }
    }
    public function addVisitor(\RS_Vendor\Twig\NodeVisitor\NodeVisitorInterface $visitor)
    {
        $this->visitors[$visitor->getPriority()][] = $visitor;
    }
    /**
     * Traverses a node and calls the registered visitors.
     */
    public function traverse(\RS_Vendor\Twig\Node\Node $node) : \RS_Vendor\Twig\Node\Node
    {
        \ksort($this->visitors);
        foreach ($this->visitors as $visitors) {
            foreach ($visitors as $visitor) {
                $node = $this->traverseForVisitor($visitor, $node);
            }
        }
        return $node;
    }
    /**
     * @return Node|null
     */
    private function traverseForVisitor(\RS_Vendor\Twig\NodeVisitor\NodeVisitorInterface $visitor, \RS_Vendor\Twig\Node\Node $node)
    {
        $node = $visitor->enterNode($node, $this->env);
        foreach ($node as $k => $n) {
            if (\false !== ($m = $this->traverseForVisitor($visitor, $n)) && null !== $m) {
                if ($m !== $n) {
                    $node->setNode($k, $m);
                }
            } else {
                if (\false === $m) {
                    @\trigger_error('Returning "false" to remove a Node from NodeVisitorInterface::leaveNode() is deprecated since Twig version 2.9; return "null" instead.', \E_USER_DEPRECATED);
                }
                $node->removeNode($k);
            }
        }
        return $visitor->leaveNode($node, $this->env);
    }
}
\class_alias('RS_Vendor\\Twig\\NodeTraverser', 'RS_Vendor\\Twig_NodeTraverser');
