<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Node\CheckSecurityNode;
\class_exists('RS_Vendor\\Twig\\Node\\CheckSecurityNode');
@\trigger_error(\sprintf('Using the "Twig_Node_CheckSecurity" class is deprecated since Twig version 2.7, use "Twig\\Node\\CheckSecurityNode" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Node\CheckSecurityNode" instead */
    class Twig_Node_CheckSecurity extends \RS_Vendor\Twig\Node\CheckSecurityNode
    {
    }
}
