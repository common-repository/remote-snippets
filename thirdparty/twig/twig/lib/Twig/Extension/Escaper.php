<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Extension\EscaperExtension;
\class_exists('RS_Vendor\\Twig\\Extension\\EscaperExtension');
@\trigger_error(\sprintf('Using the "Twig_Extension_Escaper" class is deprecated since Twig version 2.7, use "Twig\\Extension\\EscaperExtension" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Extension\EscaperExtension" instead */
    class Twig_Extension_Escaper extends \RS_Vendor\Twig\Extension\EscaperExtension
    {
    }
}
