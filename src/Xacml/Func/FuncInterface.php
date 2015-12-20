<?php

namespace Galmi\Xacml\Func;


interface FuncInterface
{
    /**
     * @param array $values
     * @return mixed
     */
    public function evaluate(array $values);
}