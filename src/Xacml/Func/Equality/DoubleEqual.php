<?php

namespace Galmi\Xacml\Func\Equality;


use Galmi\Xacml\Func\AbstractEquality;

/**
 * This function SHALL take two arguments
 * It SHALL perform its evaluation on doubles according to IEEE 754
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class DoubleEqual extends AbstractEquality
{

    /**
     * @inheritdoc
     */
    protected function bringType($value)
    {
        return (double)$value;
    }
}