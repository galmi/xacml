<?php

class StringEqualIgnoreCaseTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $func = new \Galmi\Xacml\Func\Equality\StringEqualIgnoreCase();
        $bringType = self::getMethod('bringType');

        $this->assertInternalType('string', $bringType->invokeArgs($func, [1]));
        $this->assertEquals('string', $bringType->invokeArgs($func, ['String']));
    }

    protected function getMethod($name) {
        $class = new ReflectionClass('\Galmi\Xacml\Func\Equality\StringEqualIgnoreCase');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
