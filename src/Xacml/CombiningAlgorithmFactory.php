<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 23.12.15
 * Time: 14:00
 */

namespace Galmi\Xacml;

/**
 * Factory class for retrieve algorithm object from combiningAlgorithmId
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */

class CombiningAlgorithmFactory
{
    use UtilsTrait;

    /**
     * Retrieve function object from combiningAlgorithmId
     *
     * @param $combiningAlgorithmId
     * @return CombiningAlgorithm\AlgorithmInterface
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

}