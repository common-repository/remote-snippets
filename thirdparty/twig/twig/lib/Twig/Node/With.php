<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Node\WithNode;
\class_exists('RS_Vendor\\Twig\\Node\\WithNode');
@\trigger_error(\sprintf('Using the "Twig_Node_With" class is deprecated since Twig version 2.7, use "Twig\\Node\\WithNode" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Node\WithNode" instead */
    class Twig_Node_With extends \RS_Vendor\Twig\Node\WithNode
    {
    }
}
