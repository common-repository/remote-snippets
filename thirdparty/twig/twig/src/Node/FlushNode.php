<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Node;

use RS_Vendor\Twig\Compiler;
/**
 * Represents a flush node.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class FlushNode extends \RS_Vendor\Twig\Node\Node
{
    public function __construct(int $lineno, string $tag)
    {
        parent::__construct([], [], $lineno, $tag);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->addDebugInfo($this)->write("flush();\n");
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\FlushNode', 'RS_Vendor\\Twig_Node_Flush');
