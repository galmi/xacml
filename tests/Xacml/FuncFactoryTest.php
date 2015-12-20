<?php

class FuncFactoryTest extends PHPUnit_Framework_TestCase
{

    public function testCamelCase()
    {
        $func = new \Galmi\Xacml\FuncFactory();

        $camelCase = self::getMethod('camelCase');
        $this->assertEquals('StringEqual', $camelCase->invokeArgs($func, array('string-equal')));

        $this->assertEquals('Equal', $camelCase->invokeArgs($func, array('equal')));
    }

    /**
     * @expectedException \Galmi\Xacml\Exception\FunctionNotFoundException
     */
    public function testGetFunction()
    {
        $func = new \Galmi\Xacml\FuncFactory();

        $this->assertInstanceOf('\\Galmi\\Xacml\\Func\\FuncInterface', $func->getFunction('string-equal'));

        $this->assertInstanceOf('\\Galmi\\Xacml\\Func\\FuncInterface', $func->getFunction('wrong-class'), 'FunctionNotFoundException');
    }

    protected function getMethod($name) {
        $class = new ReflectionClass('\Galmi\Xacml\FuncFactory');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
