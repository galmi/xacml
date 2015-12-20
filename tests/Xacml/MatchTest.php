<?php

class MatchTest extends \PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $request = new \Galmi\Xacml\Request();
        $request->set('Subject.role', 'Manager');

        $attributeFinder = $this->getMockBuilder('stdClass')
            ->setMethods(['getValue'])
            ->getMock();
        $attributeFinder->method('getValue')
            ->will($this->returnCallback(function() {
                /** @var \Galmi\Xacml\Request $request */
                $request = func_get_arg(0);
                $attributeId = func_get_arg(1);
                return $request->get($attributeId);
            }));

        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::ATTRIBUTE_FINDER, $attributeFinder);

        $match = new \Galmi\Xacml\Match('Subject.role', 'Manager');
        $this->assertTrue($match->evaluate($request), 'Test match evaluation');
    }
}