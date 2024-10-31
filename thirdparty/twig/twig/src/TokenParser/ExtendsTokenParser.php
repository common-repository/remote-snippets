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
namespace RS_Vendor\Twig\TokenParser;

use RS_Vendor\Twig\Error\SyntaxError;
use RS_Vendor\Twig\Node\Node;
use RS_Vendor\Twig\Token;
/**
 * Extends a template by another one.
 *
 *  {% extends "base.html" %}
 */
final class ExtendsTokenParser extends \RS_Vendor\Twig\TokenParser\AbstractTokenParser
{
    public function parse(\RS_Vendor\Twig\Token $token)
    {
        $stream = $this->parser->getStream();
        if ($this->parser->peekBlockStack()) {
            throw new \RS_Vendor\Twig\Error\SyntaxError('Cannot use "extend" in a block.', $token->getLine(), $stream->getSourceContext());
        } elseif (!$this->parser->isMainScope()) {
            throw new \RS_Vendor\Twig\Error\SyntaxError('Cannot use "extend" in a macro.', $token->getLine(), $stream->getSourceContext());
        }
        if (null !== $this->parser->getParent()) {
            throw new \RS_Vendor\Twig\Error\SyntaxError('Multiple extends tags are forbidden.', $token->getLine(), $stream->getSourceContext());
        }
        $this->parser->setParent($this->parser->getExpressionParser()->parseExpression());
        $stream->expect(\RS_Vendor\Twig\Token::BLOCK_END_TYPE);
        return new \RS_Vendor\Twig\Node\Node();
    }
    public function getTag()
    {
        return 'extends';
    }
}
\class_alias('RS_Vendor\\Twig\\TokenParser\\ExtendsTokenParser', 'RS_Vendor\\Twig_TokenParser_Extends');
