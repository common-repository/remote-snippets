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
namespace RS_Vendor\Twig\Node\Expression\Binary;

use RS_Vendor\Twig\Compiler;
use RS_Vendor\Twig\Node\Expression\AbstractExpression;
use RS_Vendor\Twig\Node\Node;
abstract class AbstractBinary extends \RS_Vendor\Twig\Node\Expression\AbstractExpression
{
    public function __construct(\RS_Vendor\Twig\Node\Node $left, \RS_Vendor\Twig\Node\Node $right, int $lineno)
    {
        parent::__construct(['left' => $left, 'right' => $right], [], $lineno);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->raw('(')->subcompile($this->getNode('left'))->raw(' ');
        $this->operator($compiler);
        $compiler->raw(' ')->subcompile($this->getNode('right'))->raw(')');
    }
    public abstract function operator(\RS_Vendor\Twig\Compiler $compiler);
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\Binary\\AbstractBinary', 'RS_Vendor\\Twig_Node_Expression_Binary');
