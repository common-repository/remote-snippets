<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Sandbox\SecurityPolicy;
\class_exists('RS_Vendor\\Twig\\Sandbox\\SecurityPolicy');
@\trigger_error(\sprintf('Using the "Twig_Sandbox_SecurityPolicy" class is deprecated since Twig version 2.7, use "Twig\\Sandbox\\SecurityPolicy" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Sandbox\SecurityPolicy" instead */
    class Twig_Sandbox_SecurityPolicy extends \RS_Vendor\Twig\Sandbox\SecurityPolicy
    {
    }
}
