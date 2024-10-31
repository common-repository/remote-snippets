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

use RS_Vendor\Twig\NodeVisitor\NodeVisitorInterface;
use RS_Vendor\Twig\TokenParser\TokenParserInterface;
use RS_Vendor\Twig\TwigFilter;
use RS_Vendor\Twig\TwigFunction;
use RS_Vendor\Twig\TwigTest;
/**
 * Used by \Twig\Environment as a staging area.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @internal
 */
final class StagingExtension extends \RS_Vendor\Twig\Extension\AbstractExtension
{
    private $functions = [];
    private $filters = [];
    private $visitors = [];
    private $tokenParsers = [];
    private $tests = [];
    public function addFunction(\RS_Vendor\Twig\TwigFunction $function)
    {
        if (isset($this->functions[$function->getName()])) {
            throw new \LogicException(\sprintf('Function "%s" is already registered.', $function->getName()));
        }
        $this->functions[$function->getName()] = $function;
    }
    public function getFunctions()
    {
        return $this->functions;
    }
    public function addFilter(\RS_Vendor\Twig\TwigFilter $filter)
    {
        if (isset($this->filters[$filter->getName()])) {
            throw new \LogicException(\sprintf('Filter "%s" is already registered.', $filter->getName()));
        }
        $this->filters[$filter->getName()] = $filter;
    }
    public function getFilters()
    {
        return $this->filters;
    }
    public function addNodeVisitor(\RS_Vendor\Twig\NodeVisitor\NodeVisitorInterface $visitor)
    {
        $this->visitors[] = $visitor;
    }
    public function getNodeVisitors()
    {
        return $this->visitors;
    }
    public function addTokenParser(\RS_Vendor\Twig\TokenParser\TokenParserInterface $parser)
    {
        if (isset($this->tokenParsers[$parser->getTag()])) {
            throw new \LogicException(\sprintf('Tag "%s" is already registered.', $parser->getTag()));
        }
        $this->tokenParsers[$parser->getTag()] = $parser;
    }
    public function getTokenParsers()
    {
        return $this->tokenParsers;
    }
    public function addTest(\RS_Vendor\Twig\TwigTest $test)
    {
        if (isset($this->tests[$test->getName()])) {
            throw new \LogicException(\sprintf('Test "%s" is already registered.', $test->getName()));
        }
        $this->tests[$test->getName()] = $test;
    }
    public function getTests()
    {
        return $this->tests;
    }
}
\class_alias('RS_Vendor\\Twig\\Extension\\StagingExtension', 'RS_Vendor\\Twig_Extension_Staging');
