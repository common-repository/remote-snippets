<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Node\Expression\GetAttrExpression;
\class_exists('RS_Vendor\\Twig\\Node\\Expression\\GetAttrExpression');
@\trigger_error(\sprintf('Using the "Twig_Node_Expression_GetAttr" class is deprecated since Twig version 2.7, use "Twig\\Node\\Expression\\GetAttrExpression" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Node\Expression\GetAttrExpression" instead */
    class Twig_Node_Expression_GetAttr extends \RS_Vendor\Twig\Node\Expression\GetAttrExpression
    {
    }
}
