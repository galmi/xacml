<?php

namespace Galmi\Xacml;



class Rule
{
    protected $id;

    /**
     * Rule effect.  The value of this attribute is either “Permit” or “Deny”.
     *
     * @var string
     */
    protected $effect;

    /**
     * A free-form description of the rule.
     *
     * @var string
     */
    protected $description;

    /**
     * Identifies the set of decision requests that the <Rule> class is intended to evaluate.
     *
     * @var Target
     */
    protected $target = null;

    /**
     * A predicate that MUST be satisfied for the rule to be assigned its Effect value.
     *
     * @var Expression
     */
    protected $condition = null;

    /**
     * A conjunctive sequence of obligation expressions which MUST be evaluated into obligations byt the PDP.
     *
     * @var array
     */
    protected $obligationExpressions;

    /**
     * A conjunctive sequence of advice expressions which MUST evaluated into advice by the PDP.
     *
     * @var array
     */
    protected $adviceExpressions;

    /**
     * Rule constructor.
     * @param string $effect
     * @param Target|null $target
     * @param Expression|null $condition
     */
    public function __construct($effect, $target = null, $condition = null)
    {
        $this->effect = $effect;
        $this->target = $target;
        $this->condition = $condition;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getEffect()
    {
        return $this->effect;
    }

    /**
     * @param string $effect
     * @return $this
     */
    public function setEffect($effect)
    {
        $this->effect = $effect;

        return $this;
    }

    /**
     * @return Target
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param Target $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return Expression
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param Expression $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * Evaluation rule
     *
     * @param Request $request
     * @return string
     */
    public function evaluation(Request $request)
    {
        $decision = Decision::NOT_APPLICABLE;
        try {
            if ($this->getTarget() == null || $this->getTarget()->evaluate($request) === true) {
                if ($this->getCondition() == null || $this->getCondition()->evaluate($request) === true) {
                    $decision = $this->getEffect();
                }
            }
        } catch (\Exception $e) {
            if ($this->getEffect() == Decision::PERMIT) {
                $decision = Decision::INDETERMINATE_P;
            } else {
                $decision = Decision::INDETERMINATE_D;
            }
        }
        return $decision;
    }
}