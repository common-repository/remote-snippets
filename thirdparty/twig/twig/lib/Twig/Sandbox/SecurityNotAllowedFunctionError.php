<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Sandbox\SecurityNotAllowedFunctionError;
\class_exists('RS_Vendor\\Twig\\Sandbox\\SecurityNotAllowedFunctionError');
@\trigger_error(\sprintf('Using the "Twig_Sandbox_SecurityNotAllowedFunctionError" class is deprecated since Twig version 2.7, use "Twig\\Sandbox\\SecurityNotAllowedFunctionError" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Sandbox\SecurityNotAllowedFunctionError" instead */
    class Twig_Sandbox_SecurityNotAllowedFunctionError extends \RS_Vendor\Twig\Sandbox\SecurityNotAllowedFunctionError
    {
    }
}
