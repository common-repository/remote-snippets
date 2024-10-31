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
 * Represents a sandbox node.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class SandboxNode extends \RS_Vendor\Twig\Node\Node
{
    public function __construct(\RS_Vendor\Twig\Node\Node $body, int $lineno, string $tag = null)
    {
        parent::__construct(['body' => $body], [], $lineno, $tag);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->addDebugInfo($this)->write("if (!\$alreadySandboxed = \$this->sandbox->isSandboxed()) {\n")->indent()->write("\$this->sandbox->enableSandbox();\n")->outdent()->write("}\n")->subcompile($this->getNode('body'))->write("if (!\$alreadySandboxed) {\n")->indent()->write("\$this->sandbox->disableSandbox();\n")->outdent()->write("}\n");
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\SandboxNode', 'RS_Vendor\\Twig_Node_Sandbox');
