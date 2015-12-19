<?php

namespace Galmi\Xacml;


class Apply
{
    /**
     * @var FunctionInterface
     */
    protected $functionId;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var
     */
    protected $value1;

    /**
     * @var
     */
    protected $value2;

    /**
     * @return boolean
     * @throws \Exception
     */
    public function evaluate()
    {
        return true;
    }
}