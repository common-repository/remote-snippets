<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Node\EmbedNode;
\class_exists('RS_Vendor\\Twig\\Node\\EmbedNode');
@\trigger_error(\sprintf('Using the "Twig_Node_Embed" class is deprecated since Twig version 2.7, use "Twig\\Node\\EmbedNode" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Node\EmbedNode" instead */
    class Twig_Node_Embed extends \RS_Vendor\Twig\Node\EmbedNode
    {
    }
}
