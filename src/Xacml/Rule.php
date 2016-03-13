<?php

namespace Galmi\Xacml;

/**
 * The <Rule> class SHALL define the individual rules in the policy.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class Rule implements Evaluable
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
     * Getter for description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Setter for description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Getter for effect
     *
     * @return string
     */
    public function getEffect()
    {
        return $this->effect;
    }

    /**
     * @param string $effect
     * @return Rule
     */
    public function setEffect($effect)
    {
        $this->effect = $effect;

        return $this;
    }

    /**
     * Setter for effect
     * @return Target
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Setter for target
     *
     * @param Target $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Getter for condition
     *
     * @return Expression
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Setter for condition
     *
     * @param Expression $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * @param mixed $id
     * @return Rule
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  -----------------------------------------------------------------------------------------
     * |       Target         |    Condition    |               Rule Value                       |
     *  -----------------------------------------------------------------------------------------
     * | “Match” or no target | “True”          | Effect                                         |
     * | “Match” or no target | “False”         | “NotApplicable”                                |
     * | “Match” or no target | “Indeterminate” | “Indeterminate{P}” if the Effect is Permit,    |
     * |                      |                 |    or “Indeterminate{D}” if the Effect is Deny |
     * | “No-match”           | Don’t care      | “NotApplicable”                                |
     * | “Indeterminate”      | Don’t care      | “Indeterminate{P}” if the Effect is Permit,    |
     * |                      |                 | or “Indeterminate{D}” if the Effect is Deny    |
     *  -----------------------------------------------------------------------------------------
     *
     * @inheritdoc
     */
    public function evaluate(Request $request)
    {
        $targetValue = null;
        $conditionValue = null;
        $decision = Decision::NOT_APPLICABLE;
        if ($this->getTarget() === null || ($targetValue = $this->getTarget()->evaluate($request)) === Match::MATCH) {
            if ($this->getCondition() === null || ($conditionValue = $this->getCondition()->evaluate($request)) === true) {
                $decision = $this->getEffect();
            }
        }
        if ($targetValue === Match::INDETERMINATE || $conditionValue === Match::INDETERMINATE) {
            if ($this->getEffect() === Decision::PERMIT) {
                $decision = Decision::INDETERMINATE_P;
            } else {
                $decision = Decision::INDETERMINATE_D;
            }
        }

        return $decision;
    }
}