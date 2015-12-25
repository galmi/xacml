<?php

namespace Galmi\Xacml\Func\Equality;


use Galmi\Xacml\Config;
use Galmi\Xacml\Func\AbstractEquality;
use Galmi\Xacml\FuncRegistry;

/**
 * This function SHALL take two arguments
 * The result SHALL be “True” if and only if the two strings are equal as defined by 'string-equal'
 * after they have both been converted to lower case with 'string-normalize-to-lower-case'.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class StringEqualIgnoreCase extends AbstractEquality
{

    /**
     * @var FuncRegistry
     */
    protected $funcFactory;

    public function __construct()
    {
        $this->funcFactory = Config::get(Config::FUNC_REGISTRY);
    }

    /**
     * @inheritdoc
     */
    protected function bringType($value)
    {
        return (string)$this->funcFactory->get('string-normalize-to-lower-case')->evaluate([$value]);
    }
}