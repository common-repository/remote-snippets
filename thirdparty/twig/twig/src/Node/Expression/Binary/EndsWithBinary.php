<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Node\Expression\Binary;

use RS_Vendor\Twig\Compiler;
class EndsWithBinary extends \RS_Vendor\Twig\Node\Expression\Binary\AbstractBinary
{
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $left = $compiler->getVarName();
        $right = $compiler->getVarName();
        $compiler->raw(\sprintf('(is_string($%s = ', $left))->subcompile($this->getNode('left'))->raw(\sprintf(') && is_string($%s = ', $right))->subcompile($this->getNode('right'))->raw(\sprintf(') && (\'\' === $%2$s || $%2$s === substr($%1$s, -strlen($%2$s))))', $left, $right));
    }
    public function operator(\RS_Vendor\Twig\Compiler $compiler)
    {
        return $compiler->raw('');
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\Binary\\EndsWithBinary', 'RS_Vendor\\Twig_Node_Expression_Binary_EndsWith');
