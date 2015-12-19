<?php

namespace Galmi\Xacml;


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
     * @var string
     */
    protected $attributeId;

    /**
     * @var AttributeDesignator
     */
    protected $attributeDesignator;

    /**
     * Match constructor.
     * @param $attributeId
     * @param $expectedAttributeValue
     */
    public function __construct($attributeId, $expectedAttributeValue)
    {
        $this->attributeId = $attributeId;
        $this->attributeValue = $expectedAttributeValue;
        $this->attributeDesignator = Config::get('AttributeDesignator');
    }

    /**
     * @return boolean
     */
    public function evaluate(Request $request)
    {
        $attributeDesignateValue = $this->getAttributeDesignator()->getValue($request, $this->getAttributeId());
        return $attributeDesignateValue === $this->getAttributeValue();
    }

    /**
     * @return AttributeDesignator
     */
    public function getAttributeDesignator()
    {
        return $this->attributeDesignator;
    }

    /**
     * @return string
     */
    public function getAttributeId()
    {
        return $this->attributeId;
    }

    /**
     * @return mixed
     */
    public function getAttributeValue()
    {
        return $this->attributeValue;
    }
}