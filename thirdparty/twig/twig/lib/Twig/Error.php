<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Error\Error;
\class_exists('RS_Vendor\\Twig\\Error\\Error');
@\trigger_error(\sprintf('Using the "Twig_Error" class is deprecated since Twig version 2.7, use "Twig\\Error\\Error" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Error\Error" instead */
    class Twig_Error extends \RS_Vendor\Twig\Error\Error
    {
    }
}
