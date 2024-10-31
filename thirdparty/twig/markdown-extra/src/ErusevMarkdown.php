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

use RS_Vendor\Parsedown;
class ErusevMarkdown implements \RS_Vendor\Twig\Extra\Markdown\MarkdownInterface
{
    private $converter;
    public function __construct(\RS_Vendor\Parsedown $converter = null)
    {
        $this->converter = $converter ?: new \RS_Vendor\Parsedown();
    }
    public function convert(string $body) : string
    {
        return $this->converter->text($body);
    }
}
