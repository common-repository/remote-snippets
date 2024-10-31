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
use RS_Vendor\Twig\Node\Node;
use RS_Vendor\Twig\Parser;
use RS_Vendor\Twig\Token;
/**
 * Interface implemented by token parsers.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface TokenParserInterface
{
    /**
     * Sets the parser associated with this token parser.
     */
    public function setParser(\RS_Vendor\Twig\Parser $parser);
    /**
     * Parses a token and returns a node.
     *
     * @return Node
     *
     * @throws SyntaxError
     */
    public function parse(\RS_Vendor\Twig\Token $token);
    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag();
}
\class_alias('RS_Vendor\\Twig\\TokenParser\\TokenParserInterface', 'RS_Vendor\\Twig_TokenParserInterface');
// Ensure that the aliased name is loaded to keep BC for classes implementing the typehint with the old aliased name.
\class_exists('RS_Vendor\\Twig\\Token');
\class_exists('RS_Vendor\\Twig\\Parser');
