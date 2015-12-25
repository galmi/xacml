<?php

class TimeEqualTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $func = new \Galmi\Xacml\Func\Equality\TimeEqual();
        $bringType = self::getMethod('bringType');

        $this->assertEquals(strtotime('14:12:00'), $bringType->invokeArgs($func, ['14:12:00']));
    }

    protected function getMethod($name) {
        $class = new ReflectionClass('\Galmi\Xacml\Func\Equality\TimeEqual');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
