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
use RS_Vendor\Twig\Extension\SandboxExtension;
use RS_Vendor\Twig\Template;
class GetAttrExpression extends \RS_Vendor\Twig\Node\Expression\AbstractExpression
{
    public function __construct(\RS_Vendor\Twig\Node\Expression\AbstractExpression $node, \RS_Vendor\Twig\Node\Expression\AbstractExpression $attribute, \RS_Vendor\Twig\Node\Expression\AbstractExpression $arguments = null, string $type, int $lineno)
    {
        $nodes = ['node' => $node, 'attribute' => $attribute];
        if (null !== $arguments) {
            $nodes['arguments'] = $arguments;
        }
        parent::__construct($nodes, ['type' => $type, 'is_defined_test' => \false, 'ignore_strict_check' => \false, 'optimizable' => \true], $lineno);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $env = $compiler->getEnvironment();
        // optimize array calls
        if ($this->getAttribute('optimizable') && (!$env->isStrictVariables() || $this->getAttribute('ignore_strict_check')) && !$this->getAttribute('is_defined_test') && \RS_Vendor\Twig\Template::ARRAY_CALL === $this->getAttribute('type')) {
            $var = '$' . $compiler->getVarName();
            $compiler->raw('((' . $var . ' = ')->subcompile($this->getNode('node'))->raw(') && is_array(')->raw($var)->raw(') || ')->raw($var)->raw(' instanceof ArrayAccess ? (')->raw($var)->raw('[')->subcompile($this->getNode('attribute'))->raw('] ?? null) : null)');
            return;
        }
        $compiler->raw('twig_get_attribute($this->env, $this->source, ');
        if ($this->getAttribute('ignore_strict_check')) {
            $this->getNode('node')->setAttribute('ignore_strict_check', \true);
        }
        $compiler->subcompile($this->getNode('node'))->raw(', ')->subcompile($this->getNode('attribute'));
        if ($this->hasNode('arguments')) {
            $compiler->raw(', ')->subcompile($this->getNode('arguments'));
        } else {
            $compiler->raw(', []');
        }
        $compiler->raw(', ')->repr($this->getAttribute('type'))->raw(', ')->repr($this->getAttribute('is_defined_test'))->raw(', ')->repr($this->getAttribute('ignore_strict_check'))->raw(', ')->repr($env->hasExtension(\RS_Vendor\Twig\Extension\SandboxExtension::class))->raw(', ')->repr($this->getNode('node')->getTemplateLine())->raw(')');
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\GetAttrExpression', 'RS_Vendor\\Twig_Node_Expression_GetAttr');
