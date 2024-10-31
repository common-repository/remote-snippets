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
class SpaceshipBinary extends \RS_Vendor\Twig\Node\Expression\Binary\AbstractBinary
{
    public function operator(\RS_Vendor\Twig\Compiler $compiler)
    {
        return $compiler->raw('<=>');
    }
}
