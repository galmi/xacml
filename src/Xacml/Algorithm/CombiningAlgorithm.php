<?php

namespace Galmi\Xacml\Algorithm;


use Galmi\Xacml\Rule;

/**
 * Interface for combining algorithm classes
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
interface CombiningAlgorithm
{
    /**
     * Evaluate decision of algorithm
     *
     * @param Rule[] $rules
     * @return string
     */
    public function evaluate(array $rules);
}