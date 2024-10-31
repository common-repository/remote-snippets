<?php

namespace RS_Vendor;

use RS_Vendor\Twig\NodeVisitor\SandboxNodeVisitor;
\class_exists('RS_Vendor\\Twig\\NodeVisitor\\SandboxNodeVisitor');
@\trigger_error(\sprintf('Using the "Twig_NodeVisitor_Sandbox" class is deprecated since Twig version 2.7, use "Twig\\NodeVisitor\\SandboxNodeVisitor" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\NodeVisitor\SandboxNodeVisitor" instead */
    class Twig_NodeVisitor_Sandbox extends \RS_Vendor\Twig\NodeVisitor\SandboxNodeVisitor
    {
    }
}
