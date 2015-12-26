<?php

namespace Galmi\Xacml;

/**
 * The <Target> class identifies the set of decision requests that the parent element is intended to evaluate.
 * The <Target> class SHALL appear as a child of a <PolicySet> and <Policy> class and MAY appear as a child of a <Rule> class.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class Target implements Evaluable
{
    /**
     * Matching specification for attributes in the context.
     * If this element is missing, then the target SHALL match all contexts.
     *
     * @var TargetAnyOf[]
     */
    protected $targetAnyOf = array();

    /**
     * Getter for targetAnyOf
     *
     * @return TargetAnyOf[]
     */
    public function getTargetAnyOf()
    {
        return $this->targetAnyOf ?: $this->targetAnyOf = [];
    }

    /**
     * Add TargetAnyOf
     *
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
     * Remove TargetAnyOf
     *
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
     *  -------------------------------------------
     * |     <AnyOf> values      |  Target value   |
     *  -------------------------------------------
     * | All “Match”             | “Match”         |
     * | At least one “No Match” | “No Match”      |
     * | Otherwise               | “Indeterminate” |
     *  -------------------------------------------
     *
     * @inheritdoc
     */
    public function evaluate(Request $request)
    {
        if (count($this->getTargetAnyOf()) == 0) {
            return Match::MATCH;
        }
        /** @var TargetAnyOf $target */
        foreach ($this->getTargetAnyOf() as $target) {
            $targetEvaluate = $target->evaluate($request);
            if ($targetEvaluate === Match::INDETERMINATE) {
                return Match::INDETERMINATE;
            }
            if ($targetEvaluate === Match::NOT_MATCH) {
                return Match::NOT_MATCH;
            }
        }
        return Match::MATCH;
    }
}