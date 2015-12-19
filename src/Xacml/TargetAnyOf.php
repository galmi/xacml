<?php

namespace Galmi\Xacml;


class TargetAnyOf implements Evaluable
{
    /**
     * @var TargetAllOf[]
     */
    protected $targetAllOf = array();

    /**
     * @return TargetAllOf[]
     */
    public function getTargetAllOf()
    {
        return $this->targetAllOf ?: $this->targetAllOf = array();
    }

    /**
     * @param TargetAllOf $match
     * @return $this
     */
    public function addTargetAllOf(TargetAllOf $match)
    {
        if (!in_array($match, $this->getTargetAllOf(), true)) {
            $this->targetAllOf[] = $match;
        }

        return $this;
    }

    /**
     * @param TargetAllOf $match
     * @return $this
     */
    public function removeTargetAllOf(TargetAllOf $match)
    {
        if (in_array($match, $this->getTargetAllOf(), true)) {
            $key = array_search($match, $this->targetAllOf, true);
            if ($key === false) {
                return false;
            }

            unset($this->targetAllOf[$key]);
            $this->targetAllOf = array_values($this->targetAllOf);
        }

        return $this;
    }

    /**
     *  -----------------------------------------------------------------
     * |            <AllOf> values                     | <AnyOf> Value   |
     *  -----------------------------------------------------------------
     * | At least one “Match”                          | “Match”         |
     * | None matches and at least one “Indeterminate” | “Indeterminate” |
     * | All “No match”                                | “No match”      |
     *  -----------------------------------------------------------------
     *
     * @return string
     */
    public function evaluate(Request $request)
    {
        if (count($this->getTargetAllOf()) == 0) {
            return Match::INDETERMINATE;
        }
        $hasIndeterminate = false;
        /** @var TargetAllOf $target */
        foreach ($this->getTargetAllOf() as $target) {
            $targetEvaluate = $target->evaluate($request);
            if ($targetEvaluate == Match::MATCH) {
                return Match::MATCH;
            } else {
                if ($targetEvaluate == Match::INDETERMINATE) {
                    $hasIndeterminate = true;
                }
            }
        }

        if ($hasIndeterminate) {
            return Match::INDETERMINATE;
        }

        return Match::NOT_MATCH;
    }
}