<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Loader;

/**
 * Empty interface for Twig 1.x compatibility.
 */
interface SourceContextLoaderInterface extends \RS_Vendor\Twig\Loader\LoaderInterface
{
}
\class_alias('RS_Vendor\\Twig\\Loader\\SourceContextLoaderInterface', 'RS_Vendor\\Twig_SourceContextLoaderInterface');
