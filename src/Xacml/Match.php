<?php

namespace Galmi\Xacml;


use Galmi\Xacml\Expression\AttributeDesignator;

/**
 * The <Match> class SHALL identify a set of entities by matching attribute values
 * of the request context with the embedded attribute value.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class Match implements Evaluable
{
    const MATCH = 'Match';
    const NOT_MATCH = 'Not match';
    const INDETERMINATE = 'Indeterminate';

    protected $id;

    /**
     * Embedded attribute value.
     *
     * @var mixed
     */
    protected $attributeValue;

    /**
     * SHALL be used to identify one or more attribute values in an <Attributes> element of the request context.
     *
     * @var AttributeDesignator
     */
    protected $attributeDesignator;

    /**
     * Match constructor.
     *
     * @param string $attributeId
     * @param mixed $expectedAttributeValue
     */
    public function __construct($attributeId, $expectedAttributeValue)
    {
        $this->attributeValue = $expectedAttributeValue;
        $this->attributeDesignator = new AttributeDesignator($attributeId);
    }

    /**
     * @inheritdoc
     */
    public function evaluate(Request $request)
    {
        $attributeDesignateValue = $this->attributeDesignator->evaluate($request);
        if ($attributeDesignateValue == Match::INDETERMINATE) {
            return Match::INDETERMINATE;
        }
        return $attributeDesignateValue === $this->attributeValue;
    }

    /**
     * @return mixed
     */
    public function getAttributeValue()
    {
        return $this->attributeValue;
    }

    /**
     * @return AttributeDesignator
     */
    public function getAttributeDesignator()
    {
        return $this->attributeDesignator;
    }
}