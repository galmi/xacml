<?php

class AttributeDesignatorTest extends PHPUnit_Framework_TestCase
{

    public function testEvaluate1()
    {
        $attributeId = 'Subject.role';
        $request = $this->createRequest();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::ATTRIBUTE_FINDER, $this->createFinder());

        $attributeDesignator = new \Galmi\Xacml\Expression\AttributeDesignator($attributeId);
        $this->assertEquals('Manager', $attributeDesignator->evaluate($request));
    }

    public function testEvaluate2()
    {
        $attributeId = 'Subject.id';
        $request = $this->createRequest();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::ATTRIBUTE_FINDER, $this->createFinder());

        $attributeDesignator = new \Galmi\Xacml\Expression\AttributeDesignator($attributeId);
        $this->assertEquals(\Galmi\Xacml\Match::INDETERMINATE, $attributeDesignator->evaluate($request));
    }

    public function testEvaluate3()
    {
        $attributeId = 'Subject.id';
        $request = $this->createRequest();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::ATTRIBUTE_FINDER, $this->createFinder());

        $attributeDesignator = new \Galmi\Xacml\Expression\AttributeDesignator($attributeId, false);
        $this->assertEquals(null, $attributeDesignator->evaluate($request));
    }

    public function testEvaluate4()
    {
        $attributeId = 'Subject.id';
        $request = $this->createRequest();
        $attributeFinder = $this->getMockBuilder('stdClass')
            ->setMethods(['getValue'])
            ->getMock();
        $attributeFinder->method('getValue')->willReturnCallback(function(){
            throw new \Exception();
        });
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::ATTRIBUTE_FINDER, $attributeFinder);

        $attributeDesignator = new \Galmi\Xacml\Expression\AttributeDesignator($attributeId, false);
        $this->assertEquals(\Galmi\Xacml\Match::INDETERMINATE, $attributeDesignator->evaluate($request));
    }

    protected function createFinder()
    {
        $attributeFinder = $this->getMockBuilder('stdClass')
            ->setMethods(['getValue'])
            ->getMock();
        $attributeFinder->method('getValue')
            ->will(
                $this->returnCallback(
                    function () {
                        /** @var \Galmi\Xacml\Request $request */
                        $request = func_get_arg(0);
                        $attributeId = func_get_arg(1);

                        return $request->get($attributeId);
                    }
                )
            );

        return $attributeFinder;
    }

    protected function createRequest()
    {
        $request = new \Galmi\Xacml\Request();
        $request->set('WorkingTime', true);
        $request->set('Subject.role', 'Manager');
        $request->set('Object.type', 'Document');
        $request->set('Action', 'edit');

        return $request;
    }
}
