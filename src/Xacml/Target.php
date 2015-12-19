<?php

namespace Galmi\Xacml;


class Target implements Evaluable
{
    /**
     * @var TargetAnyOf[]
     */
    protected $targetAnyOf = array();

    /**
     * @return TargetAnyOf[]
     */
    public function getTargetAnyOf()
    {
        return $this->targetAnyOf ?: $this->targetAnyOf = [];
    }

    /**
     * @param TargetAnyOf $match
     * @return $this
     */
    public function addTargetAnyOf(TargetAnyOf $match)
    {
        if (!in_array($match, $this->getTargetAnyOf(), true)) {
            $this->targetAnyOf[] = $match;
        }

        return $this;
    }

    /**
     * @param TargetAnyOf $match
     * @return $this
     */
    public function removeTargetAnyOf(TargetAnyOf $match)
    {
        if (in_array($match, $this->getTargetAnyOf(), true)) {
            $key = array_search($match, $this->targetAnyOf, true);
            if ($key === false) {
                return false;
            }

            unset($this->targetAnyOf[$key]);
            $this->targetAnyOf = array_values($this->targetAnyOf);
        }

        return $this;
    }

    /**
     *
     *  -------------------------------------------
     * |     <AnyOf> values      |  Target value   |
     *  -------------------------------------------
     * | All “Match”             | “Match”         |
     * | At least one “No Match” | “No Match”      |
     * | Otherwise               | “Indeterminate” |
     *  -------------------------------------------
     *
     * @return string
     */
    public function evaluate(Request $request)
    {
        if (count($this->getTargetAnyOf()) == 0) {
            return Match::MATCH;
        }
        /** @var TargetAnyOf $target */
        foreach ($this->getTargetAnyOf() as $target) {
            $targetEvaluate = $target->evaluate($request);
            if ($targetEvaluate == Match::NOT_MATCH) {
                return Match::NOT_MATCH;
            } else if ($targetEvaluate == Match::INDETERMINATE) {
                return Match::INDETERMINATE;
            }
        }
        return Match::MATCH;
    }
}