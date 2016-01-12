<?php

namespace Galmi\Xacml;


use Galmi\Xacml\Func\FuncInterface;

/**
 * Factory class for retrieve function object from functionId
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class FuncRegistry
{
    const STRING_EQUAL = 'string-equal';
    const STRING_EQUAL_IGNORE_CASE = 'string-equal-ignore-case';
    const BOOLEAN_EQUAL = 'boolean-equal';
    const INTEGER_EQUAL = 'integer-equal';
    const DOUBLE_EQUAL = 'double-equal';
    const DATE_EQUAL = 'date-equal';
    const TIME_EQUAL = 'time-equal';
    const DATE_TIME_EQUAL = 'date-time-equal';

    const STRING_NORMALIZE_TO_LOWER_CASE = 'string-normalize-to-lower-case';

    const FUNC_AND = 'and';

    /**
     * List of functionId list and their class
     *
     * @var array
     */
    private $functions = array();

    public function __construct()
    {
        $this->functions = [
            self::STRING_EQUAL => Func\Equality\StringEqual::class,
            self::STRING_EQUAL_IGNORE_CASE => Func\Equality\StringEqualIgnoreCase::class,
            self::BOOLEAN_EQUAL => Func\Equality\BooleanEqual::class,
            self::INTEGER_EQUAL => Func\Equality\IntegerEqual::class,
            self::DOUBLE_EQUAL => Func\Equality\DoubleEqual::class,
            self::DATE_EQUAL => Func\Equality\DateEqual::class,
            self::TIME_EQUAL => Func\Equality\TimeEqual::class,
            self::DATE_TIME_EQUAL => Func\Equality\DateTimeEqual::class,

            self::STRING_NORMALIZE_TO_LOWER_CASE => Func\StringConversion\StringNormalizeToLowerCase::class,

            self::FUNC_AND => Func\Logical\FuncAnd::class,

        ];
    }

    /**
     * @param $functionId
     * @param $className
     * @return $this
     */
    public function set($functionId, $className)
    {
        $this->functions[$functionId] = $className;

        return $this;
    }

    /**
     * Retrieve function object from functionId
     *
     * @param $functionId
     * @return FuncInterface
     * @throws Exception\FunctionNotFoundException
     */
    public function get($functionId)
    {
        if (!isset($this->functions[$functionId])) {
            throw new Exception\FunctionNotFoundException("Function {$functionId} not found");
        }
        $className = $this->functions[$functionId];
        if (!class_exists($className)) {
            throw new Exception\FunctionNotFoundException("Class {$className} not found");
        }

        return new $className();
    }

}