<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 20.12.15
 * Time: 17:19
 */

namespace Galmi\Xacml;


use Galmi\Xacml\Func\FuncInterface;

class FuncFactory
{
    /**
     * @param $functionId
     * @return FuncInterface
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
     * @param $str
     * @param array $noStrip
     * @return mixed|string
     */
    protected function camelCase($str, array $noStrip = [])
    {
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);
        $wordsCount = str_word_count($str);
        $str = str_replace(" ", "", $str);

        return $str;
    }
}