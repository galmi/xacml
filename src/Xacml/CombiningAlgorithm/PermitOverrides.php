<?php

namespace Galmi\Xacml\CombiningAlgorithm;


use Galmi\Xacml\Decision;
use Galmi\Xacml\Request;

/**
 * The “Permit-overrides” rule-combining algorithm of a policy and policy-combining algorithm of a policy set.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class PermitOverrides implements AlgorithmInterface
{

    /**
     * The permit overrides combining algorithm is intended for those cases
     * where a permit decision should have priority over a deny decision. This algorithm has the following behavior.
     * 1.     If any decision is "Permit", the result is "Permit".
     * 2.     Otherwise, if any decision is "Indeterminate{DP}", the result is "Indeterminate{DP}".
     * 3.     Otherwise, if any decision is "Indeterminate{P}" and another decision is “Indeterminate{D} or Deny,
     *        the result is "Indeterminate{DP}".
     * 4.     Otherwise, if any decision is "Indeterminate{P}", the result is "Indeterminate{P}".
     * 5.     Otherwise, if any decision is "Deny", the result is "Deny".
     * 6.     Otherwise, if any decision is "Indeterminate{D}", the result is "Indeterminate{D}".
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
        $atLeastOneDeny = false;
        foreach ($items as $item) {
            $decision = $item->evaluate($request);
            if ($decision == Decision::DENY) {
                $atLeastOneDeny = true;
                continue;
            }
            if ($decision == Decision::PERMIT) {
                return Decision::PERMIT;
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
        if ($atLeastOneErrorP && ($atLeastOneErrorD || $atLeastOneDeny)) {
            return Decision::INDETERMINATE_D_P;
        }
        if ($atLeastOneErrorP) {
            return Decision::INDETERMINATE_P;
        }
        if ($atLeastOneDeny) {
            return Decision::DENY;
        }
        if ($atLeastOneErrorD) {
            return Decision::INDETERMINATE_D;
        }

        return Decision::NOT_APPLICABLE;
    }
}