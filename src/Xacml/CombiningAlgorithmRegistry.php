<?php

namespace Galmi\Xacml;

/**
 * Registry class for retrieve algorithm object from combiningAlgorithmId
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */

class CombiningAlgorithmRegistry
{
    const DENY_OVERRIDES = 'deny-overrides';
    const DENY_UNLESS_PERMIT = 'deny-unless-permit';
    const FIRST_APPLICABLE = 'first-applicable';
    const ONLY_ONE_APPLICABLE = 'only-one-applicable';
    const PERMIT_OVERRIDES = 'permit-overrides';
    const PERMIT_UNLESS_DENY = 'permit-unless-deny';

    private $algorithms = array();

    public function __construct()
    {
        $this->algorithms = [
            self::DENY_OVERRIDES => CombiningAlgorithm\DenyOverrides::class,
            self::DENY_UNLESS_PERMIT => CombiningAlgorithm\DenyUnlessPermit::class,
            self::FIRST_APPLICABLE => CombiningAlgorithm\FirstApplicable::class,
            self::ONLY_ONE_APPLICABLE => CombiningAlgorithm\OnlyOneApplicable::class,
            self::PERMIT_OVERRIDES => CombiningAlgorithm\PermitOverrides::class,
            self::PERMIT_UNLESS_DENY => CombiningAlgorithm\PermitUnlessDeny::class,
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