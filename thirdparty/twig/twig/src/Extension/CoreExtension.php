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

use RS_Vendor\Twig\ExpressionParser;
use RS_Vendor\Twig\Node\Expression\Binary\AddBinary;
use RS_Vendor\Twig\Node\Expression\Binary\AndBinary;
use RS_Vendor\Twig\Node\Expression\Binary\BitwiseAndBinary;
use RS_Vendor\Twig\Node\Expression\Binary\BitwiseOrBinary;
use RS_Vendor\Twig\Node\Expression\Binary\BitwiseXorBinary;
use RS_Vendor\Twig\Node\Expression\Binary\ConcatBinary;
use RS_Vendor\Twig\Node\Expression\Binary\DivBinary;
use RS_Vendor\Twig\Node\Expression\Binary\EndsWithBinary;
use RS_Vendor\Twig\Node\Expression\Binary\EqualBinary;
use RS_Vendor\Twig\Node\Expression\Binary\FloorDivBinary;
use RS_Vendor\Twig\Node\Expression\Binary\GreaterBinary;
use RS_Vendor\Twig\Node\Expression\Binary\GreaterEqualBinary;
use RS_Vendor\Twig\Node\Expression\Binary\InBinary;
use RS_Vendor\Twig\Node\Expression\Binary\LessBinary;
use RS_Vendor\Twig\Node\Expression\Binary\LessEqualBinary;
use RS_Vendor\Twig\Node\Expression\Binary\MatchesBinary;
use RS_Vendor\Twig\Node\Expression\Binary\ModBinary;
use RS_Vendor\Twig\Node\Expression\Binary\MulBinary;
use RS_Vendor\Twig\Node\Expression\Binary\NotEqualBinary;
use RS_Vendor\Twig\Node\Expression\Binary\NotInBinary;
use RS_Vendor\Twig\Node\Expression\Binary\OrBinary;
use RS_Vendor\Twig\Node\Expression\Binary\PowerBinary;
use RS_Vendor\Twig\Node\Expression\Binary\RangeBinary;
use RS_Vendor\Twig\Node\Expression\Binary\SpaceshipBinary;
use RS_Vendor\Twig\Node\Expression\Binary\StartsWithBinary;
use RS_Vendor\Twig\Node\Expression\Binary\SubBinary;
use RS_Vendor\Twig\Node\Expression\Filter\DefaultFilter;
use RS_Vendor\Twig\Node\Expression\NullCoalesceExpression;
use RS_Vendor\Twig\Node\Expression\Test\ConstantTest;
use RS_Vendor\Twig\Node\Expression\Test\DefinedTest;
use RS_Vendor\Twig\Node\Expression\Test\DivisiblebyTest;
use RS_Vendor\Twig\Node\Expression\Test\EvenTest;
use RS_Vendor\Twig\Node\Expression\Test\NullTest;
use RS_Vendor\Twig\Node\Expression\Test\OddTest;
use RS_Vendor\Twig\Node\Expression\Test\SameasTest;
use RS_Vendor\Twig\Node\Expression\Unary\NegUnary;
use RS_Vendor\Twig\Node\Expression\Unary\NotUnary;
use RS_Vendor\Twig\Node\Expression\Unary\PosUnary;
use RS_Vendor\Twig\NodeVisitor\MacroAutoImportNodeVisitor;
use RS_Vendor\Twig\TokenParser\ApplyTokenParser;
use RS_Vendor\Twig\TokenParser\BlockTokenParser;
use RS_Vendor\Twig\TokenParser\DeprecatedTokenParser;
use RS_Vendor\Twig\TokenParser\DoTokenParser;
use RS_Vendor\Twig\TokenParser\EmbedTokenParser;
use RS_Vendor\Twig\TokenParser\ExtendsTokenParser;
use RS_Vendor\Twig\TokenParser\FilterTokenParser;
use RS_Vendor\Twig\TokenParser\FlushTokenParser;
use RS_Vendor\Twig\TokenParser\ForTokenParser;
use RS_Vendor\Twig\TokenParser\FromTokenParser;
use RS_Vendor\Twig\TokenParser\IfTokenParser;
use RS_Vendor\Twig\TokenParser\ImportTokenParser;
use RS_Vendor\Twig\TokenParser\IncludeTokenParser;
use RS_Vendor\Twig\TokenParser\MacroTokenParser;
use RS_Vendor\Twig\TokenParser\SetTokenParser;
use RS_Vendor\Twig\TokenParser\SpacelessTokenParser;
use RS_Vendor\Twig\TokenParser\UseTokenParser;
use RS_Vendor\Twig\TokenParser\WithTokenParser;
use RS_Vendor\Twig\TwigFilter;
use RS_Vendor\Twig\TwigFunction;
use RS_Vendor\Twig\TwigTest;
final class CoreExtension extends \RS_Vendor\Twig\Extension\AbstractExtension
{
    private $dateFormats = ['F j, Y H:i', '%d days'];
    private $numberFormat = [0, '.', ','];
    private $timezone = null;
    private $escapers = [];
    /**
     * Defines a new escaper to be used via the escape filter.
     *
     * @param string   $strategy The strategy name that should be used as a strategy in the escape call
     * @param callable $callable A valid PHP callable
     *
     * @deprecated since Twig 2.11, to be removed in 3.0; use the same method on EscaperExtension instead
     */
    public function setEscaper($strategy, callable $callable)
    {
        @\trigger_error(\sprintf('The "%s" method is deprecated since Twig 2.11; use "%s::setEscaper" instead.', __METHOD__, \RS_Vendor\Twig\Extension\EscaperExtension::class), \E_USER_DEPRECATED);
        $this->escapers[$strategy] = $callable;
    }
    /**
     * Gets all defined escapers.
     *
     * @return callable[] An array of escapers
     *
     * @deprecated since Twig 2.11, to be removed in 3.0; use the same method on EscaperExtension instead
     */
    public function getEscapers()
    {
        if (0 === \func_num_args() || \func_get_arg(0)) {
            @\trigger_error(\sprintf('The "%s" method is deprecated since Twig 2.11; use "%s::getEscapers" instead.', __METHOD__, \RS_Vendor\Twig\Extension\EscaperExtension::class), \E_USER_DEPRECATED);
        }
        return $this->escapers;
    }
    /**
     * Sets the default format to be used by the date filter.
     *
     * @param string $format             The default date format string
     * @param string $dateIntervalFormat The default date interval format string
     */
    public function setDateFormat($format = null, $dateIntervalFormat = null)
    {
        if (null !== $format) {
            $this->dateFormats[0] = $format;
        }
        if (null !== $dateIntervalFormat) {
            $this->dateFormats[1] = $dateIntervalFormat;
        }
    }
    /**
     * Gets the default format to be used by the date filter.
     *
     * @return array The default date format string and the default date interval format string
     */
    public function getDateFormat()
    {
        return $this->dateFormats;
    }
    /**
     * Sets the default timezone to be used by the date filter.
     *
     * @param \DateTimeZone|string $timezone The default timezone string or a \DateTimeZone object
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone instanceof \DateTimeZone ? $timezone : new \DateTimeZone($timezone);
    }
    /**
     * Gets the default timezone to be used by the date filter.
     *
     * @return \DateTimeZone The default timezone currently in use
     */
    public function getTimezone()
    {
        if (null === $this->timezone) {
            $this->timezone = new \DateTimeZone(\date_default_timezone_get());
        }
        return $this->timezone;
    }
    /**
     * Sets the default format to be used by the number_format filter.
     *
     * @param int    $decimal      the number of decimal places to use
     * @param string $decimalPoint the character(s) to use for the decimal point
     * @param string $thousandSep  the character(s) to use for the thousands separator
     */
    public function setNumberFormat($decimal, $decimalPoint, $thousandSep)
    {
        $this->numberFormat = [$decimal, $decimalPoint, $thousandSep];
    }
    /**
     * Get the default format used by the number_format filter.
     *
     * @return array The arguments for number_format()
     */
    public function getNumberFormat()
    {
        return $this->numberFormat;
    }
    public function getTokenParsers()
    {
        return [new \RS_Vendor\Twig\TokenParser\ApplyTokenParser(), new \RS_Vendor\Twig\TokenParser\ForTokenParser(), new \RS_Vendor\Twig\TokenParser\IfTokenParser(), new \RS_Vendor\Twig\TokenParser\ExtendsTokenParser(), new \RS_Vendor\Twig\TokenParser\IncludeTokenParser(), new \RS_Vendor\Twig\TokenParser\BlockTokenParser(), new \RS_Vendor\Twig\TokenParser\UseTokenParser(), new \RS_Vendor\Twig\TokenParser\FilterTokenParser(), new \RS_Vendor\Twig\TokenParser\MacroTokenParser(), new \RS_Vendor\Twig\TokenParser\ImportTokenParser(), new \RS_Vendor\Twig\TokenParser\FromTokenParser(), new \RS_Vendor\Twig\TokenParser\SetTokenParser(), new \RS_Vendor\Twig\TokenParser\SpacelessTokenParser(), new \RS_Vendor\Twig\TokenParser\FlushTokenParser(), new \RS_Vendor\Twig\TokenParser\DoTokenParser(), new \RS_Vendor\Twig\TokenParser\EmbedTokenParser(), new \RS_Vendor\Twig\TokenParser\WithTokenParser(), new \RS_Vendor\Twig\TokenParser\DeprecatedTokenParser()];
    }
    public function getFilters()
    {
        return [
            // formatting filters
            new \RS_Vendor\Twig\TwigFilter('date', '\\RS_Vendor\\twig_date_format_filter', ['needs_environment' => \true]),
            new \RS_Vendor\Twig\TwigFilter('date_modify', '\\RS_Vendor\\twig_date_modify_filter', ['needs_environment' => \true]),
            new \RS_Vendor\Twig\TwigFilter('format', 'sprintf'),
            new \RS_Vendor\Twig\TwigFilter('replace', '\\RS_Vendor\\twig_replace_filter'),
            new \RS_Vendor\Twig\TwigFilter('number_format', '\\RS_Vendor\\twig_number_format_filter', ['needs_environment' => \true]),
            new \RS_Vendor\Twig\TwigFilter('abs', 'abs'),
            new \RS_Vendor\Twig\TwigFilter('round', '\\RS_Vendor\\twig_round'),
            // encoding
            new \RS_Vendor\Twig\TwigFilter('url_encode', '\\RS_Vendor\\twig_urlencode_filter'),
            new \RS_Vendor\Twig\TwigFilter('json_encode', 'json_encode'),
            new \RS_Vendor\Twig\TwigFilter('convert_encoding', '\\RS_Vendor\\twig_convert_encoding'),
            // string filters
            new \RS_Vendor\Twig\TwigFilter('title', '\\RS_Vendor\\twig_title_string_filter', ['needs_environment' => \true]),
            new \RS_Vendor\Twig\TwigFilter('capitalize', '\\RS_Vendor\\twig_capitalize_string_filter', ['needs_environment' => \true]),
            new \RS_Vendor\Twig\TwigFilter('upper', '\\RS_Vendor\\twig_upper_filter', ['needs_environment' => \true]),
            new \RS_Vendor\Twig\TwigFilter('lower', '\\RS_Vendor\\twig_lower_filter', ['needs_environment' => \true]),
            new \RS_Vendor\Twig\TwigFilter('striptags', 'strip_tags'),
            new \RS_Vendor\Twig\TwigFilter('trim', '\\RS_Vendor\\twig_trim_filter'),
            new \RS_Vendor\Twig\TwigFilter('nl2br', 'nl2br', ['pre_escape' => 'html', 'is_safe' => ['html']]),
            new \RS_Vendor\Twig\TwigFilter('spaceless', '\\RS_Vendor\\twig_spaceless', ['is_safe' => ['html']]),
            // array helpers
            new \RS_Vendor\Twig\TwigFilter('join', '\\RS_Vendor\\twig_join_filter'),
            new \RS_Vendor\Twig\TwigFilter('split', '\\RS_Vendor\\twig_split_filter', ['needs_environment' => \true]),
            new \RS_Vendor\Twig\TwigFilter('sort', '\\RS_Vendor\\twig_sort_filter'),
            new \RS_Vendor\Twig\TwigFilter('merge', '\\RS_Vendor\\twig_array_merge'),
            new \RS_Vendor\Twig\TwigFilter('batch', '\\RS_Vendor\\twig_array_batch'),
            new \RS_Vendor\Twig\TwigFilter('column', '\\RS_Vendor\\twig_array_column'),
            new \RS_Vendor\Twig\TwigFilter('filter', '\\RS_Vendor\\twig_array_filter'),
            new \RS_Vendor\Twig\TwigFilter('map', '\\RS_Vendor\\twig_array_map'),
            new \RS_Vendor\Twig\TwigFilter('reduce', '\\RS_Vendor\\twig_array_reduce'),
            // string/array filters
            new \RS_Vendor\Twig\TwigFilter('reverse', '\\RS_Vendor\\twig_reverse_filter', ['needs_environment' => \true]),
            new \RS_Vendor\Twig\TwigFilter('length', '\\RS_Vendor\\twig_length_filter', ['needs_environment' => \true]),
            new \RS_Vendor\Twig\TwigFilter('slice', '\\RS_Vendor\\twig_slice', ['needs_environment' => \true]),
            new \RS_Vendor\Twig\TwigFilter('first', '\\RS_Vendor\\twig_first', ['needs_environment' => \true]),
            new \RS_Vendor\Twig\TwigFilter('last', '\\RS_Vendor\\twig_last', ['needs_environment' => \true]),
            // iteration and runtime
            new \RS_Vendor\Twig\TwigFilter('default', '\\RS_Vendor\\_twig_default_filter', ['node_class' => \RS_Vendor\Twig\Node\Expression\Filter\DefaultFilter::class]),
            new \RS_Vendor\Twig\TwigFilter('keys', '\\RS_Vendor\\twig_get_array_keys_filter'),
        ];
    }
    public function getFunctions()
    {
        return [new \RS_Vendor\Twig\TwigFunction('max', 'max'), new \RS_Vendor\Twig\TwigFunction('min', 'min'), new \RS_Vendor\Twig\TwigFunction('range', 'range'), new \RS_Vendor\Twig\TwigFunction('constant', 'twig_constant'), new \RS_Vendor\Twig\TwigFunction('cycle', 'twig_cycle'), new \RS_Vendor\Twig\TwigFunction('random', 'twig_random', ['needs_environment' => \true]), new \RS_Vendor\Twig\TwigFunction('date', 'twig_date_converter', ['needs_environment' => \true]), new \RS_Vendor\Twig\TwigFunction('include', 'twig_include', ['needs_environment' => \true, 'needs_context' => \true, 'is_safe' => ['all']]), new \RS_Vendor\Twig\TwigFunction('source', 'twig_source', ['needs_environment' => \true, 'is_safe' => ['all']])];
    }
    public function getTests()
    {
        return [new \RS_Vendor\Twig\TwigTest('even', null, ['node_class' => \RS_Vendor\Twig\Node\Expression\Test\EvenTest::class]), new \RS_Vendor\Twig\TwigTest('odd', null, ['node_class' => \RS_Vendor\Twig\Node\Expression\Test\OddTest::class]), new \RS_Vendor\Twig\TwigTest('defined', null, ['node_class' => \RS_Vendor\Twig\Node\Expression\Test\DefinedTest::class]), new \RS_Vendor\Twig\TwigTest('same as', null, ['node_class' => \RS_Vendor\Twig\Node\Expression\Test\SameasTest::class]), new \RS_Vendor\Twig\TwigTest('none', null, ['node_class' => \RS_Vendor\Twig\Node\Expression\Test\NullTest::class]), new \RS_Vendor\Twig\TwigTest('null', null, ['node_class' => \RS_Vendor\Twig\Node\Expression\Test\NullTest::class]), new \RS_Vendor\Twig\TwigTest('divisible by', null, ['node_class' => \RS_Vendor\Twig\Node\Expression\Test\DivisiblebyTest::class]), new \RS_Vendor\Twig\TwigTest('constant', null, ['node_class' => \RS_Vendor\Twig\Node\Expression\Test\ConstantTest::class]), new \RS_Vendor\Twig\TwigTest('empty', 'twig_test_empty'), new \RS_Vendor\Twig\TwigTest('iterable', 'twig_test_iterable')];
    }
    public function getNodeVisitors()
    {
        return [new \RS_Vendor\Twig\NodeVisitor\MacroAutoImportNodeVisitor()];
    }
    public function getOperators()
    {
        return [['not' => ['precedence' => 50, 'class' => \RS_Vendor\Twig\Node\Expression\Unary\NotUnary::class], '-' => ['precedence' => 500, 'class' => \RS_Vendor\Twig\Node\Expression\Unary\NegUnary::class], '+' => ['precedence' => 500, 'class' => \RS_Vendor\Twig\Node\Expression\Unary\PosUnary::class]], ['or' => ['precedence' => 10, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\OrBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], 'and' => ['precedence' => 15, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\AndBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], 'b-or' => ['precedence' => 16, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\BitwiseOrBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], 'b-xor' => ['precedence' => 17, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\BitwiseXorBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], 'b-and' => ['precedence' => 18, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\BitwiseAndBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '==' => ['precedence' => 20, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\EqualBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '!=' => ['precedence' => 20, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\NotEqualBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '<=>' => ['precedence' => 20, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\SpaceshipBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '<' => ['precedence' => 20, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\LessBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '>' => ['precedence' => 20, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\GreaterBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '>=' => ['precedence' => 20, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\GreaterEqualBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '<=' => ['precedence' => 20, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\LessEqualBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], 'not in' => ['precedence' => 20, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\NotInBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], 'in' => ['precedence' => 20, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\InBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], 'matches' => ['precedence' => 20, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\MatchesBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], 'starts with' => ['precedence' => 20, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\StartsWithBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], 'ends with' => ['precedence' => 20, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\EndsWithBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '..' => ['precedence' => 25, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\RangeBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '+' => ['precedence' => 30, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\AddBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '-' => ['precedence' => 30, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\SubBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '~' => ['precedence' => 40, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\ConcatBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '*' => ['precedence' => 60, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\MulBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '/' => ['precedence' => 60, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\DivBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '//' => ['precedence' => 60, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\FloorDivBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '%' => ['precedence' => 60, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\ModBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], 'is' => ['precedence' => 100, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], 'is not' => ['precedence' => 100, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_LEFT], '**' => ['precedence' => 200, 'class' => \RS_Vendor\Twig\Node\Expression\Binary\PowerBinary::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_RIGHT], '??' => ['precedence' => 300, 'class' => \RS_Vendor\Twig\Node\Expression\NullCoalesceExpression::class, 'associativity' => \RS_Vendor\Twig\ExpressionParser::OPERATOR_RIGHT]]];
    }
}
\class_alias('RS_Vendor\\Twig\\Extension\\CoreExtension', 'RS_Vendor\\Twig_Extension_Core');
namespace RS_Vendor;

use RS_Vendor\Twig\Environment;
use RS_Vendor\Twig\Error\LoaderError;
use RS_Vendor\Twig\Error\RuntimeError;
use RS_Vendor\Twig\Extension\CoreExtension;
use RS_Vendor\Twig\Extension\SandboxExtension;
use RS_Vendor\Twig\Markup;
use RS_Vendor\Twig\Source;
use RS_Vendor\Twig\Template;
/**
 * Cycles over a value.
 *
 * @param \ArrayAccess|array $values
 * @param int                $position The cycle position
 *
 * @return string The next value in the cycle
 */
function twig_cycle($values, $position)
{
    if (!\is_array($values) && !$values instanceof \ArrayAccess) {
        return $values;
    }
    return $values[$position % \count($values)];
}
/**
 * Returns a random value depending on the supplied parameter type:
 * - a random item from a \Traversable or array
 * - a random character from a string
 * - a random integer between 0 and the integer parameter.
 *
 * @param \Traversable|array|int|float|string $values The values to pick a random item from
 * @param int|null                            $max    Maximum value used when $values is an int
 *
 * @throws RuntimeError when $values is an empty array (does not apply to an empty string which is returned as is)
 *
 * @return mixed A random value from the given sequence
 */
function twig_random(\RS_Vendor\Twig\Environment $env, $values = null, $max = null)
{
    if (null === $values) {
        return null === $max ? \mt_rand() : \mt_rand(0, $max);
    }
    if (\is_int($values) || \is_float($values)) {
        if (null === $max) {
            if ($values < 0) {
                $max = 0;
                $min = $values;
            } else {
                $max = $values;
                $min = 0;
            }
        } else {
            $min = $values;
            $max = $max;
        }
        return \mt_rand($min, $max);
    }
    if (\is_string($values)) {
        if ('' === $values) {
            return '';
        }
        $charset = $env->getCharset();
        if ('UTF-8' !== $charset) {
            $values = \iconv($charset, 'UTF-8', $values);
        }
        // unicode version of str_split()
        // split at all positions, but not after the start and not before the end
        $values = \preg_split('/(?<!^)(?!$)/u', $values);
        if ('UTF-8' !== $charset) {
            foreach ($values as $i => $value) {
                $values[$i] = \iconv('UTF-8', $charset, $value);
            }
        }
    }
    if (!\RS_Vendor\twig_test_iterable($values)) {
        return $values;
    }
    $values = \RS_Vendor\twig_to_array($values);
    if (0 === \count($values)) {
        throw new \RS_Vendor\Twig\Error\RuntimeError('The random function cannot pick from an empty array.');
    }
    return $values[\array_rand($values, 1)];
}
/**
 * Converts a date to the given format.
 *
 *   {{ post.published_at|date("m/d/Y") }}
 *
 * @param \DateTimeInterface|\DateInterval|string $date     A date
 * @param string|null                             $format   The target format, null to use the default
 * @param \DateTimeZone|string|false|null         $timezone The target timezone, null to use the default, false to leave unchanged
 *
 * @return string The formatted date
 */
function twig_date_format_filter(\RS_Vendor\Twig\Environment $env, $date, $format = null, $timezone = null)
{
    if (null === $format) {
        $formats = $env->getExtension(\RS_Vendor\Twig\Extension\CoreExtension::class)->getDateFormat();
        $format = $date instanceof \DateInterval ? $formats[1] : $formats[0];
    }
    if ($date instanceof \DateInterval) {
        return $date->format($format);
    }
    return \RS_Vendor\twig_date_converter($env, $date, $timezone)->format($format);
}
/**
 * Returns a new date object modified.
 *
 *   {{ post.published_at|date_modify("-1day")|date("m/d/Y") }}
 *
 * @param \DateTimeInterface|string $date     A date
 * @param string                    $modifier A modifier string
 *
 * @return \DateTimeInterface
 */
function twig_date_modify_filter(\RS_Vendor\Twig\Environment $env, $date, $modifier)
{
    $date = \RS_Vendor\twig_date_converter($env, $date, \false);
    return $date->modify($modifier);
}
/**
 * Converts an input to a \DateTime instance.
 *
 *    {% if date(user.created_at) < date('+2days') %}
 *      {# do something #}
 *    {% endif %}
 *
 * @param \DateTimeInterface|string|null  $date     A date or null to use the current time
 * @param \DateTimeZone|string|false|null $timezone The target timezone, null to use the default, false to leave unchanged
 *
 * @return \DateTimeInterface
 */
function twig_date_converter(\RS_Vendor\Twig\Environment $env, $date = null, $timezone = null)
{
    // determine the timezone
    if (\false !== $timezone) {
        if (null === $timezone) {
            $timezone = $env->getExtension(\RS_Vendor\Twig\Extension\CoreExtension::class)->getTimezone();
        } elseif (!$timezone instanceof \DateTimeZone) {
            $timezone = new \DateTimeZone($timezone);
        }
    }
    // immutable dates
    if ($date instanceof \DateTimeImmutable) {
        return \false !== $timezone ? $date->setTimezone($timezone) : $date;
    }
    if ($date instanceof \DateTimeInterface) {
        $date = clone $date;
        if (\false !== $timezone) {
            $date->setTimezone($timezone);
        }
        return $date;
    }
    if (null === $date || 'now' === $date) {
        return new \DateTime($date, \false !== $timezone ? $timezone : $env->getExtension(\RS_Vendor\Twig\Extension\CoreExtension::class)->getTimezone());
    }
    $asString = (string) $date;
    if (\ctype_digit($asString) || !empty($asString) && '-' === $asString[0] && \ctype_digit(\substr($asString, 1))) {
        $date = new \DateTime('@' . $date);
    } else {
        $date = new \DateTime($date, $env->getExtension(\RS_Vendor\Twig\Extension\CoreExtension::class)->getTimezone());
    }
    if (\false !== $timezone) {
        $date->setTimezone($timezone);
    }
    return $date;
}
/**
 * Replaces strings within a string.
 *
 * @param string             $str  String to replace in
 * @param array|\Traversable $from Replace values
 *
 * @return string
 */
function twig_replace_filter($str, $from)
{
    if (!\RS_Vendor\twig_test_iterable($from)) {
        throw new \RS_Vendor\Twig\Error\RuntimeError(\sprintf('The "replace" filter expects an array or "Traversable" as replace values, got "%s".', \is_object($from) ? \get_class($from) : \gettype($from)));
    }
    return \strtr($str, \RS_Vendor\twig_to_array($from));
}
/**
 * Rounds a number.
 *
 * @param int|float $value     The value to round
 * @param int|float $precision The rounding precision
 * @param string    $method    The method to use for rounding
 *
 * @return int|float The rounded number
 */
function twig_round($value, $precision = 0, $method = 'common')
{
    if ('common' == $method) {
        return \round($value, $precision);
    }
    if ('ceil' != $method && 'floor' != $method) {
        throw new \RS_Vendor\Twig\Error\RuntimeError('The round filter only supports the "common", "ceil", and "floor" methods.');
    }
    return $method($value * \pow(10, $precision)) / \pow(10, $precision);
}
/**
 * Number format filter.
 *
 * All of the formatting options can be left null, in that case the defaults will
 * be used.  Supplying any of the parameters will override the defaults set in the
 * environment object.
 *
 * @param mixed  $number       A float/int/string of the number to format
 * @param int    $decimal      the number of decimal points to display
 * @param string $decimalPoint the character(s) to use for the decimal point
 * @param string $thousandSep  the character(s) to use for the thousands separator
 *
 * @return string The formatted number
 */
function twig_number_format_filter(\RS_Vendor\Twig\Environment $env, $number, $decimal = null, $decimalPoint = null, $thousandSep = null)
{
    $defaults = $env->getExtension(\RS_Vendor\Twig\Extension\CoreExtension::class)->getNumberFormat();
    if (null === $decimal) {
        $decimal = $defaults[0];
    }
    if (null === $decimalPoint) {
        $decimalPoint = $defaults[1];
    }
    if (null === $thousandSep) {
        $thousandSep = $defaults[2];
    }
    return \number_format((float) $number, $decimal, $decimalPoint, $thousandSep);
}
/**
 * URL encodes (RFC 3986) a string as a path segment or an array as a query string.
 *
 * @param string|array $url A URL or an array of query parameters
 *
 * @return string The URL encoded value
 */
function twig_urlencode_filter($url)
{
    if (\is_array($url)) {
        return \http_build_query($url, '', '&', \PHP_QUERY_RFC3986);
    }
    return \rawurlencode($url);
}
/**
 * Merges an array with another one.
 *
 *  {% set items = { 'apple': 'fruit', 'orange': 'fruit' } %}
 *
 *  {% set items = items|merge({ 'peugeot': 'car' }) %}
 *
 *  {# items now contains { 'apple': 'fruit', 'orange': 'fruit', 'peugeot': 'car' } #}
 *
 * @param array|\Traversable $arr1 An array
 * @param array|\Traversable $arr2 An array
 *
 * @return array The merged array
 */
function twig_array_merge($arr1, $arr2)
{
    if (!\RS_Vendor\twig_test_iterable($arr1)) {
        throw new \RS_Vendor\Twig\Error\RuntimeError(\sprintf('The merge filter only works with arrays or "Traversable", got "%s" as first argument.', \gettype($arr1)));
    }
    if (!\RS_Vendor\twig_test_iterable($arr2)) {
        throw new \RS_Vendor\Twig\Error\RuntimeError(\sprintf('The merge filter only works with arrays or "Traversable", got "%s" as second argument.', \gettype($arr2)));
    }
    return \array_merge(\RS_Vendor\twig_to_array($arr1), \RS_Vendor\twig_to_array($arr2));
}
/**
 * Slices a variable.
 *
 * @param mixed $item         A variable
 * @param int   $start        Start of the slice
 * @param int   $length       Size of the slice
 * @param bool  $preserveKeys Whether to preserve key or not (when the input is an array)
 *
 * @return mixed The sliced variable
 */
function twig_slice(\RS_Vendor\Twig\Environment $env, $item, $start, $length = null, $preserveKeys = \false)
{
    if ($item instanceof \Traversable) {
        while ($item instanceof \IteratorAggregate) {
            $item = $item->getIterator();
        }
        if ($start >= 0 && $length >= 0 && $item instanceof \Iterator) {
            try {
                return \iterator_to_array(new \LimitIterator($item, $start, null === $length ? -1 : $length), $preserveKeys);
            } catch (\OutOfBoundsException $e) {
                return [];
            }
        }
        $item = \iterator_to_array($item, $preserveKeys);
    }
    if (\is_array($item)) {
        return \array_slice($item, $start, $length, $preserveKeys);
    }
    $item = (string) $item;
    return (string) \mb_substr($item, $start, $length, $env->getCharset());
}
/**
 * Returns the first element of the item.
 *
 * @param mixed $item A variable
 *
 * @return mixed The first element of the item
 */
function twig_first(\RS_Vendor\Twig\Environment $env, $item)
{
    $elements = \RS_Vendor\twig_slice($env, $item, 0, 1, \false);
    return \is_string($elements) ? $elements : \current($elements);
}
/**
 * Returns the last element of the item.
 *
 * @param mixed $item A variable
 *
 * @return mixed The last element of the item
 */
function twig_last(\RS_Vendor\Twig\Environment $env, $item)
{
    $elements = \RS_Vendor\twig_slice($env, $item, -1, 1, \false);
    return \is_string($elements) ? $elements : \current($elements);
}
/**
 * Joins the values to a string.
 *
 * The separators between elements are empty strings per default, you can define them with the optional parameters.
 *
 *  {{ [1, 2, 3]|join(', ', ' and ') }}
 *  {# returns 1, 2 and 3 #}
 *
 *  {{ [1, 2, 3]|join('|') }}
 *  {# returns 1|2|3 #}
 *
 *  {{ [1, 2, 3]|join }}
 *  {# returns 123 #}
 *
 * @param array       $value An array
 * @param string      $glue  The separator
 * @param string|null $and   The separator for the last pair
 *
 * @return string The concatenated string
 */
function twig_join_filter($value, $glue = '', $and = null)
{
    if (!\RS_Vendor\twig_test_iterable($value)) {
        $value = (array) $value;
    }
    $value = \RS_Vendor\twig_to_array($value, \false);
    if (0 === \count($value)) {
        return '';
    }
    if (null === $and || $and === $glue) {
        return \implode($glue, $value);
    }
    if (1 === \count($value)) {
        return $value[0];
    }
    return \implode($glue, \array_slice($value, 0, -1)) . $and . $value[\count($value) - 1];
}
/**
 * Splits the string into an array.
 *
 *  {{ "one,two,three"|split(',') }}
 *  {# returns [one, two, three] #}
 *
 *  {{ "one,two,three,four,five"|split(',', 3) }}
 *  {# returns [one, two, "three,four,five"] #}
 *
 *  {{ "123"|split('') }}
 *  {# returns [1, 2, 3] #}
 *
 *  {{ "aabbcc"|split('', 2) }}
 *  {# returns [aa, bb, cc] #}
 *
 * @param string $value     A string
 * @param string $delimiter The delimiter
 * @param int    $limit     The limit
 *
 * @return array The split string as an array
 */
function twig_split_filter(\RS_Vendor\Twig\Environment $env, $value, $delimiter, $limit = null)
{
    if (\strlen($delimiter) > 0) {
        return null === $limit ? \explode($delimiter, $value) : \explode($delimiter, $value, $limit);
    }
    if ($limit <= 1) {
        return \preg_split('/(?<!^)(?!$)/u', $value);
    }
    $length = \mb_strlen($value, $env->getCharset());
    if ($length < $limit) {
        return [$value];
    }
    $r = [];
    for ($i = 0; $i < $length; $i += $limit) {
        $r[] = \mb_substr($value, $i, $limit, $env->getCharset());
    }
    return $r;
}
// The '_default' filter is used internally to avoid using the ternary operator
// which costs a lot for big contexts (before PHP 5.4). So, on average,
// a function call is cheaper.
/**
 * @internal
 */
function _twig_default_filter($value, $default = '')
{
    if (\RS_Vendor\twig_test_empty($value)) {
        return $default;
    }
    return $value;
}
/**
 * Returns the keys for the given array.
 *
 * It is useful when you want to iterate over the keys of an array:
 *
 *  {% for key in array|keys %}
 *      {# ... #}
 *  {% endfor %}
 *
 * @param array $array An array
 *
 * @return array The keys
 */
function twig_get_array_keys_filter($array)
{
    if ($array instanceof \Traversable) {
        while ($array instanceof \IteratorAggregate) {
            $array = $array->getIterator();
        }
        if ($array instanceof \Iterator) {
            $keys = [];
            $array->rewind();
            while ($array->valid()) {
                $keys[] = $array->key();
                $array->next();
            }
            return $keys;
        }
        $keys = [];
        foreach ($array as $key => $item) {
            $keys[] = $key;
        }
        return $keys;
    }
    if (!\is_array($array)) {
        return [];
    }
    return \array_keys($array);
}
/**
 * Reverses a variable.
 *
 * @param array|\Traversable|string $item         An array, a \Traversable instance, or a string
 * @param bool                      $preserveKeys Whether to preserve key or not
 *
 * @return mixed The reversed input
 */
function twig_reverse_filter(\RS_Vendor\Twig\Environment $env, $item, $preserveKeys = \false)
{
    if ($item instanceof \Traversable) {
        return \array_reverse(\iterator_to_array($item), $preserveKeys);
    }
    if (\is_array($item)) {
        return \array_reverse($item, $preserveKeys);
    }
    $string = (string) $item;
    $charset = $env->getCharset();
    if ('UTF-8' !== $charset) {
        $item = \iconv($charset, 'UTF-8', $string);
    }
    \preg_match_all('/./us', $item, $matches);
    $string = \implode('', \array_reverse($matches[0]));
    if ('UTF-8' !== $charset) {
        $string = \iconv('UTF-8', $charset, $string);
    }
    return $string;
}
/**
 * Sorts an array.
 *
 * @param array|\Traversable $array
 *
 * @return array
 */
function twig_sort_filter($array, $arrow = null)
{
    if ($array instanceof \Traversable) {
        $array = \iterator_to_array($array);
    } elseif (!\is_array($array)) {
        throw new \RS_Vendor\Twig\Error\RuntimeError(\sprintf('The sort filter only works with arrays or "Traversable", got "%s".', \gettype($array)));
    }
    if (null !== $arrow) {
        \uasort($array, $arrow);
    } else {
        \asort($array);
    }
    return $array;
}
/**
 * @internal
 */
function twig_in_filter($value, $compare)
{
    if ($value instanceof \RS_Vendor\Twig\Markup) {
        $value = (string) $value;
    }
    if ($compare instanceof \RS_Vendor\Twig\Markup) {
        $compare = (string) $compare;
    }
    if (\is_array($compare)) {
        return \in_array($value, $compare, \is_object($value) || \is_resource($value));
    } elseif (\is_string($compare) && (\is_string($value) || \is_int($value) || \is_float($value))) {
        return '' === $value || \false !== \strpos($compare, (string) $value);
    } elseif ($compare instanceof \Traversable) {
        if (\is_object($value) || \is_resource($value)) {
            foreach ($compare as $item) {
                if ($item === $value) {
                    return \true;
                }
            }
        } else {
            foreach ($compare as $item) {
                if ($item == $value) {
                    return \true;
                }
            }
        }
        return \false;
    }
    return \false;
}
/**
 * Returns a trimmed string.
 *
 * @return string
 *
 * @throws RuntimeError When an invalid trimming side is used (not a string or not 'left', 'right', or 'both')
 */
function twig_trim_filter($string, $characterMask = null, $side = 'both')
{
    if (null === $characterMask) {
        $characterMask = " \t\n\r\0\v";
    }
    switch ($side) {
        case 'both':
            return \trim($string, $characterMask);
        case 'left':
            return \ltrim($string, $characterMask);
        case 'right':
            return \rtrim($string, $characterMask);
        default:
            throw new \RS_Vendor\Twig\Error\RuntimeError('Trimming side must be "left", "right" or "both".');
    }
}
/**
 * Removes whitespaces between HTML tags.
 *
 * @return string
 */
function twig_spaceless($content)
{
    return \trim(\preg_replace('/>\\s+</', '><', $content));
}
function twig_convert_encoding($string, $to, $from)
{
    return \iconv($from, $to, $string);
}
/**
 * Returns the length of a variable.
 *
 * @param mixed $thing A variable
 *
 * @return int The length of the value
 */
function twig_length_filter(\RS_Vendor\Twig\Environment $env, $thing)
{
    if (null === $thing) {
        return 0;
    }
    if (\is_scalar($thing)) {
        return \mb_strlen($thing, $env->getCharset());
    }
    if ($thing instanceof \Countable || \is_array($thing) || $thing instanceof \SimpleXMLElement) {
        return \count($thing);
    }
    if ($thing instanceof \Traversable) {
        return \iterator_count($thing);
    }
    if (\method_exists($thing, '__toString') && !$thing instanceof \Countable) {
        return \mb_strlen((string) $thing, $env->getCharset());
    }
    return 1;
}
/**
 * Converts a string to uppercase.
 *
 * @param string $string A string
 *
 * @return string The uppercased string
 */
function twig_upper_filter(\RS_Vendor\Twig\Environment $env, $string)
{
    return \mb_strtoupper($string, $env->getCharset());
}
/**
 * Converts a string to lowercase.
 *
 * @param string $string A string
 *
 * @return string The lowercased string
 */
function twig_lower_filter(\RS_Vendor\Twig\Environment $env, $string)
{
    return \mb_strtolower($string, $env->getCharset());
}
/**
 * Returns a titlecased string.
 *
 * @param string $string A string
 *
 * @return string The titlecased string
 */
function twig_title_string_filter(\RS_Vendor\Twig\Environment $env, $string)
{
    if (null !== ($charset = $env->getCharset())) {
        return \mb_convert_case($string, \MB_CASE_TITLE, $charset);
    }
    return \ucwords(\strtolower($string));
}
/**
 * Returns a capitalized string.
 *
 * @param string $string A string
 *
 * @return string The capitalized string
 */
function twig_capitalize_string_filter(\RS_Vendor\Twig\Environment $env, $string)
{
    $charset = $env->getCharset();
    return \mb_strtoupper(\mb_substr($string, 0, 1, $charset), $charset) . \mb_strtolower(\mb_substr($string, 1, null, $charset), $charset);
}
/**
 * @internal
 */
function twig_call_macro(\RS_Vendor\Twig\Template $template, string $method, array $args, int $lineno, array $context, \RS_Vendor\Twig\Source $source)
{
    if (!\method_exists($template, $method)) {
        $parent = $template;
        while ($parent = $parent->getParent($context)) {
            if (\method_exists($parent, $method)) {
                return $parent->{$method}(...$args);
            }
        }
        throw new \RS_Vendor\Twig\Error\RuntimeError(\sprintf('Macro "%s" is not defined in template "%s".', \substr($method, \strlen('macro_')), $template->getTemplateName()), $lineno, $source);
    }
    return $template->{$method}(...$args);
}
/**
 * @internal
 */
function twig_ensure_traversable($seq)
{
    if ($seq instanceof \Traversable || \is_array($seq)) {
        return $seq;
    }
    return [];
}
/**
 * @internal
 */
function twig_to_array($seq, $preserveKeys = \true)
{
    if ($seq instanceof \Traversable) {
        return \iterator_to_array($seq, $preserveKeys);
    }
    if (!\is_array($seq)) {
        return $seq;
    }
    return $preserveKeys ? $seq : \array_values($seq);
}
/**
 * Checks if a variable is empty.
 *
 *    {# evaluates to true if the foo variable is null, false, or the empty string #}
 *    {% if foo is empty %}
 *        {# ... #}
 *    {% endif %}
 *
 * @param mixed $value A variable
 *
 * @return bool true if the value is empty, false otherwise
 */
function twig_test_empty($value)
{
    if ($value instanceof \Countable) {
        return 0 == \count($value);
    }
    if ($value instanceof \Traversable) {
        return !\iterator_count($value);
    }
    if (\is_object($value) && \method_exists($value, '__toString')) {
        return '' === (string) $value;
    }
    return '' === $value || \false === $value || null === $value || [] === $value;
}
/**
 * Checks if a variable is traversable.
 *
 *    {# evaluates to true if the foo variable is an array or a traversable object #}
 *    {% if foo is iterable %}
 *        {# ... #}
 *    {% endif %}
 *
 * @param mixed $value A variable
 *
 * @return bool true if the value is traversable
 */
function twig_test_iterable($value)
{
    return $value instanceof \Traversable || \is_array($value);
}
/**
 * Renders a template.
 *
 * @param array        $context
 * @param string|array $template      The template to render or an array of templates to try consecutively
 * @param array        $variables     The variables to pass to the template
 * @param bool         $withContext
 * @param bool         $ignoreMissing Whether to ignore missing templates or not
 * @param bool         $sandboxed     Whether to sandbox the template or not
 *
 * @return string The rendered template
 */
function twig_include(\RS_Vendor\Twig\Environment $env, $context, $template, $variables = [], $withContext = \true, $ignoreMissing = \false, $sandboxed = \false)
{
    $alreadySandboxed = \false;
    $sandbox = null;
    if ($withContext) {
        $variables = \array_merge($context, $variables);
    }
    if ($isSandboxed = $sandboxed && $env->hasExtension(\RS_Vendor\Twig\Extension\SandboxExtension::class)) {
        $sandbox = $env->getExtension(\RS_Vendor\Twig\Extension\SandboxExtension::class);
        if (!($alreadySandboxed = $sandbox->isSandboxed())) {
            $sandbox->enableSandbox();
        }
    }
    try {
        $loaded = null;
        try {
            $loaded = $env->resolveTemplate($template);
        } catch (\RS_Vendor\Twig\Error\LoaderError $e) {
            if (!$ignoreMissing) {
                throw $e;
            }
        }
        return $loaded ? $loaded->render($variables) : '';
    } finally {
        if ($isSandboxed && !$alreadySandboxed) {
            $sandbox->disableSandbox();
        }
    }
}
/**
 * Returns a template content without rendering it.
 *
 * @param string $name          The template name
 * @param bool   $ignoreMissing Whether to ignore missing templates or not
 *
 * @return string The template source
 */
function twig_source(\RS_Vendor\Twig\Environment $env, $name, $ignoreMissing = \false)
{
    $loader = $env->getLoader();
    try {
        return $loader->getSourceContext($name)->getCode();
    } catch (\RS_Vendor\Twig\Error\LoaderError $e) {
        if (!$ignoreMissing) {
            throw $e;
        }
    }
}
/**
 * Provides the ability to get constants from instances as well as class/global constants.
 *
 * @param string      $constant The name of the constant
 * @param object|null $object   The object to get the constant from
 *
 * @return string
 */
function twig_constant($constant, $object = null)
{
    if (null !== $object) {
        $constant = \get_class($object) . '::' . $constant;
    }
    return \constant($constant);
}
/**
 * Checks if a constant exists.
 *
 * @param string      $constant The name of the constant
 * @param object|null $object   The object to get the constant from
 *
 * @return bool
 */
function twig_constant_is_defined($constant, $object = null)
{
    if (null !== $object) {
        $constant = \get_class($object) . '::' . $constant;
    }
    return \defined($constant);
}
/**
 * Batches item.
 *
 * @param array $items An array of items
 * @param int   $size  The size of the batch
 * @param mixed $fill  A value used to fill missing items
 *
 * @return array
 */
function twig_array_batch($items, $size, $fill = null, $preserveKeys = \true)
{
    if (!\RS_Vendor\twig_test_iterable($items)) {
        throw new \RS_Vendor\Twig\Error\RuntimeError(\sprintf('The "batch" filter expects an array or "Traversable", got "%s".', \is_object($items) ? \get_class($items) : \gettype($items)));
    }
    $size = \ceil($size);
    $result = \array_chunk(\RS_Vendor\twig_to_array($items, $preserveKeys), $size, $preserveKeys);
    if (null !== $fill && $result) {
        $last = \count($result) - 1;
        if ($fillCount = $size - \count($result[$last])) {
            for ($i = 0; $i < $fillCount; ++$i) {
                $result[$last][] = $fill;
            }
        }
    }
    return $result;
}
/**
 * Returns the attribute value for a given array/object.
 *
 * @param mixed  $object            The object or array from where to get the item
 * @param mixed  $item              The item to get from the array or object
 * @param array  $arguments         An array of arguments to pass if the item is an object method
 * @param string $type              The type of attribute (@see \Twig\Template constants)
 * @param bool   $isDefinedTest     Whether this is only a defined check
 * @param bool   $ignoreStrictCheck Whether to ignore the strict attribute check or not
 * @param int    $lineno            The template line where the attribute was called
 *
 * @return mixed The attribute value, or a Boolean when $isDefinedTest is true, or null when the attribute is not set and $ignoreStrictCheck is true
 *
 * @throws RuntimeError if the attribute does not exist and Twig is running in strict mode and $isDefinedTest is false
 *
 * @internal
 */
function twig_get_attribute(\RS_Vendor\Twig\Environment $env, \RS_Vendor\Twig\Source $source, $object, $item, array $arguments = [], $type = 'any', $isDefinedTest = \false, $ignoreStrictCheck = \false, $sandboxed = \false, int $lineno = -1)
{
    // array
    if ('method' !== $type) {
        $arrayItem = \is_bool($item) || \is_float($item) ? (int) $item : $item;
        if ((\is_array($object) || $object instanceof \ArrayObject) && (isset($object[$arrayItem]) || \array_key_exists($arrayItem, (array) $object)) || $object instanceof \ArrayAccess && isset($object[$arrayItem])) {
            if ($isDefinedTest) {
                return \true;
            }
            return $object[$arrayItem];
        }
        if ('array' === $type || !\is_object($object)) {
            if ($isDefinedTest) {
                return \false;
            }
            if ($ignoreStrictCheck || !$env->isStrictVariables()) {
                return;
            }
            if ($object instanceof \ArrayAccess) {
                $message = \sprintf('Key "%s" in object with ArrayAccess of class "%s" does not exist.', $arrayItem, \get_class($object));
            } elseif (\is_object($object)) {
                $message = \sprintf('Impossible to access a key "%s" on an object of class "%s" that does not implement ArrayAccess interface.', $item, \get_class($object));
            } elseif (\is_array($object)) {
                if (empty($object)) {
                    $message = \sprintf('Key "%s" does not exist as the array is empty.', $arrayItem);
                } else {
                    $message = \sprintf('Key "%s" for array with keys "%s" does not exist.', $arrayItem, \implode(', ', \array_keys($object)));
                }
            } elseif ('array' === $type) {
                if (null === $object) {
                    $message = \sprintf('Impossible to access a key ("%s") on a null variable.', $item);
                } else {
                    $message = \sprintf('Impossible to access a key ("%s") on a %s variable ("%s").', $item, \gettype($object), $object);
                }
            } elseif (null === $object) {
                $message = \sprintf('Impossible to access an attribute ("%s") on a null variable.', $item);
            } else {
                $message = \sprintf('Impossible to access an attribute ("%s") on a %s variable ("%s").', $item, \gettype($object), $object);
            }
            throw new \RS_Vendor\Twig\Error\RuntimeError($message, $lineno, $source);
        }
    }
    if (!\is_object($object)) {
        if ($isDefinedTest) {
            return \false;
        }
        if ($ignoreStrictCheck || !$env->isStrictVariables()) {
            return;
        }
        if (null === $object) {
            $message = \sprintf('Impossible to invoke a method ("%s") on a null variable.', $item);
        } elseif (\is_array($object)) {
            $message = \sprintf('Impossible to invoke a method ("%s") on an array.', $item);
        } else {
            $message = \sprintf('Impossible to invoke a method ("%s") on a %s variable ("%s").', $item, \gettype($object), $object);
        }
        throw new \RS_Vendor\Twig\Error\RuntimeError($message, $lineno, $source);
    }
    if ($object instanceof \RS_Vendor\Twig\Template) {
        throw new \RS_Vendor\Twig\Error\RuntimeError('Accessing \\Twig\\Template attributes is forbidden.', $lineno, $source);
    }
    // object property
    if ('method' !== $type) {
        if (isset($object->{$item}) || \array_key_exists((string) $item, (array) $object)) {
            if ($isDefinedTest) {
                return \true;
            }
            if ($sandboxed) {
                $env->getExtension(\RS_Vendor\Twig\Extension\SandboxExtension::class)->checkPropertyAllowed($object, $item, $lineno, $source);
            }
            return $object->{$item};
        }
    }
    static $cache = [];
    $class = \get_class($object);
    // object method
    // precedence: getXxx() > isXxx() > hasXxx()
    if (!isset($cache[$class])) {
        $methods = \get_class_methods($object);
        \sort($methods);
        $lcMethods = \array_map('strtolower', $methods);
        $classCache = [];
        foreach ($methods as $i => $method) {
            $classCache[$method] = $method;
            $classCache[$lcName = $lcMethods[$i]] = $method;
            if ('g' === $lcName[0] && 0 === \strpos($lcName, 'get')) {
                $name = \substr($method, 3);
                $lcName = \substr($lcName, 3);
            } elseif ('i' === $lcName[0] && 0 === \strpos($lcName, 'is')) {
                $name = \substr($method, 2);
                $lcName = \substr($lcName, 2);
            } elseif ('h' === $lcName[0] && 0 === \strpos($lcName, 'has')) {
                $name = \substr($method, 3);
                $lcName = \substr($lcName, 3);
                if (\in_array('is' . $lcName, $lcMethods)) {
                    continue;
                }
            } else {
                continue;
            }
            // skip get() and is() methods (in which case, $name is empty)
            if ($name) {
                if (!isset($classCache[$name])) {
                    $classCache[$name] = $method;
                }
                if (!isset($classCache[$lcName])) {
                    $classCache[$lcName] = $method;
                }
            }
        }
        $cache[$class] = $classCache;
    }
    $call = \false;
    if (isset($cache[$class][$item])) {
        $method = $cache[$class][$item];
    } elseif (isset($cache[$class][$lcItem = \strtolower($item)])) {
        $method = $cache[$class][$lcItem];
    } elseif (isset($cache[$class]['__call'])) {
        $method = $item;
        $call = \true;
    } else {
        if ($isDefinedTest) {
            return \false;
        }
        if ($ignoreStrictCheck || !$env->isStrictVariables()) {
            return;
        }
        throw new \RS_Vendor\Twig\Error\RuntimeError(\sprintf('Neither the property "%1$s" nor one of the methods "%1$s()", "get%1$s()"/"is%1$s()"/"has%1$s()" or "__call()" exist and have public access in class "%2$s".', $item, $class), $lineno, $source);
    }
    if ($isDefinedTest) {
        return \true;
    }
    if ($sandboxed) {
        $env->getExtension(\RS_Vendor\Twig\Extension\SandboxExtension::class)->checkMethodAllowed($object, $method, $lineno, $source);
    }
    // Some objects throw exceptions when they have __call, and the method we try
    // to call is not supported. If ignoreStrictCheck is true, we should return null.
    try {
        $ret = $object->{$method}(...$arguments);
    } catch (\BadMethodCallException $e) {
        if ($call && ($ignoreStrictCheck || !$env->isStrictVariables())) {
            return;
        }
        throw $e;
    }
    return $ret;
}
/**
 * Returns the values from a single column in the input array.
 *
 * <pre>
 *  {% set items = [{ 'fruit' : 'apple'}, {'fruit' : 'orange' }] %}
 *
 *  {% set fruits = items|column('fruit') %}
 *
 *  {# fruits now contains ['apple', 'orange'] #}
 * </pre>
 *
 * @param array|Traversable $array An array
 * @param mixed             $name  The column name
 * @param mixed             $index The column to use as the index/keys for the returned array
 *
 * @return array The array of values
 */
function twig_array_column($array, $name, $index = null) : array
{
    if ($array instanceof \Traversable) {
        $array = \iterator_to_array($array);
    } elseif (!\is_array($array)) {
        throw new \RS_Vendor\Twig\Error\RuntimeError(\sprintf('The column filter only works with arrays or "Traversable", got "%s" as first argument.', \gettype($array)));
    }
    return \array_column($array, $name, $index);
}
function twig_array_filter($array, $arrow)
{
    if (\is_array($array)) {
        return \array_filter($array, $arrow, \ARRAY_FILTER_USE_BOTH);
    }
    // the IteratorIterator wrapping is needed as some internal PHP classes are \Traversable but do not implement \Iterator
    return new \CallbackFilterIterator(new \IteratorIterator($array), $arrow);
}
function twig_array_map($array, $arrow)
{
    $r = [];
    foreach ($array as $k => $v) {
        $r[$k] = $arrow($v, $k);
    }
    return $r;
}
function twig_array_reduce($array, $arrow, $initial = null)
{
    if (!\is_array($array)) {
        $array = \iterator_to_array($array);
    }
    return \array_reduce($array, $arrow, $initial);
}
