<?php

namespace Galmi\Xacml;

/**
 * Registry class for retrieve algorithm object from combiningAlgorithmId
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */

class CombiningAlgorithmRegistry
{
    use UtilsTrait;

    private $algorithms = array();

    public function __construct()
    {
        $this->algorithms = [
            'deny-overrides' => CombiningAlgorithm\DenyOverrides::class,
            'deny-unless-permit' => CombiningAlgorithm\DenyUnlessPermit::class,
            'first-applicable' => CombiningAlgorithm\FirstApplicable::class,
            'only-one-applicable' => CombiningAlgorithm\OnlyOneApplicable::class,
            'ordered-deny-overrides' => CombiningAlgorithm\OrderedDenyOverrides::class,
            'ordered-permit-overrides' => CombiningAlgorithm\OrderedPermitOverrides::class,
            'permit-overrides' => CombiningAlgorithm\PermitOverrides::class,
            'permit-unless-deny' => CombiningAlgorithm\PermitUnlessDeny::class,
        ];
    }

    /**
     * Retrieve combining algorithm object from combiningAlgorithmId
     *
     * @param $combiningAlgorithmId
     * @return CombiningAlgorithm\AlgorithmInterface
     * @throws Exception\AlgorithmNotFoundException
     */
    public function get($combiningAlgorithmId)
    {
        if (!isset($this->algorithms[$combiningAlgorithmId])) {
            throw new Exception\AlgorithmNotFoundException("Combining algorithm {$combiningAlgorithmId} not found");
        }
        $className = $this->algorithms[$combiningAlgorithmId];
        if (!class_exists($className)) {
            throw new Exception\AlgorithmNotFoundException("Class {$className} not found");
        }

        return new $className();
    }

    /**
     * Add algorithm to registry
     *
     * @param $combiningAlgorithmId
     * @param $className
     * @return $this
     */
    public function set($combiningAlgorithmId, $className)
    {
        $this->algorithms[$combiningAlgorithmId] = $className;

        return $this;
    }
}