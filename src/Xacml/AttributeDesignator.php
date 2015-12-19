<?php

namespace Galmi\Xacml;


interface AttributeDesignator
{
    /**
     * @param Request $request
     * @param $attributeId
     * @return mixed
     */
    public function getValue(Request $request, $attributeId);
}