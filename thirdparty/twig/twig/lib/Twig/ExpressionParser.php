<?php

namespace RS_Vendor;

use RS_Vendor\Twig\ExpressionParser;
\class_exists('RS_Vendor\\Twig\\ExpressionParser');
@\trigger_error(\sprintf('Using the "Twig_ExpressionParser" class is deprecated since Twig version 2.7, use "Twig\\ExpressionParser" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\ExpressionParser" instead */
    class Twig_ExpressionParser extends \RS_Vendor\Twig\ExpressionParser
    {
    }
}
