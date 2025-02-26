<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Profiler\Node;

use RS_Vendor\Twig\Compiler;
use RS_Vendor\Twig\Node\Node;
/**
 * Represents a profile leave node.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class LeaveProfileNode extends \RS_Vendor\Twig\Node\Node
{
    public function __construct(string $varName)
    {
        parent::__construct([], ['var_name' => $varName]);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->write("\n")->write(\sprintf("\$%s->leave(\$%s);\n\n", $this->getAttribute('var_name'), $this->getAttribute('var_name') . '_prof'));
    }
}
\class_alias('RS_Vendor\\Twig\\Profiler\\Node\\LeaveProfileNode', 'RS_Vendor\\Twig_Profiler_Node_LeaveProfile');
