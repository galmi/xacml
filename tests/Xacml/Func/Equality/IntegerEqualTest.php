<?php

class IntegerEqualTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $func = new \Galmi\Xacml\Func\Equality\IntegerEqual();
        $bringType = self::getMethod('bringType');

        $this->assertInternalType('integer', $bringType->invokeArgs($func, [2]));
    }

    protected function getMethod($name) {
        $class = new ReflectionClass('\Galmi\Xacml\Func\Equality\IntegerEqual');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
