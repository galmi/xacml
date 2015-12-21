<?php

namespace Galmi\Xacml\Expression;


use Galmi\Xacml\Request;

/**
 * PIP Class Interface for search value of attributeId
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
interface AttributeFinder
{
    /**
     * Retrieve value by attributeId from request context
     *
     * @param Request $request
     * @param string $attributeId
     * @return mixed
     */
    public function getValue(Request $request, $attributeId);
}