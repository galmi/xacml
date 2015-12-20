<?php

namespace Galmi\Xacml;


use Galmi\Xacml\Exception\IndeterminateException;

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
     * @param Request $request
     * @return string
     * @throws IndeterminateException
     */
    public function evaluate(Request $request)
    {
        if (count($this->getTargetAllOf()) == 0) {
            throw new IndeterminateException();
        }
        $hasIndeterminate = false;
        /** @var TargetAllOf $target */
        foreach ($this->getTargetAllOf() as $target) {
            try {
                $targetEvaluate = $target->evaluate($request);
                if ($targetEvaluate == Match::MATCH) {
                    return Match::MATCH;
                }
            } catch (\Exception $e) {
                $hasIndeterminate = true;
            }
        }

        if ($hasIndeterminate) {
            throw new IndeterminateException();
        }

        return Match::NOT_MATCH;
    }
}