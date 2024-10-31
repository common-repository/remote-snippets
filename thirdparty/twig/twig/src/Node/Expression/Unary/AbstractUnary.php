<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 * (c) Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Node\Expression\Unary;

use RS_Vendor\Twig\Compiler;
use RS_Vendor\Twig\Node\Expression\AbstractExpression;
use RS_Vendor\Twig\Node\Node;
abstract class AbstractUnary extends \RS_Vendor\Twig\Node\Expression\AbstractExpression
{
    public function __construct(\RS_Vendor\Twig\Node\Node $node, int $lineno)
    {
        parent::__construct(['node' => $node], [], $lineno);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->raw(' ');
        $this->operator($compiler);
        $compiler->subcompile($this->getNode('node'));
    }
    public abstract function operator(\RS_Vendor\Twig\Compiler $compiler);
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\Unary\\AbstractUnary', 'RS_Vendor\\Twig_Node_Expression_Unary');
