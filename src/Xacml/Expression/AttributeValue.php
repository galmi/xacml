<?php

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
     * @inheritdoc
     */
    public function evaluate(Request $request)
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return AttributeValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}