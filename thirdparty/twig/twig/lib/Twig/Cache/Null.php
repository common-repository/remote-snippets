<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Cache\NullCache;
\class_exists('RS_Vendor\\Twig\\Cache\\NullCache');
@\trigger_error(\sprintf('Using the "Twig_Cache_Null" class is deprecated since Twig version 2.7, use "Twig\\Cache\\NullCache" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Cache\NullCache" instead */
    class Twig_Cache_Null extends \RS_Vendor\Twig\Cache\NullCache
    {
    }
}
