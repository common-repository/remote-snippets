<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Environment;
\class_exists('RS_Vendor\\Twig\\Environment');
@\trigger_error(\sprintf('Using the "Twig_Environment" class is deprecated since Twig version 2.7, use "Twig\\Environment" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Environment" instead */
    class Twig_Environment extends \RS_Vendor\Twig\Environment
    {
    }
}
