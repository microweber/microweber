<?php

namespace MicroweberPackages\Form\Binding;

class BoundData
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function get($name, $default = null)
    {
        return $this->dotGet($this->transformKey($name), $default);
    }

    public function data()
    {
        return $this->data;
    }

    protected function dotGet($dotKey, $default)
    {
        $keyParts = explode('.', $dotKey);

        return $this->dataGet($this->data, $keyParts, $default);
    }

    protected function dataGet($target, $keyParts, $default)
    {
        if (count($keyParts) == 0) {
            return $target;
        }

        if (is_array($target)) {
            return $this->arrayGet($target, $keyParts, $default);
        }

        if (is_object($target)) {
            return $this->objectGet($target, $keyParts, $default);
        }

        return $default;
    }

    protected function arrayGet($target, $keyParts, $default)
    {
        $key = array_shift($keyParts);

        if (! isset($target[$key])) {
            return $default;
        }

        return $this->dataGet($target[$key], $keyParts, $default);
    }

    protected function objectGet($target, $keyParts, $default)
    {
        $key = array_shift($keyParts);

        if (! (property_exists($target, $key) || method_exists($target, '__get'))) {
            return $default;
        }

        return $this->dataGet($target->{$key}, $keyParts, $default);
    }

    protected function transformKey($key)
    {
        return str_replace(['[]', '[', ']'], ['', '.', ''], $key);
    }
}
