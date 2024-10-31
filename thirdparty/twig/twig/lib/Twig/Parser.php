<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Parser;
\class_exists('RS_Vendor\\Twig\\Parser');
@\trigger_error(\sprintf('Using the "Twig_Parser" class is deprecated since Twig version 2.7, use "Twig\\Parser" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Parser" instead */
    class Twig_Parser extends \RS_Vendor\Twig\Parser
    {
    }
}
