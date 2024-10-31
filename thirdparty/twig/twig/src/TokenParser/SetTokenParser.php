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
use RS_Vendor\Twig\Node\SetNode;
use RS_Vendor\Twig\Token;
/**
 * Defines a variable.
 *
 *  {% set foo = 'foo' %}
 *  {% set foo = [1, 2] %}
 *  {% set foo = {'foo': 'bar'} %}
 *  {% set foo = 'foo' ~ 'bar' %}
 *  {% set foo, bar = 'foo', 'bar' %}
 *  {% set foo %}Some content{% endset %}
 */
final class SetTokenParser extends \RS_Vendor\Twig\TokenParser\AbstractTokenParser
{
    public function parse(\RS_Vendor\Twig\Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $names = $this->parser->getExpressionParser()->parseAssignmentExpression();
        $capture = \false;
        if ($stream->nextIf(
            /* Token::OPERATOR_TYPE */
            8,
            '='
        )) {
            $values = $this->parser->getExpressionParser()->parseMultitargetExpression();
            $stream->expect(
                /* Token::BLOCK_END_TYPE */
                3
            );
            if (\count($names) !== \count($values)) {
                throw new \RS_Vendor\Twig\Error\SyntaxError('When using set, you must have the same number of variables and assignments.', $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }
        } else {
            $capture = \true;
            if (\count($names) > 1) {
                throw new \RS_Vendor\Twig\Error\SyntaxError('When using set with a block, you cannot have a multi-target.', $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }
            $stream->expect(
                /* Token::BLOCK_END_TYPE */
                3
            );
            $values = $this->parser->subparse([$this, 'decideBlockEnd'], \true);
            $stream->expect(
                /* Token::BLOCK_END_TYPE */
                3
            );
        }
        return new \RS_Vendor\Twig\Node\SetNode($capture, $names, $values, $lineno, $this->getTag());
    }
    public function decideBlockEnd(\RS_Vendor\Twig\Token $token)
    {
        return $token->test('endset');
    }
    public function getTag()
    {
        return 'set';
    }
}
\class_alias('RS_Vendor\\Twig\\TokenParser\\SetTokenParser', 'RS_Vendor\\Twig_TokenParser_Set');
