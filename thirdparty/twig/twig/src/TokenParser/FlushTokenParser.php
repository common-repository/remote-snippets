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

use RS_Vendor\Twig\Node\FlushNode;
use RS_Vendor\Twig\Token;
/**
 * Flushes the output to the client.
 *
 * @see flush()
 */
final class FlushTokenParser extends \RS_Vendor\Twig\TokenParser\AbstractTokenParser
{
    public function parse(\RS_Vendor\Twig\Token $token)
    {
        $this->parser->getStream()->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        return new \RS_Vendor\Twig\Node\FlushNode($token->getLine(), $this->getTag());
    }
    public function getTag()
    {
        return 'flush';
    }
}
\class_alias('RS_Vendor\\Twig\\TokenParser\\FlushTokenParser', 'RS_Vendor\\Twig_TokenParser_Flush');
