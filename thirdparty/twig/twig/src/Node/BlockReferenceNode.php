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
 * Represents a block call node.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class BlockReferenceNode extends \RS_Vendor\Twig\Node\Node implements \RS_Vendor\Twig\Node\NodeOutputInterface
{
    public function __construct(string $name, int $lineno, string $tag = null)
    {
        parent::__construct([], ['name' => $name], $lineno, $tag);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->addDebugInfo($this)->write(\sprintf("\$this->displayBlock('%s', \$context, \$blocks);\n", $this->getAttribute('name')));
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\BlockReferenceNode', 'RS_Vendor\\Twig_Node_BlockReference');
