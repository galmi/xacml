<?php

class StringEqualTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $func = new \Galmi\Xacml\Func\Equality\StringEqual();

        $this->assertEquals(true, $func->evaluate(['value', 'value']));
        $this->assertEquals(false, $func->evaluate(['value', 'different value']));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEvaluateException()
    {
        $func = new \Galmi\Xacml\Func\Equality\StringEqual();
        $this->assertEquals(false, $func->evaluate(['value', 'different value', 'third parameter']), 'Third parameter throw exception');
    }
}
