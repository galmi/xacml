<?php

class DenyOverridesTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $request = new \Galmi\Xacml\Request();
        $items = [
            $this->createItem(\Galmi\Xacml\Decision::PERMIT),
            $this->createItem(\Galmi\Xacml\Decision::DENY),
        ];
        $algorithm = new \Galmi\Xacml\CombiningAlgorithm\DenyOverrides();
        $this->assertEquals(\Galmi\Xacml\Decision::DENY, $algorithm->evaluate($request, $items));

        $items = [
            $this->createItem(\Galmi\Xacml\Decision::PERMIT),
            $this->createItem(\Galmi\Xacml\Decision::NOT_APPLICABLE),
        ];
        $this->assertEquals(\Galmi\Xacml\Decision::PERMIT, $algorithm->evaluate($request, $items));

        $items = [
            $this->createItem(\Galmi\Xacml\Decision::PERMIT),
            $this->createItem(\Galmi\Xacml\Decision::NOT_APPLICABLE),
            $this->createItem(\Galmi\Xacml\Decision::INDETERMINATE_D_P),
        ];
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D_P, $algorithm->evaluate($request, $items));

        $items = [
            $this->createItem(\Galmi\Xacml\Decision::PERMIT),
            $this->createItem(\Galmi\Xacml\Decision::NOT_APPLICABLE),
            $this->createItem(\Galmi\Xacml\Decision::INDETERMINATE_D),
        ];
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D_P, $algorithm->evaluate($request, $items));

        $items = [
            $this->createItem(\Galmi\Xacml\Decision::NOT_APPLICABLE),
            $this->createItem(\Galmi\Xacml\Decision::INDETERMINATE_D),
        ];
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D, $algorithm->evaluate($request, $items));

        $items = [
            $this->createItem(\Galmi\Xacml\Decision::NOT_APPLICABLE),
            $this->createItem(\Galmi\Xacml\Decision::INDETERMINATE_P),
        ];
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_P, $algorithm->evaluate($request, $items));

        $this->assertEquals(\Galmi\Xacml\Decision::NOT_APPLICABLE, $algorithm->evaluate($request, []));
    }

    public function createItem($response)
    {
        $mock = $this->getMockBuilder('stdClass')->setMethods(['evaluate'])->getMock();
        $mock->method('evaluate')->willReturn($response);

        return $mock;
    }
}
