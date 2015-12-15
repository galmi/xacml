<?php

namespace Galmi\Xacml;

/**
 * Class DecisionEnum
 * The result of evaluating a rule, policy or policy set
 * @package Galmi\Xacml
 */
class DecisionEnum extends \SplEnum
{
    const PERMIT = 'Permit';
    const DENY = 'Deny';
    const INDETERMINATE = 'Indeterminate';
    const INDETERMINATE_P = 'IndeterminateP';
    const INDETERMINATE_D = 'IndeterminateD';
    const INDETERMINATE_D_P = 'IndeterminateDP';
    const NOT_APPLICABLE = 'NotApplicable';
}