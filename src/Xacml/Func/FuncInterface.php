<?php

namespace Galmi\Xacml\Func;

/**
 * Interface for function classes
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
interface FuncInterface
{
    /**
     * Evaluate function value
     *
     * @param array $values
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function evaluate(array $values);
}