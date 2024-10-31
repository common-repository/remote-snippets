<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Extension;

use RS_Vendor\Twig\Profiler\NodeVisitor\ProfilerNodeVisitor;
use RS_Vendor\Twig\Profiler\Profile;
class ProfilerExtension extends \RS_Vendor\Twig\Extension\AbstractExtension
{
    private $actives = [];
    public function __construct(\RS_Vendor\Twig\Profiler\Profile $profile)
    {
        $this->actives[] = $profile;
    }
    public function enter(\RS_Vendor\Twig\Profiler\Profile $profile)
    {
        $this->actives[0]->addProfile($profile);
        \array_unshift($this->actives, $profile);
    }
    public function leave(\RS_Vendor\Twig\Profiler\Profile $profile)
    {
        $profile->leave();
        \array_shift($this->actives);
        if (1 === \count($this->actives)) {
            $this->actives[0]->leave();
        }
    }
    public function getNodeVisitors()
    {
        return [new \RS_Vendor\Twig\Profiler\NodeVisitor\ProfilerNodeVisitor(\get_class($this))];
    }
}
\class_alias('RS_Vendor\\Twig\\Extension\\ProfilerExtension', 'RS_Vendor\\Twig_Extension_Profiler');
