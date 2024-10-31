<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Util\TemplateDirIterator;
\class_exists('RS_Vendor\\Twig\\Util\\TemplateDirIterator');
@\trigger_error(\sprintf('Using the "Twig_Util_TemplateDirIterator" class is deprecated since Twig version 2.7, use "Twig\\Util\\TemplateDirIterator" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Util\TemplateDirIterator" instead */
    class Twig_Util_TemplateDirIterator extends \RS_Vendor\Twig\Util\TemplateDirIterator
    {
    }
}
