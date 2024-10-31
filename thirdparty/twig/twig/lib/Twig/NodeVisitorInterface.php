<?php

namespace RS_Vendor;

use RS_Vendor\Twig\NodeVisitor\NodeVisitorInterface;
\class_exists('RS_Vendor\\Twig\\NodeVisitor\\NodeVisitorInterface');
@\trigger_error(\sprintf('Using the "Twig_NodeVisitorInterface" class is deprecated since Twig version 2.7, use "Twig\\NodeVisitor\\NodeVisitorInterface" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\NodeVisitor\NodeVisitorInterface" instead */
    class Twig_NodeVisitorInterface extends \RS_Vendor\Twig\NodeVisitor\NodeVisitorInterface
    {
    }
}
