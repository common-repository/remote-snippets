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
final class DebugExtension extends \RS_Vendor\Twig\Extension\AbstractExtension
{
    public function getFunctions()
    {
        // dump is safe if var_dump is overridden by xdebug
        $isDumpOutputHtmlSafe = \extension_loaded('xdebug') && (\false === \ini_get('xdebug.overload_var_dump') || \ini_get('xdebug.overload_var_dump')) && (\false === \ini_get('html_errors') || \ini_get('html_errors')) || 'cli' === \PHP_SAPI;
        return [new \RS_Vendor\Twig\TwigFunction('dump', 'twig_var_dump', ['is_safe' => $isDumpOutputHtmlSafe ? ['html'] : [], 'needs_context' => \true, 'needs_environment' => \true, 'is_variadic' => \true])];
    }
}
\class_alias('RS_Vendor\\Twig\\Extension\\DebugExtension', 'RS_Vendor\\Twig_Extension_Debug');
namespace RS_Vendor;

use RS_Vendor\Twig\Environment;
use RS_Vendor\Twig\Template;
use RS_Vendor\Twig\TemplateWrapper;
function twig_var_dump(\RS_Vendor\Twig\Environment $env, $context, ...$vars)
{
    if (!$env->isDebug()) {
        return;
    }
    \ob_start();
    if (!$vars) {
        $vars = [];
        foreach ($context as $key => $value) {
            if (!$value instanceof \RS_Vendor\Twig\Template && !$value instanceof \RS_Vendor\Twig\TemplateWrapper) {
                $vars[$key] = $value;
            }
        }
        \var_dump($vars);
    } else {
        \var_dump(...$vars);
    }
    return \ob_get_clean();
}
