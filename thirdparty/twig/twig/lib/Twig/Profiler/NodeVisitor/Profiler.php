<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Profiler\NodeVisitor\ProfilerNodeVisitor;
\class_exists('RS_Vendor\\Twig\\Profiler\\NodeVisitor\\ProfilerNodeVisitor');
@\trigger_error(\sprintf('Using the "Twig_Profiler_NodeVisitor_Profiler" class is deprecated since Twig version 2.7, use "Twig\\Profiler\\NodeVisitor\\ProfilerNodeVisitor" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Profiler\NodeVisitor\ProfilerNodeVisitor" instead */
    class Twig_Profiler_NodeVisitor_Profiler extends \RS_Vendor\Twig\Profiler\NodeVisitor\ProfilerNodeVisitor
    {
    }
}
