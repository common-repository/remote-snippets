<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Sandbox\SecurityNotAllowedTagError;
\class_exists('RS_Vendor\\Twig\\Sandbox\\SecurityNotAllowedTagError');
@\trigger_error(\sprintf('Using the "Twig_Sandbox_SecurityNotAllowedTagError" class is deprecated since Twig version 2.7, use "Twig\\Sandbox\\SecurityNotAllowedTagError" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Sandbox\SecurityNotAllowedTagError" instead */
    class Twig_Sandbox_SecurityNotAllowedTagError extends \RS_Vendor\Twig\Sandbox\SecurityNotAllowedTagError
    {
    }
}
