<?php

namespace RS_Vendor;

use RS_Vendor\Twig\RuntimeLoader\ContainerRuntimeLoader;
\class_exists('RS_Vendor\\Twig\\RuntimeLoader\\ContainerRuntimeLoader');
@\trigger_error(\sprintf('Using the "Twig_ContainerRuntimeLoader" class is deprecated since Twig version 2.7, use "Twig\\RuntimeLoader\\ContainerRuntimeLoader" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\RuntimeLoader\ContainerRuntimeLoader" instead */
    class Twig_ContainerRuntimeLoader extends \RS_Vendor\Twig\RuntimeLoader\ContainerRuntimeLoader
    {
    }
}
