<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Profiler\Dumper;

use RS_Vendor\Twig\Profiler\Profile;
/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class TextDumper extends \RS_Vendor\Twig\Profiler\Dumper\BaseDumper
{
    protected function formatTemplate(\RS_Vendor\Twig\Profiler\Profile $profile, $prefix)
    {
        return \sprintf('%s└ %s', $prefix, $profile->getTemplate());
    }
    protected function formatNonTemplate(\RS_Vendor\Twig\Profiler\Profile $profile, $prefix)
    {
        return \sprintf('%s└ %s::%s(%s)', $prefix, $profile->getTemplate(), $profile->getType(), $profile->getName());
    }
    protected function formatTime(\RS_Vendor\Twig\Profiler\Profile $profile, $percent)
    {
        return \sprintf('%.2fms/%.0f%%', $profile->getDuration() * 1000, $percent);
    }
}
\class_alias('RS_Vendor\\Twig\\Profiler\\Dumper\\TextDumper', 'RS_Vendor\\Twig_Profiler_Dumper_Text');
