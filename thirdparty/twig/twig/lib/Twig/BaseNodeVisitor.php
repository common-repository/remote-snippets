<?php

namespace RS_Vendor;

use RS_Vendor\Twig\NodeVisitor\AbstractNodeVisitor;
\class_exists('RS_Vendor\\Twig\\NodeVisitor\\AbstractNodeVisitor');
@\trigger_error(\sprintf('Using the "Twig_BaseNodeVisitor" class is deprecated since Twig version 2.7, use "Twig\\NodeVisitor\\AbstractNodeVisitor" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\NodeVisitor\AbstractNodeVisitor" instead */
    class Twig_BaseNodeVisitor extends \RS_Vendor\Twig\NodeVisitor\AbstractNodeVisitor
    {
    }
}
