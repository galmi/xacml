<?php

namespace Galmi\Xacml;

/**
 * The <Request> class is an abstraction layer used by the policy language.
 *
 * @author Ildar Galiautdinov <ildar@galmi.ru>
 */
class Request
{
    /**
     * Specifies information about attributes of the request context
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * Request constructor.
     * @param array $data
     */
    public function __construct($data = array())
    {
        $this->attributes = $data;
    }

    /**
     * Set $value to attributes with $key
     *
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Get value of attribute by $key
     *
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }
        return null;
    }

}