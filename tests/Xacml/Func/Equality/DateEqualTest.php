<?php

class DateEqualTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $func = new \Galmi\Xacml\Func\Equality\DateEqual();
        $bringType = self::getMethod('bringType');

        $this->assertInstanceOf(\DateTime::class, $bringType->invokeArgs($func, [date('Y-m-d')]));
        $this->assertInstanceOf(\DateTime::class, $bringType->invokeArgs($func, [new \DateTime()]));
    }

    protected function getMethod($name) {
        $class = new ReflectionClass('\Galmi\Xacml\Func\Equality\DateEqual');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
