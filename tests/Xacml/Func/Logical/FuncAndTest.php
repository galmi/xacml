<?php

class FuncAndTest extends PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $func = new \Galmi\Xacml\Func\Logical\FuncAnd();

        $this->assertEquals(true, $func->evaluate([true, true, true]));
        $this->assertEquals(false, $func->evaluate([true, false]));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEvaluateException()
    {
        $func = new \Galmi\Xacml\Func\Logical\FuncAnd();
        $this->assertEquals(false, $func->evaluate([true, "test"]), 'Throw exception for not boolean variable');
    }
}
