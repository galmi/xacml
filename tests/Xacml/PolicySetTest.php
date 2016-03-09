<?php

class PolicySetTest extends PHPUnit_Framework_TestCase
{

    /**
     *  ---------------------------------------------------------------------------
     * |    Target       | Rule values |              PolicySet Value              |
     *  ---------------------------------------------------------------------------
     * | “Match”         | Don’t care  | Specified by the rule-combining algorithm |
     *  ---------------------------------------------------------------------------
     */
    public function testEvaluate1()
    {
        $request = new \Galmi\Xacml\Request();
        $target = new \Galmi\Xacml\Target();
        $policySet = new \Galmi\Xacml\PolicySet();
        $policySet->setTarget($target);
        $policySet->setPolicyCombiningAlgId('deny-overrides');

        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::PERMIT));
        $this->addAlgorithmFactory($algorithm);

        $this->assertEquals(\Galmi\Xacml\Decision::PERMIT, $policySet->evaluate($request));
    }

    /**
     *  ---------------------------------------------------------------------------
     * |    Target       | Rule values |              PolicySet Value              |
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
        $policySet = new \Galmi\Xacml\PolicySet();
        $policySet->setTarget($target);
        $policySet->setPolicyCombiningAlgId('deny-overrides');

        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::PERMIT));
        $this->addAlgorithmFactory($algorithm);

        $this->assertEquals(\Galmi\Xacml\Decision::PERMIT, $policySet->evaluate($request));
    }

    /**
     *  ---------------------------------------------------------------------------
     * |    Target       | Rule values |              PolicySet Value              |
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
        $policySet = new \Galmi\Xacml\PolicySet();
        $policySet->setTarget($target);
        $policySet->setPolicyCombiningAlgId('deny-overrides');

        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::PERMIT));
        $this->addAlgorithmFactory($algorithm);

        $this->assertEquals(\Galmi\Xacml\Decision::NOT_APPLICABLE, $policySet->evaluate($request));
    }

    /**
     *  ---------------------------------------------------------------------------
     * |    Target       | Rule values |              PolicySet Value              |
     *  ---------------------------------------------------------------------------
     * | “Indeterminate” | See Table 7 | See Table 7                               |
     *  ---------------------------------------------------------------------------
     *
     * Table 7
     *  --------------------------------------------------------
     * | Combining algorithm Value | PolicySet set or policy Value |
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
        $target->method('evaluate')->willReturnCallback(
            function () {
                return \Galmi\Xacml\Match::INDETERMINATE;
            }
        );
        $policySet = new \Galmi\Xacml\PolicySet();
        $policySet->setTarget($target);
        $policySet->setPolicyCombiningAlgId('deny-overrides');

        // Line 1
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::NOT_APPLICABLE));
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::NOT_APPLICABLE, $policySet->evaluate($request));

        // Line 2
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::PERMIT));
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_P, $policySet->evaluate($request));

        // Line 3
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::DENY));
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D, $policySet->evaluate($request));

        // Line 4
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::INDETERMINATE));
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D_P, $policySet->evaluate($request));

        // Line 5
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::INDETERMINATE_D_P));
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D_P, $policySet->evaluate($request));

        // Line 6
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::INDETERMINATE_P));
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_P, $policySet->evaluate($request));

        // Line 7
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')
            ->will($this->returnValue(\Galmi\Xacml\Decision::INDETERMINATE_D));
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D, $policySet->evaluate($request));
    }

    public function testEvaluate5()
    {
        $request = new \Galmi\Xacml\Request();
        $target = $this->getMockBuilder('\\Galmi\\Xacml\\Target')
            ->setMethods(['evaluate'])
            ->getMock();
        $target->method('evaluate')->willReturnCallback(
            function () {
                return \Galmi\Xacml\Match::INDETERMINATE;
            }
        );
        $policySet = new \Galmi\Xacml\PolicySet();
        $policySet->setTarget($target);
        $policySet->setPolicyCombiningAlgId('deny-overrides');

        // Line 4
        $algorithm = $this->getMockBuilder('stdClass')
            ->setMethods(['evaluate'])
            ->getMock();
        $algorithm->method('evaluate')->willReturnCallback(
            function () {
                return \Galmi\Xacml\Match::INDETERMINATE;
            }
        );
        $this->addAlgorithmFactory($algorithm);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D_P, $policySet->evaluate($request));
    }

    protected function addAlgorithmFactory($algorithm)
    {
        $combiningAlgorithmFactory = $this
            ->getMockBuilder('\\Galmi\\Xacml\\CombiningAlgorithmRegistry')
            ->setMethods(['get'])
            ->getMock();
        $combiningAlgorithmFactory
            ->method('get')
            ->willReturn($algorithm);
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::COMBINING_ALGORITHM_REGISTRY, $combiningAlgorithmFactory);
    }

    public function testAddPolicySet()
    {
        $target = new \Galmi\Xacml\Target();
        $policySet = new \Galmi\Xacml\PolicySet($target, 'combine-alg');
        $policySet->setTarget($target);
        $policySet->setPolicyCombiningAlgId('combine-alg');

        $policySet1 = new \Galmi\Xacml\PolicySet();
        $policySet1->setPolicyCombiningAlgId('combine-alg1');
        $policySet2 = new \Galmi\Xacml\PolicySet();
        $policySet2->setPolicyCombiningAlgId('combine-alg2');

        $policySet->addPolicySet($policySet1);
        $policySet->addPolicySet($policySet2);

        $this->assertEquals([$policySet1, $policySet2], $policySet->getPolicySets());
    }

    public function testAddPolicy()
    {
        $target = new \Galmi\Xacml\Target();
        $policySet = new \Galmi\Xacml\PolicySet();
        $policySet->setTarget($target);
        $policySet->setPolicyCombiningAlgId('combine-alg');

        $policy1 = new \Galmi\Xacml\Policy();
        $policy2 = new \Galmi\Xacml\Policy();

        $policySet->addPolicy($policy1);
        $policySet->addPolicy($policy2);

        $this->assertEquals([$policy1, $policy2], $policySet->getPolicies());
    }

    protected static function getMethod($name)
    {
        $class = new ReflectionClass('\Galmi\Xacml\PolicySet');
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    public function testAddPolicySetPolicy()
    {
        $target = new \Galmi\Xacml\Target();
        $policySet = new \Galmi\Xacml\PolicySet();
        $policySet->setTarget($target);
        $policySet->setPolicyCombiningAlgId('combine-alg');

        $policySet1 = new \Galmi\Xacml\PolicySet();
        $policySet1->setTarget($target);
        $policySet1->setPolicyCombiningAlgId('combine-alg1');
        $policySet2 = new \Galmi\Xacml\PolicySet();
        $policySet2->setTarget($target);
        $policySet2->setPolicyCombiningAlgId('combine-alg2');
        $policySet->addPolicySet($policySet1);
        $policySet->addPolicySet($policySet2);

        $policy1 = new \Galmi\Xacml\Policy();
        $policy2 = new \Galmi\Xacml\Policy();
        $policySet->addPolicy($policy1);
        $policySet->addPolicy($policy2);

        $getPoliciesForCombingAlgorithm = self::getMethod('getPoliciesForCombingAlgorithm');

        $this->assertEquals(
            [$policySet1, $policySet2, $policy1, $policy2],
            $getPoliciesForCombingAlgorithm->invoke($policySet)
        );
    }

    public function testRemovePolicySet()
    {
        $target = new \Galmi\Xacml\Target();
        $policySet = new \Galmi\Xacml\PolicySet();
        $policySet->setTarget($target);
        $policySet->setPolicyCombiningAlgId('combine-alg');

        $policySet1 = new \Galmi\Xacml\PolicySet();
        $policySet1->setTarget($target);
        $policySet1->setPolicyCombiningAlgId('combine-alg1');
        $policySet2 = new \Galmi\Xacml\PolicySet();
        $policySet2->setTarget($target);
        $policySet2->setPolicyCombiningAlgId('combine-alg2');

        $policySet->addPolicySet($policySet1);
        $policySet->addPolicySet($policySet2);

        $policySet->removePolicySet($policySet1);

        $this->assertEquals([$policySet2], $policySet->getPolicySets());
    }

    public function testRemovePolicy()
    {
        $target = new \Galmi\Xacml\Target();
        $policySet = new \Galmi\Xacml\PolicySet();
        $policySet->setTarget($target);
        $policySet->setPolicyCombiningAlgId('combine-alg');

        $policy1 = new \Galmi\Xacml\Policy();
        $policy2 = new \Galmi\Xacml\Policy();

        $policySet->addPolicy($policy1);
        $policySet->addPolicy($policy2);

        $policySet->removePolicy($policy1);

        $this->assertEquals([$policy2], $policySet->getPolicies());
    }

    public function testSetters()
    {
        $target = new \Galmi\Xacml\Target();
        $policySet = new \Galmi\Xacml\PolicySet();

        $policySet->setDescription('descr');
        $this->assertEquals('descr', $policySet->getDescription());
    }
}
