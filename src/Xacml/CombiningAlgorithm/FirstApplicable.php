<?php

namespace Galmi\Xacml\CombiningAlgorithm;

use Galmi\Xacml\Decision;
use Galmi\Xacml\Request;

/**
 * The “First-applicable” rule-combining algorithm of a policy and policy-combining algorithm of a policy set.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class FirstApplicable implements AlgorithmInterface
{

    /**
     * @param Request $request
     * @param \Galmi\Xacml\Policy[]|\Galmi\Xacml\PolicySet[]|\Galmi\Xacml\Rule[] $items
     * @return string
     */
    public function evaluate(Request $request, array $items)
    {
        foreach ($items as $item) {
            $decision = $item->evaluate($request);
            if ($decision != Decision::NOT_APPLICABLE) {
                return $decision;
            }
        }

        return Decision::NOT_APPLICABLE;
    }
}