<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\TokenParser;

use RS_Vendor\Twig\Node\EmbedNode;
use RS_Vendor\Twig\Node\Expression\ConstantExpression;
use RS_Vendor\Twig\Node\Expression\NameExpression;
use RS_Vendor\Twig\Token;
/**
 * Embeds a template.
 */
final class EmbedTokenParser extends \RS_Vendor\Twig\TokenParser\IncludeTokenParser
{
    public function parse(\RS_Vendor\Twig\Token $token)
    {
        $stream = $this->parser->getStream();
        $parent = $this->parser->getExpressionParser()->parseExpression();
        list($variables, $only, $ignoreMissing) = $this->parseArguments();
        $parentToken = $fakeParentToken = new \RS_Vendor\Twig\Token(
            /* Token::STRING_TYPE */
            7,
            '__parent__',
            $token->getLine()
        );
        if ($parent instanceof \RS_Vendor\Twig\Node\Expression\ConstantExpression) {
            $parentToken = new \RS_Vendor\Twig\Token(
                /* Token::STRING_TYPE */
                7,
                $parent->getAttribute('value'),
                $token->getLine()
            );
        } elseif ($parent instanceof \RS_Vendor\Twig\Node\Expression\NameExpression) {
            $parentToken = new \RS_Vendor\Twig\Token(
                /* Token::NAME_TYPE */
                5,
                $parent->getAttribute('name'),
                $token->getLine()
            );
        }
        // inject a fake parent to make the parent() function work
        $stream->injectTokens([new \RS_Vendor\Twig\Token(
            /* Token::BLOCK_START_TYPE */
            1,
            '',
            $token->getLine()
        ), new \RS_Vendor\Twig\Token(
            /* Token::NAME_TYPE */
            5,
            'extends',
            $token->getLine()
        ), $parentToken, new \RS_Vendor\Twig\Token(
            /* Token::BLOCK_END_TYPE */
            3,
            '',
            $token->getLine()
        )]);
        $module = $this->parser->parse($stream, [$this, 'decideBlockEnd'], \true);
        // override the parent with the correct one
        if ($fakeParentToken === $parentToken) {
            $module->setNode('parent', $parent);
        }
        $this->parser->embedTemplate($module);
        $stream->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        return new \RS_Vendor\Twig\Node\EmbedNode($module->getTemplateName(), $module->getAttribute('index'), $variables, $only, $ignoreMissing, $token->getLine(), $this->getTag());
    }
    public function decideBlockEnd(\RS_Vendor\Twig\Token $token)
    {
        return $token->test('endembed');
    }
    public function getTag()
    {
        return 'embed';
    }
}
\class_alias('RS_Vendor\\Twig\\TokenParser\\EmbedTokenParser', 'RS_Vendor\\Twig_TokenParser_Embed');
