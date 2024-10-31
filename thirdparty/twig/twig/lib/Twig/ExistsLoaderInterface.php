<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Loader\ExistsLoaderInterface;
\class_exists('RS_Vendor\\Twig\\Loader\\ExistsLoaderInterface');
@\trigger_error(\sprintf('Using the "Twig_ExistsLoaderInterface" class is deprecated since Twig version 2.7, use "Twig\\Loader\\ExistsLoaderInterface" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Loader\ExistsLoaderInterface" instead */
    class Twig_ExistsLoaderInterface extends \RS_Vendor\Twig\Loader\ExistsLoaderInterface
    {
    }
}
