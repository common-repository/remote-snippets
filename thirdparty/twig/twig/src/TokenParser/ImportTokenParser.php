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

use RS_Vendor\Twig\Node\Expression\AssignNameExpression;
use RS_Vendor\Twig\Node\ImportNode;
use RS_Vendor\Twig\Token;
/**
 * Imports macros.
 *
 *   {% import 'forms.html' as forms %}
 */
final class ImportTokenParser extends \RS_Vendor\Twig\TokenParser\AbstractTokenParser
{
    public function parse(\RS_Vendor\Twig\Token $token)
    {
        $macro = $this->parser->getExpressionParser()->parseExpression();
        $this->parser->getStream()->expect(
            /* Token::NAME_TYPE */
            5,
            'as'
        );
        $var = new \RS_Vendor\Twig\Node\Expression\AssignNameExpression($this->parser->getStream()->expect(
            /* Token::NAME_TYPE */
            5
        )->getValue(), $token->getLine());
        $this->parser->getStream()->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        $this->parser->addImportedSymbol('template', $var->getAttribute('name'));
        return new \RS_Vendor\Twig\Node\ImportNode($macro, $var, $token->getLine(), $this->getTag(), $this->parser->isMainScope());
    }
    public function getTag()
    {
        return 'import';
    }
}
\class_alias('RS_Vendor\\Twig\\TokenParser\\ImportTokenParser', 'RS_Vendor\\Twig_TokenParser_Import');
