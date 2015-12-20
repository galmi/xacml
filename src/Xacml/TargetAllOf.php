<?php

namespace Galmi\Xacml;


use Galmi\Xacml\Exception\IndeterminateException;

class TargetAllOf implements Evaluable
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
            $this->matches = array_values($this->matches);
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
     * @param Request $request
     * @return string
     * @throws IndeterminateException
     */
    public function evaluate(Request $request)
    {
        if (count($this->getMatches()) == 0) {
            throw new IndeterminateException();
        }
        $hasIndeterminate = false;
        /** @var Match $match */
        foreach($this->getMatches() as $match) {
            try {
                $matchEvaluate = $match->evaluate($request);
                if (!$matchEvaluate) {
                    return Match::NOT_MATCH;
                }
            } catch (\Exception $e) {
                $hasIndeterminate = true;
            }
        }
        if ($hasIndeterminate) {
            throw new IndeterminateException();
        }
        return Match::MATCH;
    }
}