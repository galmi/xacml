<?php

namespace Galmi\Xacml;


abstract class Expression implements Evaluable
{
    /**
     * @param Request $request
     * @return mixed
     */
    abstract public function evaluate(Request $request);
}