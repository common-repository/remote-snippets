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
use RS_Vendor\Twig\Node\Node;
class FunctionExpression extends \RS_Vendor\Twig\Node\Expression\CallExpression
{
    public function __construct(string $name, \RS_Vendor\Twig\Node\Node $arguments, int $lineno)
    {
        parent::__construct(['arguments' => $arguments], ['name' => $name, 'is_defined_test' => \false], $lineno);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $name = $this->getAttribute('name');
        $function = $compiler->getEnvironment()->getFunction($name);
        $this->setAttribute('name', $name);
        $this->setAttribute('type', 'function');
        $this->setAttribute('needs_environment', $function->needsEnvironment());
        $this->setAttribute('needs_context', $function->needsContext());
        $this->setAttribute('arguments', $function->getArguments());
        $callable = $function->getCallable();
        if ('constant' === $name && $this->getAttribute('is_defined_test')) {
            $callable = 'twig_constant_is_defined';
        }
        $this->setAttribute('callable', $callable);
        $this->setAttribute('is_variadic', $function->isVariadic());
        $this->compileCallable($compiler);
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\FunctionExpression', 'RS_Vendor\\Twig_Node_Expression_Function');
