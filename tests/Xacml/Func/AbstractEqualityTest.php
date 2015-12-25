<?php

class AbstractEqualityTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $mock = $this->getMockForAbstractClass('\\Galmi\\Xacml\\Func\\AbstractEquality');
        $mock->method('bringType')
             ->willReturnArgument(0);

        $this->assertTrue($mock->evaluate([1, 1]));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testArgumentsException()
    {
        $mock = $this->getMockForAbstractClass('\\Galmi\\Xacml\\Func\\AbstractEquality');
        $mock->method('bringType')
            ->willReturnArgument(0);

        $this->assertTrue($mock->evaluate([1]));
    }
}
