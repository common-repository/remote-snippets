<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Source;
\class_exists('RS_Vendor\\Twig\\Source');
@\trigger_error(\sprintf('Using the "Twig_Source" class is deprecated since Twig version 2.7, use "Twig\\Source" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Source" instead */
    class Twig_Source extends \RS_Vendor\Twig\Source
    {
    }
}
