<?php

namespace RS_Vendor;

use RS_Vendor\Twig\TokenParser\AbstractTokenParser;
\class_exists('RS_Vendor\\Twig\\TokenParser\\AbstractTokenParser');
@\trigger_error(\sprintf('Using the "Twig_TokenParser" class is deprecated since Twig version 2.7, use "Twig\\TokenParser\\AbstractTokenParser" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\TokenParser\AbstractTokenParser" instead */
    class Twig_TokenParser extends \RS_Vendor\Twig\TokenParser\AbstractTokenParser
    {
    }
}
