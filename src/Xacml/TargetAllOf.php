<?php

namespace Galmi\Xacml;


class TargetAllOf implements TargetInterface
{
    /**
     * @var Match[]
     */
    protected $matches = array();

    /**
     * @return Match[]
     */
    public function getMatches()
    {
        return $this->matches ?: $this->matches = array();
    }

    /**
     * @param Match $match
     * @return $this
     */
    public function addMatch(Match $match)
    {
        if (!in_array($match, $this->getMatches(), true)) {
            $this->matches[] = $match;
        }

        return $this;
    }

    /**
     * @param Match $match
     * @return $this
     */
    public function removeMatch(Match $match)
    {
        if (in_array($match, $this->getMatches(), true)) {
            $key = array_search($match, $this->matches, true);
            if ($key === false) {
                return false;
            }

            unset($this->matches[$key]);
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
        if (count($this->getMatches()) == 0) {
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