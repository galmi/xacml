<?php

namespace Galmi\Xacml\Func\Logical;

use Galmi\Xacml\Func\FuncInterface;

/**
 * Making OR operation for boolean type values
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class FuncOr implements FuncInterface
{

    /**
     * @inheritdoc
     */
    public function evaluate(array $values)
    {
        $result = true;
        foreach ($values as $value) {
            if (gettype($value) != 'boolean') {
                throw new \InvalidArgumentException("All values must be boolean type");
            }
            $result = $result || $value;
        }

        return $result;
    }
}