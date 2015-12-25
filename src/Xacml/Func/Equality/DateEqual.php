<?php

namespace Galmi\Xacml\Func\Equality;


use Galmi\Xacml\Func\AbstractEquality;

/**
 * This function SHALL take two arguments
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class DateEqual extends AbstractEquality
{

    /**
     * @inheritdoc
     */
    protected function bringType($value)
    {
        if ($value instanceof \DateTime) {
            $dateTimeValue = $value;
        } else {
            $dateTimeValue = new \DateTime($value);
        }
        $dateTimeValue->setTime(0, 0, 0);
        return $dateTimeValue;
    }
}