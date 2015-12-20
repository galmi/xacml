<?php

class TargetTest extends \PHPUnit_Framework_TestCase
{
    public function testAddTargetAnyOf()
    {
        $target = new \Galmi\Xacml\Target();
        $this->assertEquals([], $target->getTargetAnyOf(), 'Empty list of AnyOf');

        $anyOf = new \Galmi\Xacml\TargetAnyOf();
        $target->addTargetAnyOf($anyOf);

        $this->assertEquals([$anyOf], $target->getTargetAnyOf(), 'One item array of AnyOf');
    }

    public function testRemoveTargetAnyOf()
    {
        $target = new \Galmi\Xacml\Target();
        $this->assertEquals([], $target->getTargetAnyOf(), 'Empty list of AnyOf');

        $anyOf1 = new \Galmi\Xacml\TargetAnyOf();
        $target->addTargetAnyOf($anyOf1);

        $anyOf2 = new \Galmi\Xacml\TargetAnyOf();
        $allOf = new \Galmi\Xacml\TargetAllOf();
        $anyOf2->addTargetAllOf($allOf);
        $target->addTargetAnyOf($anyOf2);

        $this->assertEquals([$anyOf1, $anyOf2], $target->getTargetAnyOf(), 'Two item array of AnyOf');

        $target->removeTargetAnyOf($anyOf1);
        $this->assertEquals([$anyOf2], $target->getTargetAnyOf(), 'One item array of AnyOf2');

        $this->assertEquals($target, $target->removeTargetAnyOf($anyOf1), 'Remove not existed item will return this');
    }

    protected function createRequest()
    {
        $request = new \Galmi\Xacml\Request();
        $request->set('WorkingTime', true);
        $request->set('Subject.role', 'Manager');
        $request->set('Object.type', 'Document');
        $request->set('Action', 'edit');

        return $request;
    }

    protected function createAttributeFinder()
    {
        $attributeDesignator = $this->getMockBuilder('stdClass')
            ->setMethods(['getValue'])
            ->getMock();
        $attributeDesignator->method('getValue')
            ->will(
                $this->returnCallback(
                    function () {
                        /** @var \Galmi\Xacml\Request $request */
                        $request = func_get_arg(0);
                        $attributeId = func_get_arg(1);

                        return $request->get($attributeId);
                    }
                )
            );

        return $attributeDesignator;
    }

    /**
     * Test 1
     *
     * Empty target
     *
     * Result = Match
     */
    public function testEvaluate1()
    {
        $request = $this->createRequest();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::ATTRIBUTE_FINDER, $this->createAttributeFinder());

