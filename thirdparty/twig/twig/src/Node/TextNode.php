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
namespace RS_Vendor\Twig\Node;

use RS_Vendor\Twig\Compiler;
/**
 * Represents a text node.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class TextNode extends \RS_Vendor\Twig\Node\Node implements \RS_Vendor\Twig\Node\NodeOutputInterface
{
    public function __construct(string $data, int $lineno)
    {
        parent::__construct([], ['data' => $data], $lineno);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->addDebugInfo($this)->write('echo ')->string($this->getAttribute('data'))->raw(";\n");
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\TextNode', 'RS_Vendor\\Twig_Node_Text');
