<?php

namespace Galmi\Xacml;

/**
 * Interface for evaluable classes
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
interface Evaluable
{
    /**
     * Evaluate value
     *
     * @param Request $request
     * @return string|bool
     */
    public function evaluate(Request $request);
}