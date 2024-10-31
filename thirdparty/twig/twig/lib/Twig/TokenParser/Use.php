<?php

namespace RS_Vendor;

use RS_Vendor\Twig\TokenParser\UseTokenParser;
\class_exists('RS_Vendor\\Twig\\TokenParser\\UseTokenParser');
@\trigger_error(\sprintf('Using the "Twig_TokenParser_Use" class is deprecated since Twig version 2.7, use "Twig\\TokenParser\\UseTokenParser" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\TokenParser\UseTokenParser" instead */
    class Twig_TokenParser_Use extends \RS_Vendor\Twig\TokenParser\UseTokenParser
    {
    }
}
