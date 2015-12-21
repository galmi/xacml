<?php

namespace Galmi\Xacml\Func;

/**
 * Return True if two strings are equals
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class StringEqual implements FuncInterface
{
    /**
     * @inheritdoc
     */
    public function evaluate(array $values)
    {
        if (count($values) != 2) {
            throw new \InvalidArgumentException('StringEqual function must contains only 2 parameters');
        }
        return $values[0] == $values[1];
    }
}