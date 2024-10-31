<?php

namespace RS_Vendor;

use RS_Vendor\Twig\TokenParser\BlockTokenParser;
\class_exists('RS_Vendor\\Twig\\TokenParser\\BlockTokenParser');
@\trigger_error(\sprintf('Using the "Twig_TokenParser_Block" class is deprecated since Twig version 2.7, use "Twig\\TokenParser\\BlockTokenParser" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\TokenParser\BlockTokenParser" instead */
    class Twig_TokenParser_Block extends \RS_Vendor\Twig\TokenParser\BlockTokenParser
    {
    }
}
