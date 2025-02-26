<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Extra\Markdown;

use RS_Vendor\League\HTMLToMarkdown\HtmlConverter;
use RS_Vendor\Twig\Extension\AbstractExtension;
use RS_Vendor\Twig\TwigFilter;
final class MarkdownExtension extends \RS_Vendor\Twig\Extension\AbstractExtension
{
    public function getFilters()
    {
        return [new \RS_Vendor\Twig\TwigFilter('markdown_to_html', ['RS_Vendor\\Twig\\Extra\\Markdown\\MarkdownRuntime', 'convert'], ['is_safe' => ['all']]), new \RS_Vendor\Twig\TwigFilter('html_to_markdown', 'RS_Vendor\\Twig\\Extra\\Markdown\\twig_html_to_markdown', ['is_safe' => ['all']])];
    }
}
function twig_html_to_markdown(string $body, array $options = []) : string
{
    static $converters;
    if (!\class_exists(\RS_Vendor\League\HTMLToMarkdown\HtmlConverter::class)) {
        throw new \LogicException('You cannot use the "html_to_markdown" filter as league/html-to-markdown is not installed; try running "composer require league/html-to-markdown".');
    }
    $options = $options + ['hard_break' => \true, 'strip_tags' => \true, 'remove_nodes' => 'head style'];
    if (!isset($converters[$key = \serialize($options)])) {
        $converters[$key] = new \RS_Vendor\League\HTMLToMarkdown\HtmlConverter($options);
    }
    return $converters[$key]->convert($body);
}
