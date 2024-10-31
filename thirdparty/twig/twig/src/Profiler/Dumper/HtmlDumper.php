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
final class HtmlDumper extends \RS_Vendor\Twig\Profiler\Dumper\BaseDumper
{
    private static $colors = ['block' => '#dfd', 'macro' => '#ddf', 'template' => '#ffd', 'big' => '#d44'];
    public function dump(\RS_Vendor\Twig\Profiler\Profile $profile)
    {
        return '<pre>' . parent::dump($profile) . '</pre>';
    }
    protected function formatTemplate(\RS_Vendor\Twig\Profiler\Profile $profile, $prefix)
    {
        return \sprintf('%s└ <span style="background-color: %s">%s</span>', $prefix, self::$colors['template'], $profile->getTemplate());
    }
    protected function formatNonTemplate(\RS_Vendor\Twig\Profiler\Profile $profile, $prefix)
    {
        return \sprintf('%s└ %s::%s(<span style="background-color: %s">%s</span>)', $prefix, $profile->getTemplate(), $profile->getType(), isset(self::$colors[$profile->getType()]) ? self::$colors[$profile->getType()] : 'auto', $profile->getName());
    }
    protected function formatTime(\RS_Vendor\Twig\Profiler\Profile $profile, $percent)
    {
        return \sprintf('<span style="color: %s">%.2fms/%.0f%%</span>', $percent > 20 ? self::$colors['big'] : 'auto', $profile->getDuration() * 1000, $percent);
    }
}
\class_alias('RS_Vendor\\Twig\\Profiler\\Dumper\\HtmlDumper', 'RS_Vendor\\Twig_Profiler_Dumper_Html');
