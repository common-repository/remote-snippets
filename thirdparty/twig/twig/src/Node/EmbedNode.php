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
use RS_Vendor\Twig\Node\Expression\AbstractExpression;
use RS_Vendor\Twig\Node\Expression\ConstantExpression;
/**
 * Represents an embed node.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class EmbedNode extends \RS_Vendor\Twig\Node\IncludeNode
{
    // we don't inject the module to avoid node visitors to traverse it twice (as it will be already visited in the main module)
    public function __construct(string $name, int $index, \RS_Vendor\Twig\Node\Expression\AbstractExpression $variables = null, bool $only = \false, bool $ignoreMissing = \false, int $lineno, string $tag = null)
    {
        parent::__construct(new \RS_Vendor\Twig\Node\Expression\ConstantExpression('not_used', $lineno), $variables, $only, $ignoreMissing, $lineno, $tag);
        $this->setAttribute('name', $name);
        $this->setAttribute('index', $index);
    }
    protected function addGetTemplate(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->write('$this->loadTemplate(')->string($this->getAttribute('name'))->raw(', ')->repr($this->getTemplateName())->raw(', ')->repr($this->getTemplateLine())->raw(', ')->string($this->getAttribute('index'))->raw(')');
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\EmbedNode', 'RS_Vendor\\Twig_Node_Embed');
