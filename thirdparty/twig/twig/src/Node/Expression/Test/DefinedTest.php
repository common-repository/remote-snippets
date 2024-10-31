<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Node\Expression\Test;

use RS_Vendor\Twig\Compiler;
use RS_Vendor\Twig\Error\SyntaxError;
use RS_Vendor\Twig\Node\Expression\ArrayExpression;
use RS_Vendor\Twig\Node\Expression\BlockReferenceExpression;
use RS_Vendor\Twig\Node\Expression\ConstantExpression;
use RS_Vendor\Twig\Node\Expression\FunctionExpression;
use RS_Vendor\Twig\Node\Expression\GetAttrExpression;
use RS_Vendor\Twig\Node\Expression\MethodCallExpression;
use RS_Vendor\Twig\Node\Expression\NameExpression;
use RS_Vendor\Twig\Node\Expression\TestExpression;
use RS_Vendor\Twig\Node\Node;
/**
 * Checks if a variable is defined in the current context.
 *
 *    {# defined works with variable names and variable attributes #}
 *    {% if foo is defined %}
 *        {# ... #}
 *    {% endif %}
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class DefinedTest extends \RS_Vendor\Twig\Node\Expression\TestExpression
{
    public function __construct(\RS_Vendor\Twig\Node\Node $node, string $name, \RS_Vendor\Twig\Node\Node $arguments = null, int $lineno)
    {
        if ($node instanceof \RS_Vendor\Twig\Node\Expression\NameExpression) {
            $node->setAttribute('is_defined_test', \true);
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\GetAttrExpression) {
            $node->setAttribute('is_defined_test', \true);
            $this->changeIgnoreStrictCheck($node);
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\BlockReferenceExpression) {
            $node->setAttribute('is_defined_test', \true);
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\FunctionExpression && 'constant' === $node->getAttribute('name')) {
            $node->setAttribute('is_defined_test', \true);
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\ConstantExpression || $node instanceof \RS_Vendor\Twig\Node\Expression\ArrayExpression) {
            $node = new \RS_Vendor\Twig\Node\Expression\ConstantExpression(\true, $node->getTemplateLine());
        } elseif ($node instanceof \RS_Vendor\Twig\Node\Expression\MethodCallExpression) {
            $node->setAttribute('is_defined_test', \true);
        } else {
            throw new \RS_Vendor\Twig\Error\SyntaxError('The "defined" test only works with simple variables.', $lineno);
        }
        parent::__construct($node, $name, $arguments, $lineno);
    }
    private function changeIgnoreStrictCheck(\RS_Vendor\Twig\Node\Expression\GetAttrExpression $node)
    {
        $node->setAttribute('optimizable', \false);
        $node->setAttribute('ignore_strict_check', \true);
        if ($node->getNode('node') instanceof \RS_Vendor\Twig\Node\Expression\GetAttrExpression) {
            $this->changeIgnoreStrictCheck($node->getNode('node'));
        }
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->subcompile($this->getNode('node'));
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\Test\\DefinedTest', 'RS_Vendor\\Twig_Node_Expression_Test_Defined');
