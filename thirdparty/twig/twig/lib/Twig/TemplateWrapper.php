<?php

namespace RS_Vendor;

use RS_Vendor\Twig\TemplateWrapper;
\class_exists('RS_Vendor\\Twig\\TemplateWrapper');
@\trigger_error(\sprintf('Using the "Twig_TemplateWrapper" class is deprecated since Twig version 2.7, use "Twig\\TemplateWrapper" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\TemplateWrapper" instead */
    class Twig_TemplateWrapper extends \RS_Vendor\Twig\TemplateWrapper
    {
    }
}
