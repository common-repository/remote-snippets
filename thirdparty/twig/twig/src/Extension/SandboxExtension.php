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

use RS_Vendor\Twig\NodeVisitor\SandboxNodeVisitor;
use RS_Vendor\Twig\Sandbox\SecurityNotAllowedMethodError;
use RS_Vendor\Twig\Sandbox\SecurityNotAllowedPropertyError;
use RS_Vendor\Twig\Sandbox\SecurityPolicyInterface;
use RS_Vendor\Twig\Source;
use RS_Vendor\Twig\TokenParser\SandboxTokenParser;
final class SandboxExtension extends \RS_Vendor\Twig\Extension\AbstractExtension
{
    private $sandboxedGlobally;
    private $sandboxed;
    private $policy;
    public function __construct(\RS_Vendor\Twig\Sandbox\SecurityPolicyInterface $policy, $sandboxed = \false)
    {
        $this->policy = $policy;
        $this->sandboxedGlobally = $sandboxed;
    }
    public function getTokenParsers()
    {
        return [new \RS_Vendor\Twig\TokenParser\SandboxTokenParser()];
    }
    public function getNodeVisitors()
    {
        return [new \RS_Vendor\Twig\NodeVisitor\SandboxNodeVisitor()];
    }
    public function enableSandbox()
    {
        $this->sandboxed = \true;
    }
    public function disableSandbox()
    {
        $this->sandboxed = \false;
    }
    public function isSandboxed()
    {
        return $this->sandboxedGlobally || $this->sandboxed;
    }
    public function isSandboxedGlobally()
    {
        return $this->sandboxedGlobally;
    }
    public function setSecurityPolicy(\RS_Vendor\Twig\Sandbox\SecurityPolicyInterface $policy)
    {
        $this->policy = $policy;
    }
    public function getSecurityPolicy()
    {
        return $this->policy;
    }
    public function checkSecurity($tags, $filters, $functions)
    {
        if ($this->isSandboxed()) {
            $this->policy->checkSecurity($tags, $filters, $functions);
        }
    }
    public function checkMethodAllowed($obj, $method, int $lineno = -1, \RS_Vendor\Twig\Source $source = null)
    {
        if ($this->isSandboxed()) {
            try {
                $this->policy->checkMethodAllowed($obj, $method);
            } catch (\RS_Vendor\Twig\Sandbox\SecurityNotAllowedMethodError $e) {
                $e->setSourceContext($source);
                $e->setTemplateLine($lineno);
                throw $e;
            }
        }
    }
    public function checkPropertyAllowed($obj, $method, int $lineno = -1, \RS_Vendor\Twig\Source $source = null)
    {
        if ($this->isSandboxed()) {
            try {
                $this->policy->checkPropertyAllowed($obj, $method);
            } catch (\RS_Vendor\Twig\Sandbox\SecurityNotAllowedPropertyError $e) {
                $e->setSourceContext($source);
                $e->setTemplateLine($lineno);
                throw $e;
            }
        }
    }
    public function ensureToStringAllowed($obj, int $lineno = -1, \RS_Vendor\Twig\Source $source = null)
    {
        if ($this->isSandboxed() && \is_object($obj) && \method_exists($obj, '__toString')) {
            try {
                $this->policy->checkMethodAllowed($obj, '__toString');
            } catch (\RS_Vendor\Twig\Sandbox\SecurityNotAllowedMethodError $e) {
                $e->setSourceContext($source);
                $e->setTemplateLine($lineno);
                throw $e;
            }
        }
        return $obj;
    }
}
\class_alias('RS_Vendor\\Twig\\Extension\\SandboxExtension', 'RS_Vendor\\Twig_Extension_Sandbox');
