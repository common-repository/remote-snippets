<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Node\Expression;

use RS_Vendor\Twig\Compiler;
class VariadicExpression extends \RS_Vendor\Twig\Node\Expression\ArrayExpression
{
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->raw('...');
        parent::compile($compiler);
    }
}
