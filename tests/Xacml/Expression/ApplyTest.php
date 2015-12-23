<?php

class ApplyTest extends PHPUnit_Framework_TestCase
{

    public function testAddExpression()
    {
        $apply = new \Galmi\Xacml\Expression\Apply('string-equal');

        $expression1 = new \Galmi\Xacml\Expression\AttributeValue('expression 1');
        $expression2 = new \Galmi\Xacml\Expression\AttributeValue('expression 2');

        $apply->addExpression($expression1);
        $apply->addExpression($expression2);

        $this->assertEquals([$expression1, $expression2], $apply->getExpressions());
    }

    public function testRemoveExpression()
    {
        $apply = new \Galmi\Xacml\Expression\Apply('string-equal');

        $expression1 = new \Galmi\Xacml\Expression\AttributeValue('expression 1');
        $expression2 = new \Galmi\Xacml\Expression\AttributeValue('expression 2');

        $apply->addExpression($expression1);
        $apply->addExpression($expression2);

        $apply->removeExpression($expression1);
        $this->assertEquals([$expression2], $apply->getExpressions());
    }

    public function testEvaluate()
    {
        $request = new \Galmi\Xacml\Request();
        $funcFactory = new \Galmi\Xacml\FuncFactory();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::FUNCTION_FACTORY, $funcFactory);

        $apply = new \Galmi\Xacml\Expression\Apply('string-equal');

        $expression1 = new \Galmi\Xacml\Expression\AttributeValue('expression 1');
        $expression2 = new \Galmi\Xacml\Expression\AttributeValue('expression 2');

        $apply->addExpression($expression1);
        $apply->addExpression($expression2);

        $this->assertEquals(false, $apply->evaluate($request));
    }

    public function testEvaluate2()
    {
        $request = new \Galmi\Xacml\Request();
        $funcFactory = new \Galmi\Xacml\FuncFactory();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::FUNCTION_FACTORY, $funcFactory);

        $apply1 = new \Galmi\Xacml\Expression\Apply('string-equal');
        $expression11 = new \Galmi\Xacml\Expression\AttributeValue('expression 1');
        $expression12 = new \Galmi\Xacml\Expression\AttributeValue('expression 1');
        $apply1->addExpression($expression11);
        $apply1->addExpression($expression12);

        $apply2 = new \Galmi\Xacml\Expression\Apply('string-equal');
        $expression21 = new \Galmi\Xacml\Expression\AttributeValue('expression 2');
        $expression22 = new \Galmi\Xacml\Expression\AttributeValue('expression 2');
        $apply2->addExpression($expression21);
        $apply2->addExpression($expression22);

        $apply = new \Galmi\Xacml\Expression\Apply('func-and');
        $apply->addExpression($apply1);
        $apply->addExpression($apply2);

        $this->assertEquals(true, $apply->evaluate($request));
    }

    public function testEvaluate3()
    {
        $request = new \Galmi\Xacml\Request();
        $funcFactory = new \Galmi\Xacml\FuncFactory();
        \Galmi\Xacml\Config::set(\Galmi\Xacml\Config::FUNCTION_FACTORY, $funcFactory);

        $apply1 = new \Galmi\Xacml\Expression\Apply('string-equal');
        $expression11 = new \Galmi\Xacml\Expression\AttributeValue('expression 1');
        $expression12 = new \Galmi\Xacml\Expression\AttributeValue('expression 1');
        $apply1->addExpression($expression11);
        $apply1->addExpression($expression12);

        $apply2 = new \Galmi\Xacml\Expression\Apply('string-equal');
        $expression21 = new \Galmi\Xacml\Expression\AttributeValue('expression 2');
        $expression22 = new \Galmi\Xacml\Expression\AttributeValue('expression 1');
        $apply2->addExpression($expression21);
        $apply2->addExpression($expression22);

        $apply = new \Galmi\Xacml\Expression\Apply('func-and');
        $apply->addExpression($apply1);
        $apply->addExpression($apply2);

        $this->assertEquals(false, $apply->evaluate($request));
    }
}
