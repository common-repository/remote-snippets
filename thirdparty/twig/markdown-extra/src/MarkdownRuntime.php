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

class MarkdownRuntime
{
    private $converter;
    public function __construct(\RS_Vendor\Twig\Extra\Markdown\MarkdownInterface $converter)
    {
        $this->converter = $converter;
    }
    public function convert(string $body) : string
    {
        // remove indentation
        if ($white = \substr($body, 0, \strspn($body, " \t\r\n\0\v"))) {
            $body = \preg_replace("{^{$white}}m", '', $body);
        }
        return $this->converter->convert($body);
    }
}
