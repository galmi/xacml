<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 20.12.15
 * Time: 16:12
 */

namespace Galmi\Xacml\Expression;


use Galmi\Xacml\Request;

interface AttributeFinder
{
    /**
     * @param Request $request
     * @param string $attributeId
     * @return mixed
     */
    public function getValue(Request $request, $attributeId);
}