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

use RS_Vendor\Twig\Error\SyntaxError;
use RS_Vendor\Twig\Node\AutoEscapeNode;
use RS_Vendor\Twig\Node\Expression\ConstantExpression;
use RS_Vendor\Twig\Token;
/**
 * Marks a section of a template to be escaped or not.
 */
final class AutoEscapeTokenParser extends \RS_Vendor\Twig\TokenParser\AbstractTokenParser
{
    public function parse(\RS_Vendor\Twig\Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        if ($stream->test(
            /* Token::BLOCK_END_TYPE */
            3
        )) {
            $value = 'html';
        } else {
            $expr = $this->parser->getExpressionParser()->parseExpression();
            if (!$expr instanceof \RS_Vendor\Twig\Node\Expression\ConstantExpression) {
                throw new \RS_Vendor\Twig\Error\SyntaxError('An escaping strategy must be a string or false.', $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }
            $value = $expr->getAttribute('value');
        }
        $stream->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        $body = $this->parser->subparse([$this, 'decideBlockEnd'], \true);
        $stream->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        return new \RS_Vendor\Twig\Node\AutoEscapeNode($value, $body, $lineno, $this->getTag());
    }
    public function decideBlockEnd(\RS_Vendor\Twig\Token $token)
    {
        return $token->test('endautoescape');
    }
    public function getTag()
    {
        return 'autoescape';
    }
}
\class_alias('RS_Vendor\\Twig\\TokenParser\\AutoEscapeTokenParser', 'RS_Vendor\\Twig_TokenParser_AutoEscape');
