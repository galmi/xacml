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

class TargetAllOf implements TargetInterface
{
    /**
     * @var Collection
     */
    protected $matches;

    /**
     * @return Collection
     */
    public function getMatches()
    {
        return $this->matches ?: $this->matches = new ArrayCollection();
    }

    /**
     * @param Match $match
     * @return $this
     */
    public function addMatch(Match $match)
    {
        if (!$this->getMatches()->contains($match)) {
            $this->getMatches()->add($match);
        }

        return $this;
    }

    /**
     * @param Match $match
     * @return $this
     */
    public function removeMatch(Match $match)
    {
        if ($this->getMatches()->contains($match)) {
            $this->getMatches()->remove($match);
        }

        return $this;
    }

    /**
     *  ---------------------------------------------------------------
     * |            <Match> values                   |  <AllOf> Value  |
     *  ---------------------------------------------------------------
     * | All “True”                                  | “Match”         |
     * | No “False” and at least one “Indeterminate” | “Indeterminate” |
     * | At least one “False”                        | “No match”      |
     *  ---------------------------------------------------------------
     *
     * @return MatchEnum
     */
    public function evaluate()
    {
        if ($this->getMatches()->count() == 0) {
            return new MatchEnum(MatchEnum::INDETERMINATE);
        }
        $hasIndeterminate = false;
        /** @var Match $match */
        foreach($this->getMatches() as $match) {
            try {
                $matchEvaluate = $match->evaluate();
                if (!$matchEvaluate) {
                    return new MatchEnum(MatchEnum::NOT_MATCH);
                }
            } catch (\Exception $e) {
                $hasIndeterminate = true;
            }
        }
        if ($hasIndeterminate) {
            return new MatchEnum(MatchEnum::INDETERMINATE);
        }
        return new MatchEnum(MatchEnum::MATCH);
    }
}