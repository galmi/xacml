<?php

namespace Galmi\XacmlBundle\Model\Target;


use Doctrine\Common\Collections\Collection;
use Galmi\XacmlBundle\Exception\IndeterminateException;
use Galmi\XacmlBundle\Model\Match;

/**
 * Class AnyOf
 * 7.7 Target evaluation
 * At least one “Match” - “Match”
 * None matches and at least one “Indeterminate” - “Indeterminate”
 * All “No match” - “No match”
 *
 * @package Galmi\XacmlBundle\Model\Target
 */
class AnyOf implements TargetType
{
    /**
     * @param Collection $matches
     * @return bool
     * @throws IndeterminateException
     */
    public function evaluate(Collection $matches)
    {
        $inderterminate = false;

        /** @var Match $match */
        foreach ($matches as $match) {
            try {
                if ($match->evaluate() === true) {
                    return true;
                }
            } catch (IndeterminateException $e) {
                $inderterminate = true;
            }
        }
        if ($inderterminate) {
            throw new IndeterminateException();
        }

        return false;
    }
}