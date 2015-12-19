<?php

namespace Galmi\Xacml;


interface FunctionInterface
{
    /**
     * @param $value1
     * @param $value2
     * @return boolean
     */
    public function evaluate($value1, $value2);
}