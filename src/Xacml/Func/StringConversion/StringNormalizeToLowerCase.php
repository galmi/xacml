<?php

namespace Galmi\Xacml\Func\StringConversion;


use Galmi\Xacml\Func\FuncInterface;

class StringNormalizeToLowerCase implements FuncInterface
{

    /**
     * @inheritdoc
     */
    public function evaluate(array $values)
    {
        if (count($values) != 1) {
            throw new \InvalidArgumentException('StringEqual function must contains only 2 parameters');
        }

        return mb_convert_case($values[0], MB_CASE_LOWER);
    }
}