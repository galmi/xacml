<?php

namespace Galmi\XacmlBundle\Model;


use Galmi\XacmlBundle\Exception\IndeterminateException;

class Condition
{

    /**
     * @return boolean
     * @throws IndeterminateException
     */
    public function evaluate()
    {
        return true;
    }
}