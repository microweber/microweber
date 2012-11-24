<?php

// Track object changes
class Stateful {

    protected $d = array(), $c = array(), $l;

    function set($a) {
        foreach ($a as $k => $v)
            $this->$k = $v;
    }

    function __set($k, $v) {
        $t = $this;
        if (!array_key_exists($k, $t->d) or $t->d [$k] !== $v) {
            $t->d [$k] = $v;
            $t->c [$k] = $k;
        }
    }

    function __get($k) {
        return array_key_exists($k, $this->d) ? $this->d [$k] : NULL;
    }

    function __isset($k) {
        return array_key_exists($k, $this->d);
    }

    function __unset($k) {
        unset($this->d [$k], $this->c [$k]);
    }

    function clear() {
        $this->d = $this->c = array();
    }

    function values() {
        return $this->d;
    }

    function changed() {
        return (bool) $this->c;
    }

    function changes() {
        if ($this->c) {
            $a = array();
            foreach ($this->c as $k) {
                $a [$k] = $this->d [$k];
            }
            return $a;
        }
    }

}