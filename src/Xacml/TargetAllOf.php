<?php

namespace Galmi\Xacml;


/**
 * The <AllOf> class SHALL contain a conjunctive sequence of <Match> classes.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class TargetAllOf implements Evaluable
{
    /**
     * A conjunctive sequence of individual matches of the attributes in the request context
     * and the embedded attribute values.
     *
     * @var Match[]
     */
    protected $matches = array();

    /**
     * Getter for matches
     *
     * @return Match[]
     */
    public function getMatches()
    {
        return $this->matches ?: $this->matches = array();
    }

    /**
     * Add Match
     *
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
     * Remove Match
     *
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
     * @inheritdoc
     */
    public function evaluate(Request $request)
    {
        if (count($this->getMatches()) == 0) {
            return Match::INDETERMINATE;
        }
        $hasIndeterminate = false;
        /** @var Match $match */
        foreach ($this->getMatches() as $match) {
            $matchEvaluate = $match->evaluate($request);
            if ($matchEvaluate === Match::INDETERMINATE) {
                $hasIndeterminate = true;
            } elseif (!$matchEvaluate) {
                return Match::NOT_MATCH;
            }
        }
        if ($hasIndeterminate) {
            return Match::INDETERMINATE;
        }

        return Match::MATCH;
    }
}