<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Loader\SourceContextLoaderInterface;
\class_exists('RS_Vendor\\Twig\\Loader\\SourceContextLoaderInterface');
@\trigger_error(\sprintf('Using the "Twig_SourceContextLoaderInterface" class is deprecated since Twig version 2.7, use "Twig\\Loader\\SourceContextLoaderInterface" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Loader\SourceContextLoaderInterface" instead */
    class Twig_SourceContextLoaderInterface extends \RS_Vendor\Twig\Loader\SourceContextLoaderInterface
    {
    }
}
