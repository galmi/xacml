<?php

namespace Galmi\Xacml;


interface Evaluable
{
    /**
     * Evaluation Target
     *
     * @return string
     */
    public function evaluate(Request $request);
}