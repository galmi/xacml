<?php

namespace Galmi\Xacml\CombiningAlgorithm;

use Galmi\Xacml\Decision;
use Galmi\Xacml\Request;

/**
 * The “Permit-unless-deny” rule-combining algorithm of a policy or policy-combining algorithm of a policy set.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class PermitUnlessDeny implements AlgorithmInterface
{

    /**
     * This algorithm has the following behavior.
     * 1.     If any decision is "Deny", the result is "Deny".
     * 2.     Otherwise, the result is "Permit".
     *
     * @param Request $request
     * @param \Galmi\Xacml\Policy[]|\Galmi\Xacml\PolicySet[]|\Galmi\Xacml\Rule[] $items
     * @return string
     */
    public function evaluate(Request $request, array $items)
    {
        foreach ($items as $item) {
            if ($item->evaluate($request) == Decision::DENY) {
                return Decision::DENY;
            }
        }

        return Decision::PERMIT;
    }
}