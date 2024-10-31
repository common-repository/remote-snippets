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
class ConditionalExpression extends \RS_Vendor\Twig\Node\Expression\AbstractExpression
{
    public function __construct(\RS_Vendor\Twig\Node\Expression\AbstractExpression $expr1, \RS_Vendor\Twig\Node\Expression\AbstractExpression $expr2, \RS_Vendor\Twig\Node\Expression\AbstractExpression $expr3, int $lineno)
    {
        parent::__construct(['expr1' => $expr1, 'expr2' => $expr2, 'expr3' => $expr3], [], $lineno);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->raw('((')->subcompile($this->getNode('expr1'))->raw(') ? (')->subcompile($this->getNode('expr2'))->raw(') : (')->subcompile($this->getNode('expr3'))->raw('))');
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\ConditionalExpression', 'RS_Vendor\\Twig_Node_Expression_Conditional');
