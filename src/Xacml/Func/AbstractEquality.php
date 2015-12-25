<?php

namespace Galmi\Xacml\Func;


abstract class AbstractEquality implements FuncInterface
{
    /**
     * Convert $value to specific type
     *
     * @param $value
     * @return mixed
     */
    protected abstract function bringType($value);

    /**
     * @param array $values
     * @return bool
     */
    public function evaluate(array $values)
    {
        if (count($values) != 2) {
            throw new \InvalidArgumentException('StringEqual function must contains only 2 parameters');
        }

        return $this->bringType($values[0]) === $this->bringType($values[1]);
    }
}