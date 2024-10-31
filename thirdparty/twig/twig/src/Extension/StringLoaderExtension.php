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

use RS_Vendor\Twig\TwigFunction;
final class StringLoaderExtension extends \RS_Vendor\Twig\Extension\AbstractExtension
{
    public function getFunctions()
    {
        return [new \RS_Vendor\Twig\TwigFunction('template_from_string', 'twig_template_from_string', ['needs_environment' => \true])];
    }
}
\class_alias('RS_Vendor\\Twig\\Extension\\StringLoaderExtension', 'RS_Vendor\\Twig_Extension_StringLoader');
namespace RS_Vendor;

use RS_Vendor\Twig\Environment;
use RS_Vendor\Twig\TemplateWrapper;
/**
 * Loads a template from a string.
 *
 *     {{ include(template_from_string("Hello {{ name }}")) }}
 *
 * @param string $template A template as a string or object implementing __toString()
 * @param string $name     An optional name of the template to be used in error messages
 *
 * @return TemplateWrapper
 */
function twig_template_from_string(\RS_Vendor\Twig\Environment $env, $template, string $name = null)
{
    return $env->createTemplate((string) $template, $name);
}
