<?php

namespace Galmi\Xacml\Expression;


use Galmi\Xacml\Config;
use Galmi\Xacml\Expression;
use Galmi\Xacml\Match;
use Galmi\Xacml\Request;

/**
 * The <AttributeDesignator> class retrieves a bag of values for a named attribute from the request
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class AttributeDesignator extends Expression
{
    /**
     * This attribute SHALL specify the AttributeId with which to match the attribute.
     *
     * @var string
     */
    protected $attributeId;

    /**
     * PIP Class for search value of attributeId
     *
     * @var AttributeFinder
     */
    protected $attributeFinder;

    /**
     * This attribute governs whether the element returns “Indeterminate” or an empty bag
     * in the event the named attribute is absent from the request context.
     *
     * @var bool
     */
    protected $mustBePresent;

    /**
     * AttributeDesignator constructor.
     *
     * @param $attributeId
     * @param bool $mustBePresent
     * @throws \Exception
     */
    public function __construct($attributeId, $mustBePresent = true)
    {
        $this->attributeId = $attributeId;
        $this->mustBePresent = $mustBePresent;
        $this->attributeFinder = Config::get(Config::ATTRIBUTE_FINDER);
    }

    /**
     * Retrieve attributeId value using AttributeFinder
     *
     * @inheritdoc
     */
    public function evaluate(Request $request)
    {
        try {
            $value = $this->attributeFinder->getValue($request, $this->attributeId);
        } catch (\Exception $e) {
            return Match::INDETERMINATE;
        }
        if ($value == null && $this->mustBePresent) {
            return Match::INDETERMINATE;
        }

        return $value;
    }
}