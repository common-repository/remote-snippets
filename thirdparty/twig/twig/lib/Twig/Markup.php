<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Markup;
\class_exists('RS_Vendor\\Twig\\Markup');
@\trigger_error(\sprintf('Using the "Twig_Markup" class is deprecated since Twig version 2.7, use "Twig\\Markup" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Markup" instead */
    class Twig_Markup extends \RS_Vendor\Twig\Markup
    {
    }
}
