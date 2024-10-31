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
use RS_Vendor\Twig\Node\IfNode;
use RS_Vendor\Twig\Node\Node;
use RS_Vendor\Twig\Token;
/**
 * Tests a condition.
 *
 *   {% if users %}
 *    <ul>
 *      {% for user in users %}
 *        <li>{{ user.username|e }}</li>
 *      {% endfor %}
 *    </ul>
 *   {% endif %}
 */
final class IfTokenParser extends \RS_Vendor\Twig\TokenParser\AbstractTokenParser
{
    public function parse(\RS_Vendor\Twig\Token $token)
    {
        $lineno = $token->getLine();
        $expr = $this->parser->getExpressionParser()->parseExpression();
        $stream = $this->parser->getStream();
        $stream->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        $body = $this->parser->subparse([$this, 'decideIfFork']);
        $tests = [$expr, $body];
        $else = null;
        $end = \false;
        while (!$end) {
            switch ($stream->next()->getValue()) {
                case 'else':
                    $stream->expect(
                        /* Token::BLOCK_END_TYPE */
                        3
                    );
                    $else = $this->parser->subparse([$this, 'decideIfEnd']);
                    break;
                case 'elseif':
                    $expr = $this->parser->getExpressionParser()->parseExpression();
                    $stream->expect(
                        /* Token::BLOCK_END_TYPE */
                        3
                    );
                    $body = $this->parser->subparse([$this, 'decideIfFork']);
                    $tests[] = $expr;
                    $tests[] = $body;
                    break;
                case 'endif':
                    $end = \true;
                    break;
                default:
                    throw new \RS_Vendor\Twig\Error\SyntaxError(\sprintf('Unexpected end of template. Twig was looking for the following tags "else", "elseif", or "endif" to close the "if" block started at line %d).', $lineno), $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }
        }
        $stream->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        return new \RS_Vendor\Twig\Node\IfNode(new \RS_Vendor\Twig\Node\Node($tests), $else, $lineno, $this->getTag());
    }
    public function decideIfFork(\RS_Vendor\Twig\Token $token)
    {
        return $token->test(['elseif', 'else', 'endif']);
    }
    public function decideIfEnd(\RS_Vendor\Twig\Token $token)
    {
        return $token->test(['endif']);
    }
    public function getTag()
    {
        return 'if';
    }
}
\class_alias('RS_Vendor\\Twig\\TokenParser\\IfTokenParser', 'RS_Vendor\\Twig_TokenParser_If');
