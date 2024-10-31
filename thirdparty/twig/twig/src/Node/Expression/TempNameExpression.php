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
class TempNameExpression extends \RS_Vendor\Twig\Node\Expression\AbstractExpression
{
    public function __construct(string $name, int $lineno)
    {
        parent::__construct([], ['name' => $name], $lineno);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->raw('$_')->raw($this->getAttribute('name'))->raw('_');
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\TempNameExpression', 'RS_Vendor\\Twig_Node_Expression_TempName');
