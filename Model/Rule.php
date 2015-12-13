<?php

namespace Galmi\XacmlBundle\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Galmi\XacmlBundle\Exception\IndeterminateException;

class Rule
{
    protected $id;

    /**
     * The intended consequence of a satisfied rule (either "Permit" or "Deny")
     *
     * @var Decision
     */
    protected $effect;

    /**
     * @var string
     */
    protected $description;

    /**
     * The set of decision requests, identified by definitions for resource, subject and action
     * that a rule, policy, or policy set is intended to evaluate
     *
     * @var Target
     */
    protected $target = null;

    /**
     * An expression of predicates.  A function that evaluates to "True", "False" or “Indeterminate”
     *
     * @var Condition
     */
    protected $condition;

    /**
     * An operation specified in a rule, policy or policy set that should be performed by the PEP
     * in conjunction with the enforcement of an authorization decision
     *
     * @var Collection
     */
    protected $obligationExpressions;

    /**
     * A supplementary piece of information in a policy or policy set which is provided to the PEP with the decision of the PDP.
     *
     * @var Collection
     */
    protected $adviceExpressions;

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
     * @return Decision
     */
    public function getEffect()
    {
        return $this->effect;
    }

    /**
     * @param Decision $effect
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
     * @return Condition
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param Condition $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * Evaluation rule
     *
     * @return Decision
     */
    public function evaluation()
    {
        $decision = new Decision(Decision::NOT_APPLICABLE);
        try {
            if ($this->getTarget() == null || $this->getTarget()->evaluate()) {
                if ($this->getCondition()->evaluate()) {
                    $decision = $this->getEffect();
                }
            }
        } catch (IndeterminateException $e) {
            if ($this->getEffect() == Decision::PERMIT) {
                $decision = new Decision(Decision::INDETERMINATE_P);
            } else {
                $decision = new Decision(Decision::INDETERMINATE_D);
            }
        }
        return $decision;
    }
}