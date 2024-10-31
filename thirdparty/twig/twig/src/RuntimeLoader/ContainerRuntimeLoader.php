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

use RS_Vendor\Psr\Container\ContainerInterface;
/**
 * Lazily loads Twig runtime implementations from a PSR-11 container.
 *
 * Note that the runtime services MUST use their class names as identifiers.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class ContainerRuntimeLoader implements \RS_Vendor\Twig\RuntimeLoader\RuntimeLoaderInterface
{
    private $container;
    public function __construct(\RS_Vendor\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function load($class)
    {
        if ($this->container->has($class)) {
            return $this->container->get($class);
        }
    }
}
\class_alias('RS_Vendor\\Twig\\RuntimeLoader\\ContainerRuntimeLoader', 'RS_Vendor\\Twig_ContainerRuntimeLoader');
