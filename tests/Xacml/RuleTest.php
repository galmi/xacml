<?php

class RuleTest extends PHPUnit_Framework_TestCase
{
    public function testSetters()
    {
        $rule = new \Galmi\Xacml\Rule();
        $rule->setEffect(\Galmi\Xacml\Decision::PERMIT);

        $this->assertEquals(\Galmi\Xacml\Decision::PERMIT, $rule->getEffect());

        $rule->setDescription('Description');
        $this->assertEquals('Description', $rule->getDescription());

        $attributeValue = new \Galmi\Xacml\Expression\AttributeValue(true);
        $rule->setCondition($attributeValue);
        $this->assertEquals($attributeValue, $rule->getCondition());

        $target = new \Galmi\Xacml\Target();
        $rule->setTarget($target);
        $this->assertEquals($target, $rule->getTarget());
    }

    /**
     *  -----------------------------------------------------------------------------------------
     * |       Target         |    Condition    |               Rule Value                       |
     *  -----------------------------------------------------------------------------------------
     * | “Match” or no target | “True”          | Effect                                         |
     * | “Match” or no target | “False”         | “NotApplicable”                                |
     *  -----------------------------------------------------------------------------------------
     */
    public function testEvaluate()
    {
        $request = $this->createRequest();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::ATTRIBUTE_FINDER, $this->createAttributeFinder());

        $effect = \Galmi\Xacml\Decision::PERMIT;
        $rule = new \Galmi\Xacml\Rule();
        $rule->setEffect($effect);

        $this->assertEquals($effect, $rule->evaluate($request));

        $matchRole = new \Galmi\Xacml\Match('Subject.role', 'Manager');
        $allOf = new \Galmi\Xacml\TargetAllOf();
        $allOf->addMatch($matchRole);
        $anyOf = new \Galmi\Xacml\TargetAnyOf();
        $anyOf->addTargetAllOf($allOf);
        $target = new \Galmi\Xacml\Target();
        $target->addTargetAnyOf($anyOf);
        $rule->setTarget($target);
        $this->assertEquals($effect, $rule->evaluate($request));

        $condition = new \Galmi\Xacml\Expression\AttributeValue(true);
        $rule->setCondition($condition);
        $this->assertEquals($effect, $rule->evaluate($request));

        $condition = new \Galmi\Xacml\Expression\AttributeValue(false);
        $rule->setCondition($condition);
        $this->assertEquals(\Galmi\Xacml\Decision::NOT_APPLICABLE, $rule->evaluate($request));
    }

    /**
     *  -----------------------------------------------------------------------------------------
     * |       Target         |    Condition    |               Rule Value                       |
     *  -----------------------------------------------------------------------------------------
     * | “No-match”           | Don’t care      | “NotApplicable”                                |
     *  -----------------------------------------------------------------------------------------
     */
    public function testEvaluate2()
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

        $rule = new \Galmi\Xacml\Rule();
        $rule->setEffect(\Galmi\Xacml\Decision::PERMIT);
        $rule->setTarget($target);
        $this->assertEquals(\Galmi\Xacml\Decision::NOT_APPLICABLE, $rule->evaluate($request));
    }

    /**
     *  -----------------------------------------------------------------------------------------
     * |       Target         |    Condition    |               Rule Value                       |
     *  -----------------------------------------------------------------------------------------
     * | “Match” or no target | “Indeterminate” | “Indeterminate{P}” if the Effect is Permit,    |
     * |                      |                 |    or “Indeterminate{D}” if the Effect is Deny |
     *  -----------------------------------------------------------------------------------------
     */
    public function testEvaluate3()
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

        $applyMock = $this->getMockForAbstractClass('\Galmi\Xacml\Expression');
        $applyMock->method('evaluate')
            ->willReturn(\Galmi\Xacml\Match::INDETERMINATE);

        $rule = new \Galmi\Xacml\Rule();
        $rule->setEffect(\Galmi\Xacml\Decision::PERMIT);
        $rule->setTarget($target);
        $rule->setCondition($applyMock);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_P, $rule->evaluate($request));

        $rule = new \Galmi\Xacml\Rule();
        $rule->setEffect(\Galmi\Xacml\Decision::DENY);
        $rule->setTarget($target);
        $rule->setCondition($applyMock);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D, $rule->evaluate($request));
    }

    /**
     *  -----------------------------------------------------------------------------------------
     * |       Target         |    Condition    |               Rule Value                       |
     *  -----------------------------------------------------------------------------------------
     * | “Indeterminate”      | Don’t care      | “Indeterminate{P}” if the Effect is Permit,    |
     * |                      |                 | or “Indeterminate{D}” if the Effect is Deny    |
     *  -----------------------------------------------------------------------------------------
     */
    public function testEvaluate4()
    {
        $request = $this->createRequest();
        $matchMock = $this->getMockBuilder('\Galmi\Xacml\Match')
            ->setConstructorArgs(array('Subject.role', 'Manager'))
            ->getMock();
        $matchMock->method('evaluate')
            ->willReturn(\Galmi\Xacml\Match::INDETERMINATE);
        $allOf = new \Galmi\Xacml\TargetAllOf();
        $allOf->addMatch($matchMock);
        $anyOf = new \Galmi\Xacml\TargetAnyOf();
        $anyOf->addTargetAllOf($allOf);
        $target = new \Galmi\Xacml\Target();
        $target->addTargetAnyOf($anyOf);

        $rule = new \Galmi\Xacml\Rule();
        $rule->setEffect(\Galmi\Xacml\Decision::PERMIT);
        $rule->setTarget($target);

        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_P, $rule->evaluate($request));

        $rule = new \Galmi\Xacml\Rule();
        $rule->setEffect(\Galmi\Xacml\Decision::DENY);
        $rule->setTarget($target);
        $this->assertEquals(\Galmi\Xacml\Decision::INDETERMINATE_D, $rule->evaluate($request));
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
}
