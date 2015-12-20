<?php

namespace Galmi\Xacml;


class Request
{
    /**
     * @var array
     */
    protected $data = array();

    public function __construct($data = array())
    {
        $this->data = $data;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return null;
    }

}