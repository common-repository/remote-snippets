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
use RS_Vendor\Twig\Node\Expression\AbstractExpression;
/**
 * Checks if casting an expression to __toString() is allowed by the sandbox.
 *
 * For instance, when there is a simple Print statement, like {{ article }},
 * and if the sandbox is enabled, we need to check that the __toString()
 * method is allowed if 'article' is an object. The same goes for {{ article|upper }}
 * or {{ random(article) }}
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class CheckToStringNode extends \RS_Vendor\Twig\Node\Expression\AbstractExpression
{
    public function __construct(\RS_Vendor\Twig\Node\Expression\AbstractExpression $expr)
    {
        parent::__construct(['expr' => $expr], [], $expr->getTemplateLine(), $expr->getNodeTag());
    }
    public function compile(\RS_Vendor\Twig\Compiler $compiler)
    {
        $expr = $this->getNode('expr');
        $compiler->raw('$this->sandbox->ensureToStringAllowed(')->subcompile($expr)->raw(', ')->repr($expr->getTemplateLine())->raw(', $this->source)');
    }
}
