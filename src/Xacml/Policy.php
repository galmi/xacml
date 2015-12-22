<?php

namespace Galmi\Xacml;


use Galmi\Xacml\Algorithm\CombiningAlgorithm;

/**
 * The <Policy> class is the smallest entity that SHALL be presented to the PDP for evaluation.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class Policy implements Evaluable
{

    protected $id;

    /**
     * @var number
     */
    protected $version;

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
    protected $target;

    /**
     * A target, an effect, a condition and (optionally) a set of obligations or advice.  A component of a policy
     *
     * @var Rule[]
     */
    protected $rules = array();

    /**
     * The procedure for combining decisions from multiple rules
     *
     * @var CombiningAlgorithm
     */
    protected $combiningAlgorithm;

    /**
     * Getter for Version
     *
     * @return number
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Setter for Version
     *
     * @param number $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Getter for Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Setter for Description
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
     * Getter for Target
     *
     * @return Target
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Setter for Target
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
     * Getter for Combining algorithm
     *
     * @return CombiningAlgorithm
     */
    public function getCombiningAlghoritm()
    {
        return $this->combiningAlgorithm;
    }

    /**
     * Setter Combining algorithm
     *
     * @param CombiningAlgorithm $combiningAlgorithm
     * @return $this
     */
    public function setCombiningAlghoritm($combiningAlgorithm)
    {
        $this->combiningAlgorithm = $combiningAlgorithm;

        return $this;
    }

    /**
     * Getter for Rule
     *
     * @return Rule[]
     */
    public function getRules()
    {
        return $this->rules ?: $this->rules = array();
    }

    /**
     * Add Rule
     *
     * @param Rule $rule
     * @return $this
     */
    public function addRule(Rule $rule)
    {
        if (!in_array($rule, $this->getRules(), true)) {
            $this->rules[] = $rule;
        }

        return $this;
    }

    /**
     * Remove Rule
     *
     * @param Rule $rule
     * @return $this
     */
    public function removeRule(Rule $rule)
    {
        if (in_array($rule, $this->getRules(), true)) {
            $key = array_search($rule, $this->rules, true);
            if ($key === false) {
                return false;
            }

            unset($this->rules[$key]);
            $this->rules = array_values($this->rules);
        }

        return $this;
    }

    /**
     *  ---------------------------------------------------------------------------
     * |    Target       | Rule values |              Policy Value                 |
     *  ---------------------------------------------------------------------------
     * | “Match”         | Don’t care  | Specified by the rule-combining algorithm |
     * | “No-match”      | Don’t care  | “NotApplicable”                           |
     * | “Indeterminate” | See Table 7 | See Table 7                               |
     *  ---------------------------------------------------------------------------
     *
     * Table 7
     *  --------------------------------------------------------
     * | Combining algorithm Value | Policy set or policy Value |
     *  --------------------------------------------------------
     * | “NotApplicable”           | “NotApplicable”            |
     * | “Permit”                  | “Indeterminate{P}”         |
     * | “Deny”                    | “Indeterminate{D}”         |
     * | “Indeterminate”           | “Indeterminate{DP}”        |
     * | “Indeterminate{DP}”       | “Indeterminate{DP}”        |
     * | “Indeterminate{P}”        | “Indeterminate{P}”         |
     * | “Indeterminate{D}”        | “Indeterminate{D}”         |
     *  --------------------------------------------------------
     *
     * @inheritdoc
     */
    public function evaluate(Request $request)
    {
        $combiningAlgorithmDecision = null;
        $decision = Decision::NOT_APPLICABLE;
        try {
            if ($this->getTarget() == null || $this->getTarget()->evaluate($request) === Match::MATCH) {
                $combiningAlgorithmDecision = $this->combiningAlgorithm->evaluate($this->rules);
                $decision = $combiningAlgorithmDecision;
            }
        } catch (\Exception $e) {
            switch ($combiningAlgorithmDecision) {
                case (Decision::NOT_APPLICABLE):
                    $decision = Decision::NOT_APPLICABLE;
                    break;
                case (Decision::PERMIT):
                    $decision = Decision::INDETERMINATE_P;
                    break;
                case (Decision::DENY):
                    $decision = Decision::INDETERMINATE_D;
                    break;
                case (Decision::INDETERMINATE_D_P):
                    $decision = Decision::INDETERMINATE_D_P;
                    break;
                case (Decision::INDETERMINATE_P):
                    $decision = Decision::INDETERMINATE_P;
                    break;
                case (Decision::INDETERMINATE_D):
                    $decision = Decision::INDETERMINATE_D;
                    break;
                default:
                    $decision = Decision::INDETERMINATE_D_P;
            }
        }

        return $decision;
    }
}