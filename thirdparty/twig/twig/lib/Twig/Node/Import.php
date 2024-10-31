<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Node\ImportNode;
\class_exists('RS_Vendor\\Twig\\Node\\ImportNode');
@\trigger_error(\sprintf('Using the "Twig_Node_Import" class is deprecated since Twig version 2.7, use "Twig\\Node\\ImportNode" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Node\ImportNode" instead */
    class Twig_Node_Import extends \RS_Vendor\Twig\Node\ImportNode
    {
    }
}
