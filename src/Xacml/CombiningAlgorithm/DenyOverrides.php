<?php

namespace Galmi\Xacml\CombiningAlgorithm;

use Galmi\Xacml\Decision;
use Galmi\Xacml\Request;

/**
 * The “Deny-overrides” is rule-combining algorithm of a policy and policy-combining algorithm of a policy set.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class DenyOverrides implements AlgorithmInterface
{

    /**
     * The deny overrides combining algorithm is intended for those cases
     * where a deny decision should have priority over a permit decision. This algorithm has the following behavior.
     * 1.     If any decision is "Deny", the result is "Deny".
     * 2.     Otherwise, if any decision is "Indeterminate{DP}", the result is "Indeterminate{DP}".
     * 3.     Otherwise, if any decision is "Indeterminate{D}" and another decision is “Indeterminate{P} or Permit,
     *        the result is "Indeterminate{DP}".
     * 4.     Otherwise, if any decision is "Indeterminate{D}", the result is "Indeterminate{D}".
     * 5.     Otherwise, if any decision is "Permit", the result is "Permit".
     * 6.     Otherwise, if any decision is "Indeterminate{P}", the result is "Indeterminate{P}".
     * 7.     Otherwise, the result is "NotApplicable".
     *
     * @param Request $request
     * @param \Galmi\Xacml\Policy[]|\Galmi\Xacml\PolicySet[]|\Galmi\Xacml\Rule[] $items
     * @return string
     */
    public function evaluate(Request $request, array $items)
    {
        $atLeastOneErrorD = false;
        $atLeastOneErrorP = false;
        $atLeastOneErrorDP = false;
        $atLeastOnePermit = false;

        foreach ($items as $item) {
            $decision = $item->evaluate($request);
            if ($decision == Decision::DENY) {
                return Decision::DENY;
            }
            if ($decision == Decision::PERMIT) {
                $atLeastOnePermit = true;
                continue;
            }
            if ($decision == Decision::NOT_APPLICABLE) {
                continue;
            }
            if ($decision == Decision::INDETERMINATE_D) {
                $atLeastOneErrorD = true;
                continue;
            }
            if ($decision == Decision::INDETERMINATE_P) {
                $atLeastOneErrorP = true;
                continue;
            }
            if ($decision == Decision::INDETERMINATE_D_P) {
                $atLeastOneErrorDP = true;
                continue;
            }
        }
        if ($atLeastOneErrorDP) {
            return Decision::INDETERMINATE_D_P;
        }

        if ($atLeastOneErrorD && ($atLeastOneErrorP || $atLeastOnePermit)) {
            return Decision::INDETERMINATE_D_P;
        }
        if ($atLeastOneErrorD) {
            return Decision::INDETERMINATE_D;
        }
        if ($atLeastOnePermit) {
            return Decision::PERMIT;
        }
        if ($atLeastOneErrorP) {
            return Decision::INDETERMINATE_P;
        }

        return Decision::NOT_APPLICABLE;
    }
}