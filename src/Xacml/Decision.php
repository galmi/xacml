<?php

namespace Galmi\Xacml;

/**
 * The result of evaluating a rule, policy or policy set
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class Decision
{
    const PERMIT = 'Permit';
    const DENY = 'Deny';
    const INDETERMINATE = 'Indeterminate';
    const INDETERMINATE_P = 'IndeterminateP';
    const INDETERMINATE_D = 'IndeterminateD';
    const INDETERMINATE_D_P = 'IndeterminateDP';
    const NOT_APPLICABLE = 'NotApplicable';
}