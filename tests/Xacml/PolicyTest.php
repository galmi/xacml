<?php

class PolicyTest extends PHPUnit_Framework_TestCase
{

    /**
     *  ---------------------------------------------------------------------------
     * |    Target       | Rule values |              Policy Value                 |
     *  ---------------------------------------------------------------------------
     * | “Match”         | Don’t care  | Specified by the rule-combining algorithm |
     *  ---------------------------------------------------------------------------
     */
    public function testEvaluate1()
    {
        $request = new \Galmi\Xacml\Request();
        $target = new \Galmi\Xacml\Target();
        $policy = new \Galmi\Xacml\Policy($target, 'deny-overrides');

        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::PERMIT));
        $this->addAlgorithmFactory($algorithm);

        $this->assertEquals(\Galmi\Xacml\Decision::PERMIT, $policy->evaluate($request));
    }

    /**
     *  ---------------------------------------------------------------------------
     * |    Target       | Rule values |              Policy Value                 |
     *  ---------------------------------------------------------------------------
     * | “Match”         | Don’t care  | Specified by the rule-combining algorithm |
     *  ---------------------------------------------------------------------------
     */
    public function testEvaluate2()
    {
        $request = new \Galmi\Xacml\Request();
        $target = $this->getMockBuilder('\\Galmi\\Xacml\\Target')
            ->setMethods(['evaluate'])
            ->getMock();
        $target->method('evaluate')->willReturn(\Galmi\Xacml\Match::MATCH);
        $policy = new \Galmi\Xacml\Policy($target, 'deny-overrides');

        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::PERMIT));
        $this->addAlgorithmFactory($algorithm);

        $this->assertEquals(\Galmi\Xacml\Decision::PERMIT, $policy->evaluate($request));
    }

    /**
     *  ---------------------------------------------------------------------------
     * |    Target       | Rule values |              Policy Value                 |
     *  ---------------------------------------------------------------------------
     * | “No-match”      | Don’t care  | “NotApplicable”                           |
     *  ---------------------------------------------------------------------------
     */
    public function testEvaluate3()
    {
        $request = new \Galmi\Xacml\Request();
        $target = $this->getMockBuilder('\\Galmi\\Xacml\\Target')
            ->setMethods(['evaluate'])
            ->getMock();
        $target->method('evaluate')->willReturn(\Galmi\Xacml\Match::NOT_MATCH);
        $policy = new \Galmi\Xacml\Policy($target, 'deny-overrides');

        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::PERMIT));
        $this->addAlgorithmFactory($algorithm);

        $this->assertEquals(\Galmi\Xacml\Decision::NOT_APPLICABLE, $policy->evaluate($request));
    }

    /**
     *  ---------------------------------------------------------------------------
     * |    Target       | Rule values |              Policy Value                 |
     *  ---------------------------------------------------------------------------
     * | “Indeterminate” | See Table 7 | See Table 7                               |
     *  ---------------------------------------------------------------------------
     *
     * Table 7
     *  --------------------------------------------------------
     * | Combining algorithm Value | Policy set or policy Value |
     *  --------------------------------------------------------
     * | “NotApplicable”           | “NotApplicable”            |
     * | “Permit”                  | “Indeterminate{P}”         |
     * | “Deny”                    | “Indeterminate{D}”         |
     * | “Indeterminate”           | “Indeterminate{DP}”        |
     * | “Indeterminate{DP}”       | “Indeterminate{DP}”        |
     * | “Indeterminate{P}”        | “Indeterminate{P}”         |
     * | “Indeterminate{D}”        | “Indeterminate{D}”         |
     *  --------------------------------------------------------
     *
     */
    public function testEvaluate4()
    {
        $request = new \Galmi\Xacml\Request();
        $target = $this->getMockBuilder('\\Galmi\\Xacml\\Target')
            ->setMethods(['evaluate'])
            ->getMock();
        $target->method('evaluate')->willReturnCallback(function(){
            throw new \Galmi\Xacml\Exception\IndeterminateException;
        });
        $policy = new \Galmi\Xacml\Policy($target, 'deny-overrides');

        // Line 1
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::NOT_APPLICABLE));
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::NOT_APPLICABLE, $policy->evaluate($request));

        // Line 2
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::PERMIT));
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_P, $policy->evaluate($request));

        // Line 3
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::DENY));
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D, $policy->evaluate($request));

        // Line 4
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::INDETERMINATE));
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D_P, $policy->evaluate($request));

        // Line 5
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::INDETERMINATE_D_P));
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D_P, $policy->evaluate($request));

        // Line 6
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::INDETERMINATE_P));
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_P, $policy->evaluate($request));

        // Line 7
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::INDETERMINATE_D));
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D, $policy->evaluate($request));
    }

    /**
     * @expectedException \Galmi\Xacml\Exception\IndeterminateException
     */
    public function testEvaluate5()
    {
        $request = new \Galmi\Xacml\Request();
        $target = $this->getMockBuilder('\\Galmi\\Xacml\\Target')
            ->setMethods(['evaluate'])
            ->getMock();
        $target->method('evaluate')->willReturnCallback(function(){
            throw new \Galmi\Xacml\Exception\IndeterminateException;
        });
        $policy = new \Galmi\Xacml\Policy($target, 'deny-overrides');

        // Line 4
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')->willReturnCallback(function(){
            throw new \Galmi\Xacml\Exception\IndeterminateException;
        });
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D_P, $policy->evaluate($request));
    }

    protected function addAlgorithmFactory($algorithm)
    {
        $combiningAlgorithmFactory = $this
            ->getMockBuilder('\\Galmi\\Xacml\\CombiningAlgorithmRegistry')
            ->setMethods(['getCombiningAlgorithm'])
            ->getMock();
        $combiningAlgorithmFactory
            ->method('getCombiningAlgorithm')
            ->willReturn($algorithm);
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::COMBINING_ALGORITHM_REGISTRY, $combiningAlgorithmFactory);
    }

    public function testAddRule()
    {
        $target = new \Galmi\Xacml\Target();
        $policy = new \Galmi\Xacml\Policy($target, 'combine-alg');

        $rule1 = new \Galmi\Xacml\Rule(\Galmi\Xacml\Decision::PERMIT);
        $rule2 = new \Galmi\Xacml\Rule(\Galmi\Xacml\Decision::DENY);

        $policy->addRule($rule1);
        $policy->addRule($rule2);

        $this->assertEquals([$rule1, $rule2], $policy->getRules());
    }

    public function testAddRule2()
    {
        $target = new \Galmi\Xacml\Target();
        $rule1 = new \Galmi\Xacml\Rule(\Galmi\Xacml\Decision::PERMIT);
        $rule2 = new \Galmi\Xacml\Rule(\Galmi\Xacml\Decision::DENY);

        $policy = new \Galmi\Xacml\Policy($target, 'combine-alg', [$rule1, $rule2]);

        $this->assertEquals([$rule1, $rule2], $policy->getRules());
    }

    public function testRemoveRule()
    {
        $target = new \Galmi\Xacml\Target();
        $policy = new \Galmi\Xacml\Policy($target, 'combine-alg');

        $rule1 = new \Galmi\Xacml\Rule(\Galmi\Xacml\Decision::PERMIT);
        $rule2 = new \Galmi\Xacml\Rule(\Galmi\Xacml\Decision::DENY);

        $policy->addRule($rule1);
        $policy->addRule($rule2);

        $policy->removeRule($rule1);

        $this->assertEquals([$rule2], $policy->getRules());
    }

    public function testSetters()
    {
        $target = new \Galmi\Xacml\Target();
        $policy = new \Galmi\Xacml\Policy($target, 'combine-alg');

        $policy->setVersion(1);
        $this->assertEquals(1, $policy->getVersion());

        $policy->setDescription('descr');
        $this->assertEquals('descr', $policy->getDescription());
    }
}
