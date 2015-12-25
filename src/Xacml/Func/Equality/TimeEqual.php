<?php

namespace Galmi\Xacml\Func\Equality;


use Galmi\Xacml\Func\AbstractEquality;

/**
 * This function SHALL take two arguments
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class TimeEqual extends AbstractEquality
{

    /**
     * @inheritdoc
     */
    protected function bringType($value)
    {
        $dateTimeValue = new \DateTime($value);
        return $dateTimeValue->format('H:i:s');
    }
}