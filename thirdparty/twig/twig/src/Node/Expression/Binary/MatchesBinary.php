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
class MatchesBinary extends \RS_Vendor\Twig\Node\Expression\Binary\AbstractBinary
{
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->raw('preg_match(')->subcompile($this->getNode('right'))->raw(', ')->subcompile($this->getNode('left'))->raw(')');
    }
    public function operator(\RS_Vendor\Twig\Compiler $compiler)
    {
        return $compiler->raw('');
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\Expression\\Binary\\MatchesBinary', 'RS_Vendor\\Twig_Node_Expression_Binary_Matches');
