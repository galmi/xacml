<?php

namespace Galmi\Xacml\CombiningAlgorithm;

use Galmi\Xacml\Request;


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
     * @param Request $request
     * @param \Galmi\Xacml\Policy[]|\Galmi\Xacml\PolicySet[]|\Galmi\Xacml\Rule[] $items
     * @return string
     */
    public function evaluate(Request $request, array $items);
}