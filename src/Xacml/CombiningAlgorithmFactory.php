<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 23.12.15
 * Time: 14:00
 */

namespace Galmi\Xacml;

use Galmi\Xacml\Algorithm\CombiningAlgorithmInterface;

/**
 * Factory class for retrieve algorithm object from combiningAlgorithmId
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */

class CombiningAlgorithmFactory
{
    /**
     * Retrieve function object from combiningAlgorithmId
     *
     * @param $combiningAlgorithmId
     * @return CombiningAlgorithmInterface
     * @throws Exception\FunctionNotFoundException
     */
    public function getCombiningAlgorithm($combiningAlgorithmId)
    {
        $combiningAlgorithmName = '\\Galmi\\Xacml\\Algorithm\\' . $this->camelCase($combiningAlgorithmId);
        if (!class_exists($combiningAlgorithmName)) {
            throw new Exception\FunctionNotFoundException("Class {$combiningAlgorithmName} not found");
        }
        return new $combiningAlgorithmName();
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