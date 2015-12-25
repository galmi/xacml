<?php

namespace Galmi\Xacml\CombiningAlgorithm;


use Galmi\Xacml\Decision;
use Galmi\Xacml\Request;

/**
 * The “Only-one-applicable” policy-combining algorithm of a policy set.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class OnlyOneApplicable implements AlgorithmInterface
{

    /**
     * In the entire set of policies in the policy set, if no policy is considered applicable by virtue of its target,
     * then the result of the policy-combination algorithm SHALL be "NotApplicable".
     * If more than one policy is considered applicable by virtue of its target,
     * then the result of the policy-combination algorithm SHALL be "Indeterminate".
     *
     * If only one policy is considered applicable by evaluation of its target,
     * then the result of the policy-combining algorithm SHALL be the result of evaluating the policy.
     *
     * If an error occurs while evaluating the target of a policy, or a reference to a policy is considered invalid or
     * the policy evaluation results in "Indeterminate, then the policy set SHALL evaluate to "Indeterminate",
     * with the appropriate error status.
     *
     * @param Request $request
     * @param \Galmi\Xacml\Policy[]|\Galmi\Xacml\PolicySet[]|\Galmi\Xacml\Rule[] $items
     * @return string
     */
    public function evaluate(Request $request, array $items)
    {
        $atLeastOne = false;
        $selectedPolicy = null;
        $appResult = null;

        foreach ($items as $item) {
            try {
                $appResult = $item->evaluate($request);
            } catch (\Exception $e) {
                return Decision::INDETERMINATE;
            }

            if (in_array($appResult, [Decision::DENY, Decision::PERMIT])) {
                if ($atLeastOne) {
                    return Decision::INDETERMINATE;
                } else {
                    $atLeastOne = true;
                    $selectedPolicy = $item;
                }
            } else {
                continue;
            }
        }
        if ($atLeastOne) {
            return $selectedPolicy->evaluate($request);
        } else {
            return Decision::NOT_APPLICABLE;
        }
    }
}