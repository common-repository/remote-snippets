<?php

namespace RS_Vendor;

use RS_Vendor\Twig\Extension\OptimizerExtension;
\class_exists('RS_Vendor\\Twig\\Extension\\OptimizerExtension');
@\trigger_error(\sprintf('Using the "Twig_Extension_Optimizer" class is deprecated since Twig version 2.7, use "Twig\\Extension\\OptimizerExtension" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\Extension\OptimizerExtension" instead */
    class Twig_Extension_Optimizer extends \RS_Vendor\Twig\Extension\OptimizerExtension
    {
    }
}
