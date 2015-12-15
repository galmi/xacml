<?php

namespace Galmi\XacmlBundle\Model;


class Target implements TargetInterface
{
    /**
     * @var TargetAnyOf[]
     */
    protected $targetAnyOf;

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
     * @return MatchEnum
     */
    public function evaluate()
    {
        if (count($this->getTargetAnyOf()) == 0) {
            return new MatchEnum(MatchEnum::MATCH);
        }
        /** @var TargetAnyOf $target */
        foreach ($this->getTargetAnyOf() as $target) {
            $targetEvaluate = $target->evaluate();
            if ($targetEvaluate == MatchEnum::NOT_MATCH) {
                return new MatchEnum(MatchEnum::NOT_MATCH);
            } else if ($targetEvaluate == MatchEnum::INDETERMINATE) {
                return new MatchEnum(MatchEnum::INDETERMINATE);
            }
        }
        return new MatchEnum(MatchEnum::MATCH);
    }
}