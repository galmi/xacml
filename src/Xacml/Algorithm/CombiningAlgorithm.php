<?php

namespace Galmi\Xacml\Algorithm;


use Galmi\Xacml\Rule;

interface CombiningAlgorithm
{
    /**
     * @param Rule[] $rules
     * @return string
     */
    public function evaluate(array $rules);
}