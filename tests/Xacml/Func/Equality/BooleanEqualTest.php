<?php

class BooleanEqualTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $func = new \Galmi\Xacml\Func\Equality\BooleanEqual();
        $bringType = self::getMethod('bringType');

        $this->assertInternalType('boolean', $bringType->invokeArgs($func, [true]));
    }

    protected function getMethod($name) {
        $class = new ReflectionClass('\Galmi\Xacml\Func\Equality\BooleanEqual');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
