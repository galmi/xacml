<?php

namespace Galmi\Xacml\Func\Equality;


use Galmi\Xacml\Func\AbstractEquality;

/**
 * This function SHALL take two arguments
 * The function SHALL return “True” if and only if the two arguments represent the same number.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class IntegerEqual extends AbstractEquality
{

    /**
     * @inheritdoc
     */
    protected function bringType($value)
    {
        return (int)$value;
    }
}