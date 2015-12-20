<?php

namespace Galmi\Xacml;


use Galmi\Xacml\Expression\AttributeDesignator;

class Match implements Evaluable
{
    const MATCH = 'Match';
    const NOT_MATCH = 'Not match';
    const INDETERMINATE = 'Indeterminate';

    protected $id;

    /**
     * @var mixed
     */
    protected $attributeValue;

    /**
     * @var AttributeDesignator
     */
    protected $attributeDesignator;

    /**
     * Match constructor.
     * @param string $attributeId
     * @param mixed $expectedAttributeValue
     */
    public function __construct($attributeId, $expectedAttributeValue)
    {
        $this->attributeValue = $expectedAttributeValue;
        $this->attributeDesignator = new AttributeDesignator($attributeId);
    }

    /**
     * @return boolean
     */
    public function evaluate(Request $request)
    {
        $attributeDesignateValue = $this->attributeDesignator->evaluate($request);
        return $attributeDesignateValue === $this->attributeValue;
    }
}