        $target = new \Galmi\Xacml\Target();
        $this->assertEquals(\Galmi\Xacml\Match::MATCH, $target->evaluate($request), 'Empty target return Match');
    }

    /**
     * Test 2
     *
     * Subject.role == Manager
     *
     * Result = Match
     */
    public function testEvaluate2()
    {
        $request = $this->createRequest();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::ATTRIBUTE_FINDER, $this->createAttributeFinder());

        $matchRole = new \Galmi\Xacml\Match('Subject.role', 'Manager');
        $allOf = new \Galmi\Xacml\TargetAllOf();
        $allOf->addMatch($matchRole);
        $anyOf = new \Galmi\Xacml\TargetAnyOf();
        $anyOf->addTargetAllOf($allOf);
        $target = new \Galmi\Xacml\Target();
        $target->addTargetAnyOf($anyOf);
        $this->assertEquals(\Galmi\Xacml\Match::MATCH, $target->evaluate($request), 'Test 2 is Match');
    }

    /**
     * Test 3
     *
     * Subject.role == Guest
     *
     * Result = Not Match
     */
    public function testEvaluate3()
    {
        $request = $this->createRequest();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::ATTRIBUTE_FINDER, $this->createAttributeFinder());

        $matchRole = new \Galmi\Xacml\Match('Subject.role', 'Guest');
        $allOf = new \Galmi\Xacml\TargetAllOf();
        $allOf->addMatch($matchRole);
        $anyOf = new \Galmi\Xacml\TargetAnyOf();
        $anyOf->addTargetAllOf($allOf);
        $target = new \Galmi\Xacml\Target();
        $target->addTargetAnyOf($anyOf);
        $this->assertEquals(\Galmi\Xacml\Match::NOT_MATCH, $target->evaluate($request), 'Test 3 is not Match');
    }

    /**
     * Test 4
     *
     * Subject.role == Manager
     *  AND
     * Object.type == Document
     *
     * Result = Match
     */
    public function testEvaluate4()
    {
        $request = $this->createRequest();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::ATTRIBUTE_FINDER, $this->createAttributeFinder());

        $matchRole = new \Galmi\Xacml\Match('Subject.role', 'Manager');
        $matchObject = new \Galmi\Xacml\Match('Object.type', 'Document');

        $allOf = new \Galmi\Xacml\TargetAllOf();
        $allOf->addMatch($matchRole);
        $allOf->addMatch($matchObject);
        $anyOf = new \Galmi\Xacml\TargetAnyOf();
        $anyOf->addTargetAllOf($allOf);
        $target = new \Galmi\Xacml\Target();
        $target->addTargetAnyOf($anyOf);
        $this->assertEquals(\Galmi\Xacml\Match::MATCH, $target->evaluate($request), 'Test 4 is Match');
    }

    /**
     * Test 5
     * (
     * WorkingTime == true
     *  AND
     * Subject.role == Manager
     * )
     *  OR
     * (
     * Object.type == Document
     *  AND
     * Action == edit
     * )
     *
     * Result = Match
     */
    public function testEvaluate5()
    {
        $request = $this->createRequest();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::ATTRIBUTE_FINDER, $this->createAttributeFinder());

        $matchTime = new \Galmi\Xacml\Match('WorkingTime', true);
        $matchRole = new \Galmi\Xacml\Match('Subject.role', 'Manager');
        $matchObject = new \Galmi\Xacml\Match('Object.type', 'Document');
        $matchAction = new \Galmi\Xacml\Match('Action', 'edit');

        $allOf1 = new \Galmi\Xacml\TargetAllOf();
        $allOf1->addMatch($matchTime);
        $allOf1->addMatch($matchRole);

        $allOf2 = new \Galmi\Xacml\TargetAllOf();
        $allOf2->addMatch($matchObject);
        $allOf2->addMatch($matchAction);

        $anyOf = new \Galmi\Xacml\TargetAnyOf();
        $anyOf->addTargetAllOf($allOf1);
        $anyOf->addTargetAllOf($allOf2);

        $target = new \Galmi\Xacml\Target();
        $target->addTargetAnyOf($anyOf);
        $this->assertEquals(\Galmi\Xacml\Match::MATCH, $target->evaluate($request), 'Test 5 is Match');
    }

    /**
     * Test 6
     * (
     * WorkingTime == true
     *  AND
     * Subject.role == Guest
     * )
     *  OR
     * (
     * Object.type == Document
     *  AND
     * Action == view
     * )
     *
     * Result = Not Match
     */
    public function testEvaluate6()
    {
        $request = $this->createRequest();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::ATTRIBUTE_FINDER, $this->createAttributeFinder());

        $matchTime = new \Galmi\Xacml\Match('WorkingTime', true);
        $matchRole = new \Galmi\Xacml\Match('Subject.role', 'Guest');
        $matchObject = new \Galmi\Xacml\Match('Object.type', 'Document');
        $matchAction = new \Galmi\Xacml\Match('Action', 'view');

        $allOf1 = new \Galmi\Xacml\TargetAllOf();
        $allOf1->addMatch($matchTime);
        $allOf1->addMatch($matchRole);

        $allOf2 = new \Galmi\Xacml\TargetAllOf();
        $allOf2->addMatch($matchObject);
        $allOf2->addMatch($matchAction);

        $anyOf = new \Galmi\Xacml\TargetAnyOf();
        $anyOf->addTargetAllOf($allOf1);
        $anyOf->addTargetAllOf($allOf2);

        $target = new \Galmi\Xacml\Target();
        $target->addTargetAnyOf($anyOf);
        $this->assertEquals(\Galmi\Xacml\Match::NOT_MATCH, $target->evaluate($request), 'Test 6 is not Match');
    }

    /**
     * Test 7
     *
     * Subject.role == Guest
     *  OR
     * (
     * Object.type == Document
     *  AND
     * Action == edit
     * )
     *
     * Result = Match
     */
    public function testEvaluate7()
    {
        $request = $this->createRequest();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::ATTRIBUTE_FINDER, $this->createAttributeFinder());

        $matchRole = new \Galmi\Xacml\Match('Subject.role', 'Guest');
        $matchObject = new \Galmi\Xacml\Match('Object.type', 'Document');
        $matchAction = new \Galmi\Xacml\Match('Action', 'edit');

        $allOf1 = new \Galmi\Xacml\TargetAllOf();
        $allOf1->addMatch($matchRole);

        $allOf2 = new \Galmi\Xacml\TargetAllOf();
        $allOf2->addMatch($matchObject);
        $allOf2->addMatch($matchAction);

        $anyOf = new \Galmi\Xacml\TargetAnyOf();
        $anyOf->addTargetAllOf($allOf1);
        $anyOf->addTargetAllOf($allOf2);

        $target = new \Galmi\Xacml\Target();
        $target->addTargetAnyOf($anyOf);
        $this->assertEquals(\Galmi\Xacml\Match::MATCH, $target->evaluate($request), 'Test 7 is Match');
    }

    /**
     * Test 8
     *
     * Subject.role == Array()
     *
     * Result = Indeterminate
     *
     * @expectedException \Galmi\Xacml\Exception\IndeterminateException
     */
    public function testEvaluate8()
    {
        $request = $this->createRequest();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::ATTRIBUTE_FINDER, $this->createAttributeFinder());

        $matchMock = $this->getMockBuilder('\Galmi\Xacml\Match')
            ->setConstructorArgs(array('Subject.role', 'Manager'))
            ->getMock();
        $matchMock->method('evaluate')
            ->will(
                $this->returnCallback(function() {
                    throw new \Exception('test exception');
                })
            );
        $allOf = new \Galmi\Xacml\TargetAllOf();
        $allOf->addMatch($matchMock);
        $anyOf = new \Galmi\Xacml\TargetAnyOf();
        $anyOf->addTargetAllOf($allOf);
        $target = new \Galmi\Xacml\Target();
        $target->addTargetAnyOf($anyOf);
        $this->assertEquals(\Galmi\Xacml\Match::INDETERMINATE, $target->evaluate($request), 'Test 8 is Indeterminate');
    }

}