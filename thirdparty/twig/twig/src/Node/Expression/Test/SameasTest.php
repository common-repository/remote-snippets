<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Node\Expression\Test;

use RS_Vendor\Twig\Compiler;
use RS_Vendor\Twig\Node\Expression\TestExpression;
/**
 * Checks if a variable is the same as another one (=== in PHP).
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class SameasTest extends \RS_Vendor\Twig\Node\Expression\TestExpression
{
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->raw('(')->subcompile($this->getNode('node'))->raw(' === ')->subcompile($this->getNode('arguments')->getNode(0))->raw(')');
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\Test\\SameasTest', 'RS_Vendor\\Twig_Node_Expression_Test_Sameas');
