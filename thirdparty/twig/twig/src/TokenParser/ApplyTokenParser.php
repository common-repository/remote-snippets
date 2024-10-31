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

use RS_Vendor\Twig\Node\Expression\TempNameExpression;
use RS_Vendor\Twig\Node\Node;
use RS_Vendor\Twig\Node\PrintNode;
use RS_Vendor\Twig\Node\SetNode;
use RS_Vendor\Twig\Token;
/**
 * Applies filters on a section of a template.
 *
 *   {% apply upper %}
 *      This text becomes uppercase
 *   {% endapplys %}
 */
final class ApplyTokenParser extends \RS_Vendor\Twig\TokenParser\AbstractTokenParser
{
    public function parse(\RS_Vendor\Twig\Token $token)
    {
        $lineno = $token->getLine();
        $name = $this->parser->getVarName();
        $ref = new \RS_Vendor\Twig\Node\Expression\TempNameExpression($name, $lineno);
        $ref->setAttribute('always_defined', \true);
        $filter = $this->parser->getExpressionParser()->parseFilterExpressionRaw($ref, $this->getTag());
        $this->parser->getStream()->expect(\RS_Vendor\Twig\Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideApplyEnd'], \true);
        $this->parser->getStream()->expect(\RS_Vendor\Twig\Token::BLOCK_END_TYPE);
        return new \RS_Vendor\Twig\Node\Node([new \RS_Vendor\Twig\Node\SetNode(\true, $ref, $body, $lineno, $this->getTag()), new \RS_Vendor\Twig\Node\PrintNode($filter, $lineno, $this->getTag())]);
    }
    public function decideApplyEnd(\RS_Vendor\Twig\Token $token)
    {
        return $token->test('endapply');
    }
    public function getTag()
    {
        return 'apply';
    }
}
