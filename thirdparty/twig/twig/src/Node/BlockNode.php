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
namespace RS_Vendor\Twig\Node;

use RS_Vendor\Twig\Compiler;
/**
 * Represents a block node.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class BlockNode extends \RS_Vendor\Twig\Node\Node
{
    public function __construct(string $name, \RS_Vendor\Twig\Node\Node $body, int $lineno, string $tag = null)
    {
        parent::__construct(['body' => $body], ['name' => $name], $lineno, $tag);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->addDebugInfo($this)->write(\sprintf("public function block_%s(\$context, array \$blocks = [])\n", $this->getAttribute('name')), "{\n")->indent()->write("\$macros = \$this->macros;\n");
        $compiler->subcompile($this->getNode('body'))->outdent()->write("}\n\n");
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\BlockNode', 'RS_Vendor\\Twig_Node_Block');
