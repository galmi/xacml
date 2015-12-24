<?php

namespace Galmi\Xacml;


use Galmi\Xacml\Func\FuncInterface;

/**
 * Factory class for retrieve function object from functionId
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class FuncFactory
{
    use UtilsTrait;

    /**
     * Retrieve function object from functionId
     *
     * @param $functionId
     * @return FuncInterface
     * @throws Exception\FunctionNotFoundException
     */
    public function getFunction($functionId)
    {
        $funcName = '\\Galmi\\Xacml\\Func\\' . $this->camelCase($functionId);
        if (!class_exists($funcName)) {
            throw new Exception\FunctionNotFoundException("Class {$funcName} not found");
        }
        return new $funcName();
    }

}