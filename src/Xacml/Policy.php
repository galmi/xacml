<?php

namespace Galmi\Xacml;


use Galmi\Xacml\Algorithm\CombiningAlgorithm;

class Policy
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
     * @return number
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param number $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
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
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return CombiningAlgorithm
     */
    public function getCombiningAlghoritm()
    {
        return $this->combiningAlgorithm;
    }

    /**
     * @param CombiningAlgorithm $combiningAlgorithm
     */
    public function setCombiningAlghoritm($combiningAlgorithm)
    {
        $this->combiningAlgorithm = $combiningAlgorithm;
    }

    /**
     * @return Rule[]
     */
    public function getRules()
    {
        return $this->rules ?: $this->rules = array();
    }

    /**
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
        }

        return $this;
    }
}