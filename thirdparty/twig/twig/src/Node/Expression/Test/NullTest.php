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
 * Checks that a variable is null.
 *
 *  {{ var is none }}
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class NullTest extends \RS_Vendor\Twig\Node\Expression\TestExpression
{
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->raw('(null === ')->subcompile($this->getNode('node'))->raw(')');
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\Test\\NullTest', 'RS_Vendor\\Twig_Node_Expression_Test_Null');
