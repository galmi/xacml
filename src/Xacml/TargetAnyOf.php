<?php

namespace Galmi\Xacml;


/**
 * The <AnyOf> class SHALL contain a disjunctive sequence of <AllOf> classes.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class TargetAnyOf implements Evaluable
{
    /**
     * <AllOf> [One to Many, Required]
     *
     * @var TargetAllOf[]
     */
    protected $targetAllOf = array();

    /**
     * Getter for TargetAllOf
     *
     * @return TargetAllOf[]
     */
    public function getTargetAllOf()
    {
        return $this->targetAllOf ?: $this->targetAllOf = array();
    }

    /**
     * Add TargetAllOf
     *
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
     * Remove TargetAllOf
     *
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
     * @inheritdoc
     */
    public function evaluate(Request $request)
    {
        if (count($this->getTargetAllOf()) == 0) {
            throw new Exception\IndeterminateException();
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
            throw new Exception\IndeterminateException();
        }

        return Match::NOT_MATCH;
    }
}