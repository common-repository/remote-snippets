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

use RS_Vendor\Twig\Parser;
/**
 * Base class for all token parsers.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class AbstractTokenParser implements \RS_Vendor\Twig\TokenParser\TokenParserInterface
{
    /**
     * @var Parser
     */
    protected $parser;
    public function setParser(\RS_Vendor\Twig\Parser $parser)
    {
        $this->parser = $parser;
    }
}
\class_alias('RS_Vendor\\Twig\\TokenParser\\AbstractTokenParser', 'RS_Vendor\\Twig_TokenParser');
