<?php

class FuncRegistryTest extends PHPUnit_Framework_TestCase
{

    public function testCamelCase()
    {
        $func = new \Galmi\Xacml\FuncRegistry();

        $camelCase = self::getMethod('camelCase');
        $this->assertEquals('StringEqual', $camelCase->invokeArgs($func, array('string-equal')));

        $this->assertEquals('Equal', $camelCase->invokeArgs($func, array('equal')));
    }

    public function testGet()
    {
        $func = new \Galmi\Xacml\FuncRegistry();

        $this->assertInstanceOf('\\Galmi\\Xacml\\Func\\FuncInterface', $func->get('string-equal'));
    }

    /**
     * @expectedException \Galmi\Xacml\Exception\FunctionNotFoundException
     * @expectedExceptionMessage Class MyFunc not found
     */
    public function testSet()
    {
        $func = new \Galmi\Xacml\FuncRegistry();

        $func->set('my-func', \MyFunc::class);

        $func->get('my-func');
    }
    /**
     * @expectedException \Galmi\Xacml\Exception\FunctionNotFoundException
     */
    public function testGetFunctionException()
    {
        $func = new \Galmi\Xacml\FuncRegistry();

        $this->assertInstanceOf('\\Galmi\\Xacml\\Func\\FuncInterface',
            $func->get('wrong-class'),
            'FunctionNotFoundException');
    }

    protected function getMethod($name) {
        $class = new ReflectionClass('\Galmi\Xacml\FuncRegistry');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
