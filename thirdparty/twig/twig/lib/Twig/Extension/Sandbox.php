<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Extension\SandboxExtension;
\class_exists('RS_Vendor\\Twig\\Extension\\SandboxExtension');
@\trigger_error(\sprintf('Using the "Twig_Extension_Sandbox" class is deprecated since Twig version 2.7, use "Twig\\Extension\\SandboxExtension" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Extension\SandboxExtension" instead */
    class Twig_Extension_Sandbox extends \RS_Vendor\Twig\Extension\SandboxExtension
    {
    }
}
