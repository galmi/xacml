<?php

namespace Galmi\Xacml;

/**
 * The <Expression> class signifies that an class that extends the ExpressionType
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
abstract class Expression implements Evaluable
{
    /**
     * Evaluate expression
     *
     * @param Request $request
     * @return bool|string
     */
    abstract public function evaluate(Request $request);
}