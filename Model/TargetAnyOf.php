<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 15.12.15
 * Time: 0:13
 */

namespace Galmi\XacmlBundle\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TargetAnyOf implements TargetInterface
{
    /**
     * @var Collection
     */
    protected $targetAllOf;

    /**
     * @return Collection
     */
    public function getTargetAllOf()
    {
        return $this->targetAllOf ?: $this->targetAllOf = new ArrayCollection();
    }

    /**
     * @param TargetAllOf $match
     * @return $this
     */
    public function addTargetAllOf(TargetAllOf $match)
    {
        if (!$this->getTargetAllOf()->contains($match)) {
            $this->getTargetAllOf()->add($match);
        }

        return $this;
    }

    /**
     * @param TargetAllOf $match
     * @return $this
     */
    public function removeTargetAllOf(TargetAllOf $match)
    {
        if ($this->getTargetAllOf()->contains($match)) {
            $this->getTargetAllOf()->remove($match);
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
        if ($this->getTargetAllOf()->count() == 0) {
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