<?php

namespace Galmi\XacmlBundle\Model\Target;


use Doctrine\Common\Collections\Collection;
use Galmi\XacmlBundle\Exception\IndeterminateException;
use Galmi\XacmlBundle\Model\Match;

/**
 * Class AllOf
 * 7.7 Target evaluation
 * All “True” - “Match”
 * No “False” and at least one “Indeterminate” - “Indeterminate”
 * At least one “False” - “No match”
 *
 * @package Galmi\XacmlBundle\Model\Target
 */
class AllOf implements TargetType
{

    /**
     * @param Collection $matches
     * @return boolean
     * @throws IndeterminateException
     */
    public function evaluate(Collection $matches)
    {
        /** @var Match $match */
        foreach ($matches as $match) {
            if (!$match->evaluate()) {
                return false;
            }
        }

        return true;
    }
}