<?php

namespace Galmi\Xacml\Func\Equality;


use Galmi\Xacml\Func\AbstractEquality;

/**
 * This function SHALL take two arguments
 * The function SHALL return "True" if and only if the arguments are equal.  Otherwise, it SHALL return “False”.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class BooleanEqual extends AbstractEquality
{
    /**
     * @inheritdoc
     */
    protected function bringType($value)
    {
        return (bool)$value;
    }

}