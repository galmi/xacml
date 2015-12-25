<?php

namespace Galmi\Xacml\CombiningAlgorithm;

use Galmi\Xacml\Decision;
use Galmi\Xacml\Request;

/**
 * The “Deny-unless-permit” rule-combining algorithm of a policy or policy-combining algorithm of a policy set.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class DenyUnlessPermit implements AlgorithmInterface
{

    /**
     * This algorithm has the following behavior.
     * 1.     If any decision is "Permit", the result is "Permit".
     * 2.     Otherwise, the result is "Deny".
     *
     * @param Request $request
     * @param \Galmi\Xacml\Policy[]|\Galmi\Xacml\PolicySet[]|\Galmi\Xacml\Rule[] $items
     * @return string
     */
    public function evaluate(Request $request, array $items)
    {
        foreach($items as $item) {
            if ($item->evaluate($request) == Decision::PERMIT) {
                return Decision::PERMIT;
            }
        }

        return Decision::DENY;
    }
}