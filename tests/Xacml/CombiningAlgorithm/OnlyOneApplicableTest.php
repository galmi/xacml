<?php

class OnlyOneApplicableTest extends PHPUnit_Framework_TestCase
{

    public function testEvaluate()
    {
        $request = new \Galmi\Xacml\Request();
        $items = [
            $this->createItem(\Galmi\Xacml\Decision::NOT_APPLICABLE),
            $this->createItem(\Galmi\Xacml\Decision::DENY),
        ];
        $algorithm = new \Galmi\Xacml\CombiningAlgorithm\OnlyOneApplicable();
        $this->assertEquals(\Galmi\Xacml\Decision::DENY, $algorithm->evaluate($request, $items));

        $items = [
            $this->createItem(\Galmi\Xacml\Decision::NOT_APPLICABLE),
            $this->createItem(\Galmi\Xacml\Decision::NOT_APPLICABLE),
        ];
        $algorithm = new \Galmi\Xacml\CombiningAlgorithm\OnlyOneApplicable();
        $this->assertEquals(\Galmi\Xacml\Decision::NOT_APPLICABLE, $algorithm->evaluate($request, $items));
    }

    public function testEvaluateException()
    {
        $request = new \Galmi\Xacml\Request();
        $items = [
            $this->createItem(\Galmi\Xacml\Decision::PERMIT),
            $this->createItem(\Galmi\Xacml\Decision::DENY),
        ];
        $algorithm = new \Galmi\Xacml\CombiningAlgorithm\OnlyOneApplicable();
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE, $algorithm->evaluate($request, $items));
    }

    public function createItem($response)
    {
        $mock = $this->getMockBuilder('stdClass')->setMethods(['evaluate'])->getMock();
        $mock->method('evaluate')->willReturn($response);

        return $mock;
    }
}
