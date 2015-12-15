<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 15.12.15
 * Time: 0:13
 */

namespace Galmi\XacmlBundle\Model;


class TargetAnyOf implements TargetInterface
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
     * @return MatchEnum
     */
    public function evaluate()
    {
        if (count($this->getTargetAllOf()) == 0) {
            return new MatchEnum(MatchEnum::INDETERMINATE);
        }
        $hasIndeterminate = false;
        /** @var TargetAllOf $target */
        foreach ($this->getTargetAllOf() as $target) {
            $targetEvaluate = $target->evaluate();
            if ($targetEvaluate == MatchEnum::MATCH) {
                return new MatchEnum(MatchEnum::MATCH);
            } else {
                if ($targetEvaluate == MatchEnum::INDETERMINATE) {
                    $hasIndeterminate = true;
                }
            }
        }

        if ($hasIndeterminate) {
            return new MatchEnum(MatchEnum::INDETERMINATE);
        }

        return new MatchEnum(MatchEnum::NOT_MATCH);
    }
}