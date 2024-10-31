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
 * Represents an if node.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class IfNode extends \RS_Vendor\Twig\Node\Node
{
    public function __construct(\RS_Vendor\Twig\Node\Node $tests, \RS_Vendor\Twig\Node\Node $else = null, int $lineno, string $tag = null)
    {
        $nodes = ['tests' => $tests];
        if (null !== $else) {
            $nodes['else'] = $else;
        }
        parent::__construct($nodes, [], $lineno, $tag);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->addDebugInfo($this);
        for ($i = 0, $count = \count($this->getNode('tests')); $i < $count; $i += 2) {
            if ($i > 0) {
                $compiler->outdent()->write('} elseif (');
            } else {
                $compiler->write('if (');
            }
            $compiler->subcompile($this->getNode('tests')->getNode($i))->raw(") {\n")->indent()->subcompile($this->getNode('tests')->getNode($i + 1));
        }
        if ($this->hasNode('else')) {
            $compiler->outdent()->write("} else {\n")->indent()->subcompile($this->getNode('else'));
        }
        $compiler->outdent()->write("}\n");
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\IfNode', 'RS_Vendor\\Twig_Node_If');
