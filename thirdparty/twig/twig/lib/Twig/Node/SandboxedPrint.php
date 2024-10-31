<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Node\SandboxedPrintNode;
\class_exists('RS_Vendor\\Twig\\Node\\SandboxedPrintNode');
@\trigger_error(\sprintf('Using the "Twig_Node_SandboxedPrint" class is deprecated since Twig version 2.7, use "Twig\\Node\\SandboxedPrintNode" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Node\SandboxedPrintNode" instead */
    class Twig_Node_SandboxedPrint extends \RS_Vendor\Twig\Node\SandboxedPrintNode
    {
    }
}
