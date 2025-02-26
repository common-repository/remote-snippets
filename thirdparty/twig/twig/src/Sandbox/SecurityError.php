<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Sandbox;

use RS_Vendor\Twig\Error\Error;
/**
 * Exception thrown when a security error occurs at runtime.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class SecurityError extends \RS_Vendor\Twig\Error\Error
{
}
\class_alias('RS_Vendor\\Twig\\Sandbox\\SecurityError', 'RS_Vendor\\Twig_Sandbox_SecurityError');
