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

use RS_Vendor\League\CommonMark\CommonMarkConverter;
use RS_Vendor\Michelf\MarkdownExtra;
use RS_Vendor\Parsedown;
class DefaultMarkdown implements \RS_Vendor\Twig\Extra\Markdown\MarkdownInterface
{
    private $converter;
    public function __construct()
    {
        if (\class_exists(\RS_Vendor\Parsedown::class)) {
            $this->converter = new \RS_Vendor\Twig\Extra\Markdown\ErusevMarkdown();
        } elseif (\class_exists(\RS_Vendor\League\CommonMark\CommonMarkConverter::class)) {
            $this->converter = new \RS_Vendor\Twig\Extra\Markdown\LeagueMarkdown();
        } elseif (\class_exists(\RS_Vendor\Michelf\MarkdownExtra::class)) {
            $this->converter = new \RS_Vendor\Twig\Extra\Markdown\MichelfMarkdown();
        } else {
            throw new \LogicException('You cannot use the "markdown_to_html" filter as no Markdown library is available; try running "composer require erusev/parsedown".');
        }
    }
    public function convert(string $body) : string
    {
        return $this->converter->convert($body);
    }
}
