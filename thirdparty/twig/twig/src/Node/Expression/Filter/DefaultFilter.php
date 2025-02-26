<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Node\Expression\Filter;

use RS_Vendor\Twig\Compiler;
use RS_Vendor\Twig\Node\Expression\ConditionalExpression;
use RS_Vendor\Twig\Node\Expression\ConstantExpression;
use RS_Vendor\Twig\Node\Expression\FilterExpression;
use RS_Vendor\Twig\Node\Expression\GetAttrExpression;
use RS_Vendor\Twig\Node\Expression\NameExpression;
use RS_Vendor\Twig\Node\Expression\Test\DefinedTest;
use RS_Vendor\Twig\Node\Node;
/**
 * Returns the value or the default value when it is undefined or empty.
 *
 *  {{ var.foo|default('foo item on var is not defined') }}
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class DefaultFilter extends \RS_Vendor\Twig\Node\Expression\FilterExpression
{
    public function __construct(\RS_Vendor\Twig\Node\Node $node, \RS_Vendor\Twig\Node\Expression\ConstantExpression $filterName, \RS_Vendor\Twig\Node\Node $arguments, int $lineno, string $tag = null)
    {
        $default = new \RS_Vendor\Twig\Node\Expression\FilterExpression($node, new \RS_Vendor\Twig\Node\Expression\ConstantExpression('default', $node->getTemplateLine()), $arguments, $node->getTemplateLine());
        if ('default' === $filterName->getAttribute('value') && ($node instanceof \RS_Vendor\Twig\Node\Expression\NameExpression || $node instanceof \RS_Vendor\Twig\Node\Expression\GetAttrExpression)) {
            $test = new \RS_Vendor\Twig\Node\Expression\Test\DefinedTest(clone $node, 'defined', new \RS_Vendor\Twig\Node\Node(), $node->getTemplateLine());
            $false = \count($arguments) ? $arguments->getNode(0) : new \RS_Vendor\Twig\Node\Expression\ConstantExpression('', $node->getTemplateLine());
            $node = new \RS_Vendor\Twig\Node\Expression\ConditionalExpression($test, $default, $false, $node->getTemplateLine());
        } else {
            $node = $default;
        }
        parent::__construct($node, $filterName, $arguments, $lineno, $tag);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->subcompile($this->getNode('node'));
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\Filter\\DefaultFilter', 'RS_Vendor\\Twig_Node_Expression_Filter_Default');
