<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Loader\FilesystemLoader;
\class_exists('RS_Vendor\\Twig\\Loader\\FilesystemLoader');
@\trigger_error(\sprintf('Using the "Twig_Loader_Filesystem" class is deprecated since Twig version 2.7, use "Twig\\Loader\\FilesystemLoader" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Loader\FilesystemLoader" instead */
    class Twig_Loader_Filesystem extends \RS_Vendor\Twig\Loader\FilesystemLoader
    {
    }
}
