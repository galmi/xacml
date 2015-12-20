<?php

class AttributeValueTest extends PHPUnit_Framework_TestCase
{

    public function testEvaluate()
    {
        $request = new \Galmi\Xacml\Request();
        $value = 'testValue';
        $attributeValue = new \Galmi\Xacml\Expression\AttributeValue($value);

        $this->assertEquals($value, $attributeValue->evaluate($request));
    }
}
