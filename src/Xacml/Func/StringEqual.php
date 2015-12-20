<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 20.12.15
 * Time: 17:26
 */

namespace Galmi\Xacml\Func;


class StringEqual implements FuncInterface
{
    /**
     * @param array $values
     * @return bool
     */
    public function evaluate(array $values)
    {
        if (count($values) != 2) {
            throw new \InvalidArgumentException('StringEqual must contains only 2 parameters');
        }
        return $values[0] == $values[1];
    }
}