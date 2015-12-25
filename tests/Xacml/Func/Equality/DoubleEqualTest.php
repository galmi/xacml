<?php

class DoubleEqualTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $func = new \Galmi\Xacml\Func\Equality\DoubleEqual();
        $bringType = self::getMethod('bringType');

        $this->assertInternalType('double', $bringType->invokeArgs($func, [2]));
    }

    protected function getMethod($name) {
        $class = new ReflectionClass('\Galmi\Xacml\Func\Equality\DoubleEqual');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
