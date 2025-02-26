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
use RS_Vendor\Twig\Node\BlockNode;
use RS_Vendor\Twig\Node\BlockReferenceNode;
use RS_Vendor\Twig\Node\Node;
use RS_Vendor\Twig\Node\PrintNode;
use RS_Vendor\Twig\Token;
/**
 * Marks a section of a template as being reusable.
 *
 *  {% block head %}
 *    <link rel="stylesheet" href="style.css" />
 *    <title>{% block title %}{% endblock %} - My Webpage</title>
 *  {% endblock %}
 */
final class BlockTokenParser extends \RS_Vendor\Twig\TokenParser\AbstractTokenParser
{
    public function parse(\RS_Vendor\Twig\Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $name = $stream->expect(
            /* Token::NAME_TYPE */
            5
        )->getValue();
        if ($this->parser->hasBlock($name)) {
            throw new \RS_Vendor\Twig\Error\SyntaxError(\sprintf("The block '%s' has already been defined line %d.", $name, $this->parser->getBlock($name)->getTemplateLine()), $stream->getCurrent()->getLine(), $stream->getSourceContext());
        }
        $this->parser->setBlock($name, $block = new \RS_Vendor\Twig\Node\BlockNode($name, new \RS_Vendor\Twig\Node\Node([]), $lineno));
        $this->parser->pushLocalScope();
        $this->parser->pushBlockStack($name);
        if ($stream->nextIf(
            /* Token::BLOCK_END_TYPE */
            3
        )) {
            $body = $this->parser->subparse([$this, 'decideBlockEnd'], \true);
            if ($token = $stream->nextIf(
                /* Token::NAME_TYPE */
                5
            )) {
                $value = $token->getValue();
                if ($value != $name) {
                    throw new \RS_Vendor\Twig\Error\SyntaxError(\sprintf('Expected endblock for block "%s" (but "%s" given).', $name, $value), $stream->getCurrent()->getLine(), $stream->getSourceContext());
                }
            }
        } else {
            $body = new \RS_Vendor\Twig\Node\Node([new \RS_Vendor\Twig\Node\PrintNode($this->parser->getExpressionParser()->parseExpression(), $lineno)]);
        }
        $stream->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        $block->setNode('body', $body);
        $this->parser->popBlockStack();
        $this->parser->popLocalScope();
        return new \RS_Vendor\Twig\Node\BlockReferenceNode($name, $lineno, $this->getTag());
    }
    public function decideBlockEnd(\RS_Vendor\Twig\Token $token)
    {
        return $token->test('endblock');
    }
    public function getTag()
    {
        return 'block';
    }
}
\class_alias('RS_Vendor\\Twig\\TokenParser\\BlockTokenParser', 'RS_Vendor\\Twig_TokenParser_Block');
