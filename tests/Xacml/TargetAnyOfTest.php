<?php

class TargetAnyOfTest extends PHPUnit_Framework_TestCase
{
    public function testAddTargetAllOf()
    {
        $target = new \Galmi\Xacml\TargetAnyOf();
        $this->assertEquals([], $target->getTargetAllOf(), 'Empty list of AllOf');

        $allOf = new \Galmi\Xacml\TargetAllOf();
        $target->addTargetAllOf($allOf);

        $this->assertEquals([$allOf], $target->getTargetAllOf(), 'One item array of AllOf');
    }

    public function testRemoveTargetAllOf()
    {
        $target = new \Galmi\Xacml\TargetAnyOf();
        $this->assertEquals([], $target->getTargetAllOf(), 'Empty list of AllOf');

        $allOf1 = new \Galmi\Xacml\TargetAllOf();
        $target->addTargetAllOf($allOf1);

        $allOf2 = new \Galmi\Xacml\TargetAllOf();
        $match = new \Galmi\Xacml\Match('Attribute.test', 'test');
        $allOf2->addMatch($match);
        $target->addTargetAllOf($allOf2);

        $this->assertEquals([$allOf1, $allOf2], $target->getTargetAllOf(), 'Two item array of AllOf');

        $target->removeTargetAllOf($allOf1);
        $this->assertEquals([$allOf2], $target->getTargetAllOf(), 'One item array of AllOf2');
    }
}
