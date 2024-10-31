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
use RS_Vendor\Twig\Node\Expression\AssignNameExpression;
use RS_Vendor\Twig\Node\Expression\ConstantExpression;
use RS_Vendor\Twig\Node\Expression\GetAttrExpression;
use RS_Vendor\Twig\Node\Expression\NameExpression;
use RS_Vendor\Twig\Node\ForNode;
use RS_Vendor\Twig\Node\Node;
use RS_Vendor\Twig\Token;
use RS_Vendor\Twig\TokenStream;
/**
 * Loops over each item of a sequence.
 *
 *   <ul>
 *    {% for user in users %}
 *      <li>{{ user.username|e }}</li>
 *    {% endfor %}
 *   </ul>
 */
final class ForTokenParser extends \RS_Vendor\Twig\TokenParser\AbstractTokenParser
{
    public function parse(\RS_Vendor\Twig\Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $targets = $this->parser->getExpressionParser()->parseAssignmentExpression();
        $stream->expect(
            /* Token::OPERATOR_TYPE */
            8,
            'in'
        );
        $seq = $this->parser->getExpressionParser()->parseExpression();
        $ifexpr = null;
        if ($stream->nextIf(
            /* Token::NAME_TYPE */
            5,
            'if'
        )) {
            @\trigger_error(\sprintf('Using an "if" condition on "for" tag in "%s" at line %d is deprecated since Twig 2.10.0, use a "filter" filter or an "if" condition inside the "for" body instead (if your condition depends on a variable updated inside the loop).', $stream->getSourceContext()->getName(), $lineno), \E_USER_DEPRECATED);
            $ifexpr = $this->parser->getExpressionParser()->parseExpression();
        }
        $stream->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        $body = $this->parser->subparse([$this, 'decideForFork']);
        if ('else' == $stream->next()->getValue()) {
            $stream->expect(
                /* Token::BLOCK_END_TYPE */
                3
            );
            $else = $this->parser->subparse([$this, 'decideForEnd'], \true);
        } else {
            $else = null;
        }
        $stream->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        if (\count($targets) > 1) {
            $keyTarget = $targets->getNode(0);
            $keyTarget = new \RS_Vendor\Twig\Node\Expression\AssignNameExpression($keyTarget->getAttribute('name'), $keyTarget->getTemplateLine());
            $valueTarget = $targets->getNode(1);
            $valueTarget = new \RS_Vendor\Twig\Node\Expression\AssignNameExpression($valueTarget->getAttribute('name'), $valueTarget->getTemplateLine());
        } else {
            $keyTarget = new \RS_Vendor\Twig\Node\Expression\AssignNameExpression('_key', $lineno);
            $valueTarget = $targets->getNode(0);
            $valueTarget = new \RS_Vendor\Twig\Node\Expression\AssignNameExpression($valueTarget->getAttribute('name'), $valueTarget->getTemplateLine());
        }
        if ($ifexpr) {
            $this->checkLoopUsageCondition($stream, $ifexpr);
            $this->checkLoopUsageBody($stream, $body);
        }
        return new \RS_Vendor\Twig\Node\ForNode($keyTarget, $valueTarget, $seq, $ifexpr, $body, $else, $lineno, $this->getTag());
    }
    public function decideForFork(\RS_Vendor\Twig\Token $token)
    {
        return $token->test(['else', 'endfor']);
    }
    public function decideForEnd(\RS_Vendor\Twig\Token $token)
    {
        return $token->test('endfor');
    }
    // the loop variable cannot be used in the condition
    private function checkLoopUsageCondition(\RS_Vendor\Twig\TokenStream $stream, \RS_Vendor\Twig\Node\Node $node)
    {
        if ($node instanceof \RS_Vendor\Twig\Node\Expression\GetAttrExpression && $node->getNode('node') instanceof \RS_Vendor\Twig\Node\Expression\NameExpression && 'loop' == $node->getNode('node')->getAttribute('name')) {
            throw new \RS_Vendor\Twig\Error\SyntaxError('The "loop" variable cannot be used in a looping condition.', $node->getTemplateLine(), $stream->getSourceContext());
        }
        foreach ($node as $n) {
            if (!$n) {
                continue;
            }
            $this->checkLoopUsageCondition($stream, $n);
        }
    }
    // check usage of non-defined loop-items
    // it does not catch all problems (for instance when a for is included into another or when the variable is used in an include)
    private function checkLoopUsageBody(\RS_Vendor\Twig\TokenStream $stream, \RS_Vendor\Twig\Node\Node $node)
    {
        if ($node instanceof \RS_Vendor\Twig\Node\Expression\GetAttrExpression && $node->getNode('node') instanceof \RS_Vendor\Twig\Node\Expression\NameExpression && 'loop' == $node->getNode('node')->getAttribute('name')) {
            $attribute = $node->getNode('attribute');
            if ($attribute instanceof \RS_Vendor\Twig\Node\Expression\ConstantExpression && \in_array($attribute->getAttribute('value'), ['length', 'revindex0', 'revindex', 'last'])) {
                throw new \RS_Vendor\Twig\Error\SyntaxError(\sprintf('The "loop.%s" variable is not defined when looping with a condition.', $attribute->getAttribute('value')), $node->getTemplateLine(), $stream->getSourceContext());
            }
        }
        // should check for parent.loop.XXX usage
        if ($node instanceof \RS_Vendor\Twig\Node\ForNode) {
            return;
        }
        foreach ($node as $n) {
            if (!$n) {
                continue;
            }
            $this->checkLoopUsageBody($stream, $n);
        }
    }
    public function getTag()
    {
        return 'for';
    }
}
\class_alias('RS_Vendor\\Twig\\TokenParser\\ForTokenParser', 'RS_Vendor\\Twig_TokenParser_For');
