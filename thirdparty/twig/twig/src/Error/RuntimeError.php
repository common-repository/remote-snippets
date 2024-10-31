<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 * (c) Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Error;

/**
 * Exception thrown when an error occurs at runtime.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class RuntimeError extends \RS_Vendor\Twig\Error\Error
{
}
\class_alias('RS_Vendor\\Twig\\Error\\RuntimeError', 'RS_Vendor\\Twig_Error_Runtime');
