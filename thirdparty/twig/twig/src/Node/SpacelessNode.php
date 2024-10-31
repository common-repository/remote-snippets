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
 * Represents a spaceless node.
 *
 * It removes spaces between HTML tags.
 *
 * @deprecated since Twig 2.7, to be removed in 3.0
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class SpacelessNode extends \RS_Vendor\Twig\Node\Node implements \RS_Vendor\Twig\Node\NodeOutputInterface
{
    public function __construct(\RS_Vendor\Twig\Node\Node $body, int $lineno, string $tag = 'spaceless')
    {
        parent::__construct(['body' => $body], [], $lineno, $tag);
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $compiler->addDebugInfo($this);
        if ($compiler->getEnvironment()->isDebug()) {
            $compiler->write("ob_start();\n");
        } else {
            $compiler->write("ob_start(function () { return ''; });\n");
        }
        $compiler->subcompile($this->getNode('body'))->write("echo trim(preg_replace('/>\\s+</', '><', ob_get_clean()));\n");
    }
}
\class_alias('RS_Vendor\\Twig\\Node\\SpacelessNode', 'RS_Vendor\\Twig_Node_Spaceless');
