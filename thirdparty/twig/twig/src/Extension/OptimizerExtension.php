<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Extension;

use RS_Vendor\Twig\NodeVisitor\OptimizerNodeVisitor;
final class OptimizerExtension extends \RS_Vendor\Twig\Extension\AbstractExtension
{
    private $optimizers;
    public function __construct($optimizers = -1)
    {
        $this->optimizers = $optimizers;
    }
    public function getNodeVisitors()
    {
        return [new \RS_Vendor\Twig\NodeVisitor\OptimizerNodeVisitor($this->optimizers)];
    }
}
\class_alias('RS_Vendor\\Twig\\Extension\\OptimizerExtension', 'RS_Vendor\\Twig_Extension_Optimizer');
