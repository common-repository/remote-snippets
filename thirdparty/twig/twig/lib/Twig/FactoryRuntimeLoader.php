<?php

namespace RS_Vendor;

use RS_Vendor\Twig\RuntimeLoader\FactoryRuntimeLoader;
\class_exists('RS_Vendor\\Twig\\RuntimeLoader\\FactoryRuntimeLoader');
@\trigger_error(\sprintf('Using the "Twig_FactoryRuntimeLoader" class is deprecated since Twig version 2.7, use "Twig\\RuntimeLoader\\FactoryRuntimeLoader" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\RuntimeLoader\FactoryRuntimeLoader" instead */
    class Twig_FactoryRuntimeLoader extends \RS_Vendor\Twig\RuntimeLoader\FactoryRuntimeLoader
    {
    }
}
