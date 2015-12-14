<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 15.12.15
 * Time: 0:20
 */

namespace Galmi\XacmlBundle\Model;


interface TargetInterface
{
    /**
     * Evaluation Target
     *
     * @return MatchEnum
     */
    public function evaluate();
}