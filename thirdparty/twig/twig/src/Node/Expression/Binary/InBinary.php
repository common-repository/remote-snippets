<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Node\Expression\Binary;

use RS_Vendor\Twig\Compiler;
class InBinary extends \RS_Vendor\Twig\Node\Expression\Binary\AbstractBinary
{
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->raw('twig_in_filter(')->subcompile($this->getNode('left'))->raw(', ')->subcompile($this->getNode('right'))->raw(')');
    }
    public function operator(\RS_Vendor\Twig\Compiler $compiler)
    {
        return $compiler->raw('in');
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\Binary\\InBinary', 'RS_Vendor\\Twig_Node_Expression_Binary_In');
