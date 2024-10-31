<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Compiler;
\class_exists('RS_Vendor\\Twig\\Compiler');
@\trigger_error(\sprintf('Using the "Twig_Compiler" class is deprecated since Twig version 2.7, use "Twig\\Compiler" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Compiler" instead */
    class Twig_Compiler extends \RS_Vendor\Twig\Compiler
    {
    }
}
