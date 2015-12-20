<?php

/*
 * This file is part of the Xacml package.
 *
 * (c) Ildar Galiautdinov <ildar@galmi.ru>
 */

namespace Galmi\Xacml\Expression;

use Galmi\Xacml\Expression;
use Galmi\Xacml\Request;

/**
 * The <AttributeValue> class SHALL contain a literal attribute value.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class AttributeValue extends Expression
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * AttributeValue constructor.
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Return $value
     *
     * @param Request $request
     * @return mixed
     */
    public function evaluate(Request $request)
    {
        return $this->value;
    }
}