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

use RS_Vendor\Michelf\MarkdownExtra;
class MichelfMarkdown implements \RS_Vendor\Twig\Extra\Markdown\MarkdownInterface
{
    private $converter;
    public function __construct(\RS_Vendor\Michelf\MarkdownExtra $converter = null)
    {
        if (null === $converter) {
            $converter = new \RS_Vendor\Michelf\MarkdownExtra();
            $converter->hard_wrap = \true;
        }
        $this->converter = $converter;
    }
    public function convert(string $body) : string
    {
        return $this->converter->defaultTransform($body);
    }
}
