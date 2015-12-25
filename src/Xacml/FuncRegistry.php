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
    use UtilsTrait;

    /**
     * List of functionId list and their class
     *
     * @var array
     */
    private $functions = array();

    public function __construct()
    {
        $this->functions = [
            'string-equal' => Func\Equality\StringEqual::class,
            'string-equal-ignore-case' => Func\Equality\StringEqualIgnoreCase::class,
            'boolean-equal' => Func\Equality\BooleanEqual::class,
            'integer-equal' => Func\Equality\IntegerEqual::class,
            'double-equal' => Func\Equality\DoubleEqual::class,
            'date-equal' => Func\Equality\DateEqual::class,
            'time-equal' => Func\Equality\TimeEqual::class,
            'dateTime-equal' => Func\Equality\DateTimeEqual::class,

            'string-normalize-to-lower-case' => Func\StringConversion\StringNormalizeToLowerCase::class,

            'and' => Func\Logical\FuncAnd::class,

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