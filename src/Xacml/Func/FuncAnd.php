<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 20.12.15
 * Time: 22:21
 */

namespace Galmi\Xacml\Func;


class FuncAnd implements FuncInterface
{

    /**
     * @param array $values
     * @return mixed
     */
    public function evaluate(array $values)
    {
        $result = true;
        foreach ($values as $value) {
            if (gettype($value) != 'boolean') {
                throw new \InvalidArgumentException("All values must be boolean type");
            }
            $result = $result && $value;
        }

        return $result;
    }
}