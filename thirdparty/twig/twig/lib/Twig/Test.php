<?php

namespace RS_Vendor;

use RS_Vendor\Twig\TwigTest;
\class_exists('RS_Vendor\\Twig\\TwigTest');
@\trigger_error(\sprintf('Using the "Twig_Test" class is deprecated since Twig version 2.7, use "Twig\\TwigTest" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\TwigTest" instead */
    class Twig_Test extends \RS_Vendor\Twig\TwigTest
    {
    }
}
