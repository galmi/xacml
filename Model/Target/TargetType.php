<?php

namespace Galmi\XacmlBundle\Model\Target;


use Doctrine\Common\Collections\Collection;
use Galmi\XacmlBundle\Exception\IndeterminateException;

interface TargetType
{
    /**
     * @param Collection $matches
     * @return boolean
     * @throws IndeterminateException
     */
    public function evaluate(Collection $matches);
}