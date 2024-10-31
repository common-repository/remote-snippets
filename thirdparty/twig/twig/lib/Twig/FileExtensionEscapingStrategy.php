<?php

namespace RS_Vendor;

use RS_Vendor\Twig\FileExtensionEscapingStrategy;
\class_exists('RS_Vendor\\Twig\\FileExtensionEscapingStrategy');
@\trigger_error(\sprintf('Using the "Twig_FileExtensionEscapingStrategy" class is deprecated since Twig version 2.7, use "Twig\\FileExtensionEscapingStrategy" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\FileExtensionEscapingStrategy" instead */
    class Twig_FileExtensionEscapingStrategy extends \RS_Vendor\Twig\FileExtensionEscapingStrategy
    {
    }
}
