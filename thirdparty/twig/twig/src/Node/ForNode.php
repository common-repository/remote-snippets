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
use RS_Vendor\Twig\Node\Expression\AbstractExpression;
use RS_Vendor\Twig\Node\Expression\AssignNameExpression;
/**
 * Represents a for node.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ForNode extends \RS_Vendor\Twig\Node\Node
{
    private $loop;
    public function __construct(\RS_Vendor\Twig\Node\Expression\AssignNameExpression $keyTarget, \RS_Vendor\Twig\Node\Expression\AssignNameExpression $valueTarget, \RS_Vendor\Twig\Node\Expression\AbstractExpression $seq, \RS_Vendor\Twig\Node\Expression\AbstractExpression $ifexpr = null, \RS_Vendor\Twig\Node\Node $body, \RS_Vendor\Twig\Node\Node $else = null, int $lineno, string $tag = null)
    {
        $body = new \RS_Vendor\Twig\Node\Node([$body, $this->loop = new \RS_Vendor\Twig\Node\ForLoopNode($lineno, $tag)]);
        if (null !== $ifexpr) {
            $body = new \RS_Vendor\Twig\Node\IfNode(new \RS_Vendor\Twig\Node\Node([$ifexpr, $body]), null, $lineno, $tag);
        }
        $nodes = ['key_target' => $keyTarget, 'value_target' => $valueTarget, 'seq' => $seq, 'body' => $body];
        if (null !== $else) {
            $nodes['else'] = $else;
        }
        parent::__construct($nodes, ['with_loop' => \true, 'ifexpr' => null !== $ifexpr], $lineno, $tag);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->addDebugInfo($this)->write("\$context['_parent'] = \$context;\n")->write("\$context['_seq'] = twig_ensure_traversable(")->subcompile($this->getNode('seq'))->raw(");\n");
        if ($this->hasNode('else')) {
            $compiler->write("\$context['_iterated'] = false;\n");
        }
        if ($this->getAttribute('with_loop')) {
            $compiler->write("\$context['loop'] = [\n")->write("  'parent' => \$context['_parent'],\n")->write("  'index0' => 0,\n")->write("  'index'  => 1,\n")->write("  'first'  => true,\n")->write("];\n");
            if (!$this->getAttribute('ifexpr')) {
                $compiler->write("if (is_array(\$context['_seq']) || (is_object(\$context['_seq']) && \$context['_seq'] instanceof \\Countable)) {\n")->indent()->write("\$length = count(\$context['_seq']);\n")->write("\$context['loop']['revindex0'] = \$length - 1;\n")->write("\$context['loop']['revindex'] = \$length;\n")->write("\$context['loop']['length'] = \$length;\n")->write("\$context['loop']['last'] = 1 === \$length;\n")->outdent()->write("}\n");
            }
        }
        $this->loop->setAttribute('else', $this->hasNode('else'));
        $this->loop->setAttribute('with_loop', $this->getAttribute('with_loop'));
        $this->loop->setAttribute('ifexpr', $this->getAttribute('ifexpr'));
        $compiler->write("foreach (\$context['_seq'] as ")->subcompile($this->getNode('key_target'))->raw(' => ')->subcompile($this->getNode('value_target'))->raw(") {\n")->indent()->subcompile($this->getNode('body'))->outdent()->write("}\n");
        if ($this->hasNode('else')) {
            $compiler->write("if (!\$context['_iterated']) {\n")->indent()->subcompile($this->getNode('else'))->outdent()->write("}\n");
        }
        $compiler->write("\$_parent = \$context['_parent'];\n");
        // remove some "private" loop variables (needed for nested loops)
        $compiler->write('unset($context[\'_seq\'], $context[\'_iterated\'], $context[\'' . $this->getNode('key_target')->getAttribute('name') . '\'], $context[\'' . $this->getNode('value_target')->getAttribute('name') . '\'], $context[\'_parent\'], $context[\'loop\']);' . "\n");
        // keep the values set in the inner context for variables defined in the outer context
        $compiler->write("\$context = array_intersect_key(\$context, \$_parent) + \$_parent;\n");
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\ForNode', 'RS_Vendor\\Twig_Node_For');
