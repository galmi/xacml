<?php

namespace Galmi\XacmlBundle\Model;

/**
 * Class Decision
 * The result of evaluating a rule, policy or policy set
 * @package Galmi\XacmlBundle\Model
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

    /**
     * @var string
     */
    protected $value;

    /**
     * Decision constructor.
     * @param $value
     */
    public function __construct($value = null)
    {
        $this->setValue($value);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }
}