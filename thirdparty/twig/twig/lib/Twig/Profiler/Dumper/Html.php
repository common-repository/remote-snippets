<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Profiler\Dumper\HtmlDumper;
\class_exists('RS_Vendor\\Twig\\Profiler\\Dumper\\HtmlDumper');
@\trigger_error(\sprintf('Using the "Twig_Profiler_Dumper_Html" class is deprecated since Twig version 2.7, use "Twig\\Profiler\\Dumper\\HtmlDumper" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Profiler\Dumper\HtmlDumper" instead */
    class Twig_Profiler_Dumper_Html extends \RS_Vendor\Twig\Profiler\Dumper\HtmlDumper
    {
    }
}
