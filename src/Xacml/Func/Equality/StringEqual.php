<?php

namespace Galmi\Xacml\Func\Equality;

use Galmi\Xacml\Func\AbstractEquality;

/**
 * This function SHALL take two arguments
 * Return True if two strings are equals
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class StringEqual extends AbstractEquality
{
    /**
     * @inheritdoc
     */
    protected function bringType($value)
    {
        return (string)$value;
    }
}