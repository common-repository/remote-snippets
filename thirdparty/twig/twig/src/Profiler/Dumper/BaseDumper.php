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
abstract class BaseDumper
{
    private $root;
    public function dump(\RS_Vendor\Twig\Profiler\Profile $profile)
    {
        return $this->dumpProfile($profile);
    }
    protected abstract function formatTemplate(\RS_Vendor\Twig\Profiler\Profile $profile, $prefix);
    protected abstract function formatNonTemplate(\RS_Vendor\Twig\Profiler\Profile $profile, $prefix);
    protected abstract function formatTime(\RS_Vendor\Twig\Profiler\Profile $profile, $percent);
    private function dumpProfile(\RS_Vendor\Twig\Profiler\Profile $profile, $prefix = '', $sibling = \false) : string
    {
        if ($profile->isRoot()) {
            $this->root = $profile->getDuration();
            $start = $profile->getName();
        } else {
            if ($profile->isTemplate()) {
                $start = $this->formatTemplate($profile, $prefix);
            } else {
                $start = $this->formatNonTemplate($profile, $prefix);
            }
            $prefix .= $sibling ? '│ ' : '  ';
        }
        $percent = $this->root ? $profile->getDuration() / $this->root * 100 : 0;
        if ($profile->getDuration() * 1000 < 1) {
            $str = $start . "\n";
        } else {
            $str = \sprintf("%s %s\n", $start, $this->formatTime($profile, $percent));
        }
        $nCount = \count($profile->getProfiles());
        foreach ($profile as $i => $p) {
            $str .= $this->dumpProfile($p, $prefix, $i + 1 !== $nCount);
        }
        return $str;
    }
}
\class_alias('RS_Vendor\\Twig\\Profiler\\Dumper\\BaseDumper', 'RS_Vendor\\Twig_Profiler_Dumper_Base');
