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

use RS_Vendor\Twig\Node\SpacelessNode;
use RS_Vendor\Twig\Token;
/**
 * Remove whitespaces between HTML tags.
 *
 *   {% spaceless %}
 *      <div>
 *          <strong>foo</strong>
 *      </div>
 *   {% endspaceless %}
 *   {# output will be <div><strong>foo</strong></div> #}
 *
 * @deprecated since Twig 2.7, to be removed in 3.0 (use the "spaceless" filter with the "apply" tag instead)
 */
final class SpacelessTokenParser extends \RS_Vendor\Twig\TokenParser\AbstractTokenParser
{
    public function parse(\RS_Vendor\Twig\Token $token)
    {
        $stream = $this->parser->getStream();
        $lineno = $token->getLine();
        @\trigger_error(\sprintf('The spaceless tag in "%s" at line %d is deprecated since Twig 2.7, use the "spaceless" filter with the "apply" tag instead.', $stream->getSourceContext()->getName(), $lineno), \E_USER_DEPRECATED);
        $stream->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        $body = $this->parser->subparse([$this, 'decideSpacelessEnd'], \true);
        $stream->expect(
            /* Token::BLOCK_END_TYPE */
            3
        );
        return new \RS_Vendor\Twig\Node\SpacelessNode($body, $lineno, $this->getTag());
    }
    public function decideSpacelessEnd(\RS_Vendor\Twig\Token $token)
    {
        return $token->test('endspaceless');
    }
    public function getTag()
    {
        return 'spaceless';
    }
}
\class_alias('RS_Vendor\\Twig\\TokenParser\\SpacelessTokenParser', 'RS_Vendor\\Twig_TokenParser_Spaceless');
