<?php

namespace Microweber;


class Router
{

    public static $_instance;
    public $functions = array();
    public $vars = array();
    public $controller;
    public $routes = array();
    /**
     * An instance of the Router adapter to use
     *
     * @var $adapter
     */
    public $adapter;

    function __construct($adapter = null)
    {
        if (!is_object($adapter)) {
            $this->adapter = new Adapters\Router\Generic();
        } else {
            $this->adapter = $adapter;
        }
    }

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function get($url,$callback)
    {
        return $this->adapter->get($url,$callback);


    }
    function post($url,$callback)
    {
        return $this->adapter->post($url,$callback);


    }
    function map($controller)
    {
        return $this->adapter->map($controller);


    }

    function run()
    {
        return $this->adapter->run();

    }
}