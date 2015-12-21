<?php

namespace Galmi\Xacml\Expression;


use Galmi\Xacml\Expression;
use Galmi\Xacml\FuncFactory;
use Galmi\Xacml\Request;

/**
 * The <Apply> class denotes application of a function to its arguments
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class Apply extends Expression
{

    /**
     * The identifier of the function to be applied to the arguments.
     *
     * @var string
     */
    protected $functionId;

    /**
     * A free-form description of the <Apply> class.
     *
     * @var string
     */
    protected $description;

    /**
     * Arguments to the function, which may include other functions.
     *
     * @var Expression[]
     */
    protected $expressions;

    /**
     * Apply constructor.
     * @param string $functionId
     * @param string $description
     */
    public function __construct($functionId, $description = '')
    {
        $this->functionId = $functionId;
        $this->description = $description;
    }

    /**
     * Getter for Expression
     *
     * @return Expression[]|[]
     */
    public function getExpressions()
    {
        return $this->expressions ?: $this->expressions = [];
    }

    /**
     * Add Expression
     *
     * @param Expression $expression
     * @return $this
     */
    public function addExpression(Expression $expression)
    {
        if (!in_array($expression, $this->getExpressions(), true)) {
            $this->expressions[] = $expression;
        }

        return $this;
    }

    /**
     * Remove Expression
     *
     * @param Expression $expression
     * @return $this|bool
     */
    public function removeExpression(Expression $expression)
    {
        if (in_array($expression, $this->getExpressions(), true)) {
            $key = array_search($expression, $this->expressions, true);
            if ($key === false) {
                return false;
            }

            unset($this->expressions[$key]);
            $this->expressions = array_values($this->expressions);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function evaluate(Request $request)
    {
        $funcFactory = new FuncFactory();
        $func = $funcFactory->getFunction($this->functionId);
        $values = [];
        foreach($this->getExpressions() as $expression) {
            $values[] = $expression->evaluate($request);
        }
        return $func->evaluate($values);
    }

}