<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Node\ModuleNode;
\class_exists('RS_Vendor\\Twig\\Node\\ModuleNode');
@\trigger_error(\sprintf('Using the "Twig_Node_Module" class is deprecated since Twig version 2.7, use "Twig\\Node\\ModuleNode" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Node\ModuleNode" instead */
    class Twig_Node_Module extends \RS_Vendor\Twig\Node\ModuleNode
    {
    }
}
