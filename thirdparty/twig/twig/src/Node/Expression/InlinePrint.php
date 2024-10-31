<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Node\Expression;

use RS_Vendor\Twig\Compiler;
use RS_Vendor\Twig\Node\Node;
/**
 * @internal
 */
final class InlinePrint extends \RS_Vendor\Twig\Node\Expression\AbstractExpression
{
    public function __construct(\RS_Vendor\Twig\Node\Node $node, $lineno)
    {
        parent::__construct(['node' => $node], [], $lineno);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->raw('print (')->subcompile($this->getNode('node'))->raw(')');
    }
}
