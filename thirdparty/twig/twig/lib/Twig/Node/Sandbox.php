<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Node\SandboxNode;
\class_exists('RS_Vendor\\Twig\\Node\\SandboxNode');
@\trigger_error(\sprintf('Using the "Twig_Node_Sandbox" class is deprecated since Twig version 2.7, use "Twig\\Node\\SandboxNode" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Node\SandboxNode" instead */
    class Twig_Node_Sandbox extends \RS_Vendor\Twig\Node\SandboxNode
    {
    }
}
