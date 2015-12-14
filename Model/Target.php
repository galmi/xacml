<?php

namespace Galmi\XacmlBundle\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Target implements TargetInterface
{
    /**
     * @var Collection
     */
    protected $targetAnyOf;

    /**
     * @return Collection
     */
    public function getTargetAnyOf()
    {
        return $this->targetAnyOf ?: $this->targetAnyOf = new ArrayCollection();
    }

    /**
     * @param TargetAnyOf $match
     * @return $this
     */
    public function addTargetAnyOf(TargetAnyOf $match)
    {
        if (!$this->getTargetAnyOf()->contains($match)) {
            $this->getTargetAnyOf()->add($match);
        }

        return $this;
    }

    /**
     * @param TargetAnyOf $match
     * @return $this
     */
    public function removeTargetAnyOf(TargetAnyOf $match)
    {
        if ($this->getTargetAnyOf()->contains($match)) {
            $this->getTargetAnyOf()->remove($match);
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
        if ($this->getTargetAnyOf()->count() == 0) {
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