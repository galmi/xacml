<?php

namespace Galmi\Xacml;


/**
 * The <Policy> class is the smallest entity that SHALL be presented to the PDP for evaluation.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class Policy implements Evaluable
{

    protected $id;

    /**
     * The version number of the Policy.
     *
     * @var number
     */
    protected $version;

    /**
     * A free-form description of the policy.
     *
     * @var string
     */
    protected $description;

    /**
     * The <Target> class defines the applicability of a <Policy> to a set of decision requests.
     *
     * @var Target
     */
    protected $target;

    /**
     * A sequence of rules that MUST be combined according to the RuleCombiningAlgId attribute.
     * Rules whose <Target> classes and conditions match the decision request MUST be considered.
     * Rules whose <Target> classes or conditions do not match the decision request SHALL be ignored.
     *
     * @var Rule[]
     */
    protected $rules = array();

    /**
     * The identifier of the rule-combining algorithm by which the <Policy> class MUST be combined.
     *
     * @var string
     */
    protected $ruleCombiningAlgId;

    /**
     * @return Target
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set target
     *
     * @param Target $target
     *
     * @return Policy
     */
    public function setTarget(Target $target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return string
     */
    public function getRuleCombiningAlgId()
    {
        return $this->ruleCombiningAlgId;
    }

    /**
     * Set ruleCombiningAlgId
     *
     * @param string $ruleCombiningAlgId
     *
     * @return Policy
     */
    public function setRuleCombiningAlgId($ruleCombiningAlgId)
    {
        $this->ruleCombiningAlgId = $ruleCombiningAlgId;

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
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

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
     * Getter for Rule
     *
     * @return Rule[]
     */
    public function getRules()
    {
        return $this->rules ?: $this->rules = array();
    }

    /**
     * @param Rule[] $rules
     * @return $this
     */
    public function setRules($rules)
    {
        $this->rules = $rules;

        return $this;
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
     * @return CombiningAlgorithm\AlgorithmInterface
     * @throws Exception\FunctionNotFoundException
     */
    public function getRuleCombiningAlgorithm()
    {
        /** @var CombiningAlgorithmRegistry $combiningAlgorithmFactory */
        $combiningAlgorithmFactory = Config::get(Config::COMBINING_ALGORITHM_REGISTRY);

        return $combiningAlgorithmFactory->get($this->ruleCombiningAlgId);
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
        $targetValue = null;
        $combiningAlgorithmDecision = null;
        $decision = Decision::NOT_APPLICABLE;
        $targetValue = $this->target->evaluate($request);
        if ($targetValue === Match::MATCH) {
            $decision = $this->getRuleCombiningAlgorithm()->evaluate($request, $this->getRules());
        } elseif ($targetValue === Match::INDETERMINATE) {
            switch ($this->getRuleCombiningAlgorithm()->evaluate($request, $this->getRules())) {
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