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
namespace RS_Vendor\Twig\Node\Expression;

use RS_Vendor\Twig\Compiler;
class AssignNameExpression extends \RS_Vendor\Twig\Node\Expression\NameExpression
{
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->raw('$context[')->string($this->getAttribute('name'))->raw(']');
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\AssignNameExpression', 'RS_Vendor\\Twig_Node_Expression_AssignName');
