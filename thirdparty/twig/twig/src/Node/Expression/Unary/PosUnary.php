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
class PosUnary extends \RS_Vendor\Twig\Node\Expression\Unary\AbstractUnary
{
    public function operator(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->raw('+');
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\Unary\\PosUnary', 'RS_Vendor\\Twig_Node_Expression_Unary_Pos');
