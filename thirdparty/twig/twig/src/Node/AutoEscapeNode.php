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
 * Represents an autoescape node.
 *
 * The value is the escaping strategy (can be html, js, ...)
 *
 * The true value is equivalent to html.
 *
 * If autoescaping is disabled, then the value is false.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class AutoEscapeNode extends \RS_Vendor\Twig\Node\Node
{
    public function __construct($value, \RS_Vendor\Twig\Node\Node $body, int $lineno, string $tag = 'autoescape')
    {
        parent::__construct(['body' => $body], ['value' => $value], $lineno, $tag);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->subcompile($this->getNode('body'));
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\AutoEscapeNode', 'RS_Vendor\\Twig_Node_AutoEscape');
