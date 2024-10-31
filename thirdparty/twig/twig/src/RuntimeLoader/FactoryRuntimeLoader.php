<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\RuntimeLoader;

/**
 * Lazy loads the runtime implementations for a Twig element.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class FactoryRuntimeLoader implements \RS_Vendor\Twig\RuntimeLoader\RuntimeLoaderInterface
{
    private $map;
    /**
     * @param array $map An array where keys are class names and values factory callables
     */
    public function __construct(array $map = [])
    {
        $this->map = $map;
    }
    public function load($class)
    {
        if (isset($this->map[$class])) {
            $runtimeFactory = $this->map[$class];
            return $runtimeFactory();
        }
    }
}
\class_alias('RS_Vendor\\Twig\\RuntimeLoader\\FactoryRuntimeLoader', 'RS_Vendor\\Twig_FactoryRuntimeLoader');
