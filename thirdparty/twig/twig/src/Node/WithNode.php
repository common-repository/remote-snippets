<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Node;

use RS_Vendor\Twig\Compiler;
/**
 * Represents a nested "with" scope.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class WithNode extends \RS_Vendor\Twig\Node\Node
{
    public function __construct(\RS_Vendor\Twig\Node\Node $body, \RS_Vendor\Twig\Node\Node $variables = null, bool $only = \false, int $lineno, string $tag = null)
    {
        $nodes = ['body' => $body];
        if (null !== $variables) {
            $nodes['variables'] = $variables;
        }
        parent::__construct($nodes, ['only' => $only], $lineno, $tag);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->addDebugInfo($this);
        if ($this->hasNode('variables')) {
            $node = $this->getNode('variables');
            $varsName = $compiler->getVarName();
            $compiler->write(\sprintf('$%s = ', $varsName))->subcompile($node)->raw(";\n")->write(\sprintf("if (!twig_test_iterable(\$%s)) {\n", $varsName))->indent()->write("throw new RuntimeError('Variables passed to the \"with\" tag must be a hash.', ")->repr($node->getTemplateLine())->raw(", \$this->getSourceContext());\n")->outdent()->write("}\n")->write(\sprintf("\$%s = twig_to_array(\$%s);\n", $varsName, $varsName));
            if ($this->getAttribute('only')) {
                $compiler->write("\$context = ['_parent' => \$context];\n");
            } else {
                $compiler->write("\$context['_parent'] = \$context;\n");
            }
            $compiler->write(\sprintf("\$context = \$this->env->mergeGlobals(array_merge(\$context, \$%s));\n", $varsName));
        } else {
            $compiler->write("\$context['_parent'] = \$context;\n");
        }
        $compiler->subcompile($this->getNode('body'))->write("\$context = \$context['_parent'];\n");
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\WithNode', 'RS_Vendor\\Twig_Node_With');
