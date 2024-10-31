<?php

namespace RS_Vendor;

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use RS_Vendor\Twig\TwigTest;
/*
 * For Twig 1.x compatibility.
 */
\class_exists(\RS_Vendor\Twig\TwigTest::class);
@\trigger_error(\sprintf('Using the "Twig_SimpleTest" class is deprecated since Twig version 2.7, use "Twig\\TwigTest" instead.'), \E_USER_DEPRECATED);
if (\false) {
    /** @deprecated since Twig 2.7, use "Twig\TwigTest" instead */
    final class Twig_SimpleTest extends \RS_Vendor\Twig\TwigTest
    {
    }
}
