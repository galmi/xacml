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

    /**
     * Convert spec characters (e.g. "-", "_") to camel case
     *
     * @param $str
     * @return string
     */
    protected function camelCase($str)
    {
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);
        $str = str_replace(" ", "", $str);

        return $str;
    }
}