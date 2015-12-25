<?php

class StringNormalizeToLowerCaseTest extends PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage StringEqual function must contains only 2 parameters
     */
    public function testArguments()
    {
        $func = new \Galmi\Xacml\Func\StringConversion\StringNormalizeToLowerCase();
        $func->evaluate([1,2]);
    }

    public function testEvaluate()
    {
        $func = new \Galmi\Xacml\Func\StringConversion\StringNormalizeToLowerCase();
        $this->assertEquals('string', $func->evaluate(['String']));
    }
}
