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
            try {
                $decision = $item->evaluate($request);
            } catch (\Exception $e) {
                return Decision::INDETERMINATE;
            }
            if ($decision == Decision::DENY) {
                return Decision::DENY;
            }
            if ($decision == Decision::PERMIT) {
                return Decision::PERMIT;
            }
            if ($decision == Decision::NOT_APPLICABLE) {
                continue;
            }
        }

        return Decision::NOT_APPLICABLE;
    }
}