<?php

namespace Galmi\Xacml\Func\Equality;


use Galmi\Xacml\Func\AbstractEquality;

/**
 * This function SHALL take two arguments
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class DateTimeEqual extends AbstractEquality
{

    /**
     * @inheritdoc
     * @return \DateTime
     */
    protected function bringType($value)
    {
        if ($value instanceof \DateTime) {
            return $value;
        }
        return new \DateTime($value);
    }
}