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

use RS_Vendor\Twig\Node\DoNode;
use RS_Vendor\Twig\Token;
/**
 * Evaluates an expression, discarding the returned value.
 */
final class DoTokenParser extends \RS_Vendor\Twig\TokenParser\AbstractTokenParser
{
    public function parse(\RS_Vendor\Twig\Token $token)
    {
        $expr = $this->parser->getExpressionParser()->parseExpression();
        $this->parser->getStream()->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        return new \RS_Vendor\Twig\Node\DoNode($expr, $token->getLine(), $this->getTag());
    }
    public function getTag()
    {
        return 'do';
    }
}
\class_alias('RS_Vendor\\Twig\\TokenParser\\DoTokenParser', 'RS_Vendor\\Twig_TokenParser_Do');
