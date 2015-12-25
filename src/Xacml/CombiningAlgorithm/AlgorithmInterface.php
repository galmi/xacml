<?php

namespace Galmi\Xacml\CombiningAlgorithm;


/**
 * Interface for combining algorithm classes
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
interface AlgorithmInterface
{
    /**
     * Evaluate decision of algorithm
     *
     * @param \Galmi\Xacml\Policy[]|\Galmi\Xacml\PolicySet[]|\Galmi\Xacml\Rule[] $items
     * @return string
     */
    public function evaluate(array $items);
}