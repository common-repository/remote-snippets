<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Node\MacroNode;
\class_exists('RS_Vendor\\Twig\\Node\\MacroNode');
@\trigger_error(\sprintf('Using the "Twig_Node_Macro" class is deprecated since Twig version 2.7, use "Twig\\Node\\MacroNode" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Node\MacroNode" instead */
    class Twig_Node_Macro extends \RS_Vendor\Twig\Node\MacroNode
    {
    }
}
