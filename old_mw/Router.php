<?php

namespace Microweber;


class Router
{

    public static $_instance;
    /**
     * An instance of the Router adapter to use
     *
     * @var $adapter
     */
    public static $adapter;

    function __construct($adapter = null)
    {
        if (!is_object($adapter) and !is_object(self::$adapter)) {
            self::$adapter = new Adapters\Router\Generic();
        } elseif (is_object($adapter) and !is_object(self::$adapter)) {
            self::$adapter = $adapter;
        }
    }

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function get($url, $callback)
    {
        return self::$adapter->get($url, $callback);
    }

    public static function post($url, $callback)
    {
        return self::$adapter->post($url, $callback);
    }

    public static function map($controller)
    {
        return self::$adapter->map($controller);
    }

    public static function run()
    {
        return self::$adapter->run();
    }
}