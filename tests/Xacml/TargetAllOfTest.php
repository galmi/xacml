<?php

class TargetAllOfTest extends \PHPUnit_Framework_TestCase
{
    public function testAddMatch()
    {
        $target = new \Galmi\Xacml\TargetAllOf();
        $this->assertEquals([], $target->getMatches(), 'Empty list of Match');

        $match = new \Galmi\Xacml\Match('attribute', 'value');
        $target->addMatch($match);

        $this->assertEquals([$match], $target->getMatches(), 'One item array of Match');
    }

    public function testRemoveMatch()
    {
        $target = new \Galmi\Xacml\TargetAllOf();
        $this->assertEquals([], $target->getMatches(), 'Empty list of Match');

        $match1 = new \Galmi\Xacml\Match('attribute', 'value');
        $match2 = new \Galmi\Xacml\Match('attribute2', 'value2');
        $target->addMatch($match1);
        $target->addMatch($match2);

        $this->assertEquals([$match1, $match2], $target->getMatches(), 'Two items array of Match');

        $target->removeMatch($match1);
        $this->assertEquals([$match2], $target->getMatches(), 'One items array of Match');

        $this->assertEquals($target, $target->removeMatch($match1), 'Remove not existed item will return this');
    }

    /**
     * @expectedException \Galmi\Xacml\Exception\IndeterminateException
     */
    public function testEvaluate()
    {
        $request = new \Galmi\Xacml\Request();
        $target = new \Galmi\Xacml\TargetAllOf();
        $this->assertEquals(\Galmi\Xacml\Match::INDETERMINATE, $target->evaluate($request));
    }
}
