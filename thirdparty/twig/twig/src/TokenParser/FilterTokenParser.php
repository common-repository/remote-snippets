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

use RS_Vendor\Twig\Node\BlockNode;
use RS_Vendor\Twig\Node\Expression\BlockReferenceExpression;
use RS_Vendor\Twig\Node\Expression\ConstantExpression;
use RS_Vendor\Twig\Node\PrintNode;
use RS_Vendor\Twig\Token;
/**
 * Filters a section of a template by applying filters.
 *
 *   {% filter upper %}
 *      This text becomes uppercase
 *   {% endfilter %}
 *
 * @deprecated since Twig 2.9, to be removed in 3.0 (use the "apply" tag instead)
 */
final class FilterTokenParser extends \RS_Vendor\Twig\TokenParser\AbstractTokenParser
{
    public function parse(\RS_Vendor\Twig\Token $token)
    {
        $stream = $this->parser->getStream();
        $lineno = $token->getLine();
        @\trigger_error(\sprintf('The "filter" tag in "%s" at line %d is deprecated since Twig 2.9, use the "apply" tag instead.', $stream->getSourceContext()->getName(), $lineno), \E_USER_DEPRECATED);
        $name = $this->parser->getVarName();
        $ref = new \RS_Vendor\Twig\Node\Expression\BlockReferenceExpression(new \RS_Vendor\Twig\Node\Expression\ConstantExpression($name, $lineno), null, $lineno, $this->getTag());
        $filter = $this->parser->getExpressionParser()->parseFilterExpressionRaw($ref, $this->getTag());
        $stream->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        $body = $this->parser->subparse([$this, 'decideBlockEnd'], \true);
        $stream->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        $block = new \RS_Vendor\Twig\Node\BlockNode($name, $body, $lineno);
        $this->parser->setBlock($name, $block);
        return new \RS_Vendor\Twig\Node\PrintNode($filter, $lineno, $this->getTag());
    }
    public function decideBlockEnd(\RS_Vendor\Twig\Token $token)
    {
        return $token->test('endfilter');
    }
    public function getTag()
    {
        return 'filter';
    }
}
\class_alias('RS_Vendor\\Twig\\TokenParser\\FilterTokenParser', 'RS_Vendor\\Twig_TokenParser_Filter');
