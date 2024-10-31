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
use RS_Vendor\Twig\Node\Node;
/**
 * Used to make node visitors compatible with Twig 1.x and 2.x.
 *
 * To be removed in Twig 3.1.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class AbstractNodeVisitor implements \RS_Vendor\Twig\NodeVisitor\NodeVisitorInterface
{
    public final function enterNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        return $this->doEnterNode($node, $env);
    }
    public final function leaveNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env)
    {
        return $this->doLeaveNode($node, $env);
    }
    /**
     * Called before child nodes are visited.
     *
     * @return Node The modified node
     */
    protected abstract function doEnterNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env);
    /**
     * Called after child nodes are visited.
     *
     * @return Node|null The modified node or null if the node must be removed
     */
    protected abstract function doLeaveNode(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Environment $env);
}
\class_alias('RS_Vendor\\Twig\\NodeVisitor\\AbstractNodeVisitor', 'RS_Vendor\\Twig_BaseNodeVisitor');
