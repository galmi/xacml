<?php

class CombiningAlgorithmRegistryTest extends PHPUnit_Framework_TestCase
{

    public function testCamelCase()
    {
        $func = new \Galmi\Xacml\CombiningAlgorithmRegistry();

        $camelCase = self::getMethod('camelCase');
        $this->assertEquals('StringEqual', $camelCase->invokeArgs($func, array('string-equal')));

        $this->assertEquals('Equal', $camelCase->invokeArgs($func, array('equal')));
    }

    public function testGet()
    {
        $func = new \Galmi\Xacml\CombiningAlgorithmRegistry();

        $this->assertInstanceOf('\\Galmi\\Xacml\\CombiningAlgorithm\\AlgorithmInterface', $func->get('deny-overrides'));
    }

    /**
     * @expectedException \Galmi\Xacml\Exception\AlgorithmNotFoundException
     * @expectedExceptionMessage Class MyFunc not found
     */
    public function testSet()
    {
        $func = new \Galmi\Xacml\CombiningAlgorithmRegistry();

        $func->set('my-func', \MyFunc::class);

        $func->get('my-func');
    }
    /**
     * @expectedException \Galmi\Xacml\Exception\AlgorithmNotFoundException
     */
    public function testGetFunctionException()
    {
        $func = new \Galmi\Xacml\CombiningAlgorithmRegistry();

        $this->assertInstanceOf('\\Galmi\\Xacml\\Func\\FuncInterface',
            $func->get('wrong-class'),
            'FunctionNotFoundException');
    }

    protected function getMethod($name) {
        $class = new ReflectionClass('\Galmi\Xacml\CombiningAlgorithmRegistry');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
