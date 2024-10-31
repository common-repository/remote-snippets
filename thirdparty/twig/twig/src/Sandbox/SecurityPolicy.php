<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RS_Vendor\Twig\Sandbox;

use RS_Vendor\Twig\Markup;
use RS_Vendor\Twig\Template;
/**
 * Represents a security policy which need to be enforced when sandbox mode is enabled.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class SecurityPolicy implements \RS_Vendor\Twig\Sandbox\SecurityPolicyInterface
{
    private $allowedTags;
    private $allowedFilters;
    private $allowedMethods;
    private $allowedProperties;
    private $allowedFunctions;
    public function __construct(array $allowedTags = [], array $allowedFilters = [], array $allowedMethods = [], array $allowedProperties = [], array $allowedFunctions = [])
    {
        $this->allowedTags = $allowedTags;
        $this->allowedFilters = $allowedFilters;
        $this->setAllowedMethods($allowedMethods);
        $this->allowedProperties = $allowedProperties;
        $this->allowedFunctions = $allowedFunctions;
    }
    public function setAllowedTags(array $tags)
    {
        $this->allowedTags = $tags;
    }
    public function setAllowedFilters(array $filters)
    {
        $this->allowedFilters = $filters;
    }
    public function setAllowedMethods(array $methods)
    {
        $this->allowedMethods = [];
        foreach ($methods as $class => $m) {
            $this->allowedMethods[$class] = \array_map('strtolower', \is_array($m) ? $m : [$m]);
        }
    }
    public function setAllowedProperties(array $properties)
    {
        $this->allowedProperties = $properties;
    }
    public function setAllowedFunctions(array $functions)
    {
        $this->allowedFunctions = $functions;
    }
    public function checkSecurity($tags, $filters, $functions)
    {
        foreach ($tags as $tag) {
            if (!\in_array($tag, $this->allowedTags)) {
                throw new \RS_Vendor\Twig\Sandbox\SecurityNotAllowedTagError(\sprintf('Tag "%s" is not allowed.', $tag), $tag);
            }
        }
        foreach ($filters as $filter) {
            if (!\in_array($filter, $this->allowedFilters)) {
                throw new \RS_Vendor\Twig\Sandbox\SecurityNotAllowedFilterError(\sprintf('Filter "%s" is not allowed.', $filter), $filter);
            }
        }
        foreach ($functions as $function) {
            if (!\in_array($function, $this->allowedFunctions)) {
                throw new \RS_Vendor\Twig\Sandbox\SecurityNotAllowedFunctionError(\sprintf('Function "%s" is not allowed.', $function), $function);
            }
        }
    }
    public function checkMethodAllowed($obj, $method)
    {
        if ($obj instanceof \RS_Vendor\Twig\Template || $obj instanceof \RS_Vendor\Twig\Markup) {
            return;
        }
        $allowed = \false;
        $method = \strtolower($method);
        foreach ($this->allowedMethods as $class => $methods) {
            if ($obj instanceof $class) {
                $allowed = \in_array($method, $methods);
                break;
            }
        }
        if (!$allowed) {
            $class = \get_class($obj);
            throw new \RS_Vendor\Twig\Sandbox\SecurityNotAllowedMethodError(\sprintf('Calling "%s" method on a "%s" object is not allowed.', $method, $class), $class, $method);
        }
    }
    public function checkPropertyAllowed($obj, $property)
    {
        $allowed = \false;
        foreach ($this->allowedProperties as $class => $properties) {
            if ($obj instanceof $class) {
                $allowed = \in_array($property, \is_array($properties) ? $properties : [$properties]);
                break;
            }
        }
        if (!$allowed) {
            $class = \get_class($obj);
            throw new \RS_Vendor\Twig\Sandbox\SecurityNotAllowedPropertyError(\sprintf('Calling "%s" property on a "%s" object is not allowed.', $property, $class), $class, $property);
        }
    }
}
\class_alias('RS_Vendor\\Twig\\Sandbox\\SecurityPolicy', 'RS_Vendor\\Twig_Sandbox_SecurityPolicy');
