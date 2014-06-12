<?php


namespace Microweber;


class Orm
{


    /**
     * An instance of the Microweber Application class
     *
     * @var $app
     */
    public $app;
    /**
     * An instance of the ORM adapter to use
     *
     * @var $adapter
     */
    public $adapter;

    function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = Application::getInstance();
            }
        }
        if (!is_object($this->adapter)) {
            if (!isset($this->app->adapters->container['orm'])) {
                $app = $this->app;
                $this->app->adapters->container['orm'] = function ($c) use ($app) {
                    return new Adapters\Orm\IdiOrm($app);
                };
            }
            $this->adapter = $this->app->adapters->container['orm'];
        }
        return $this->adapter;
    }

    function __call($method, $arg=null)
    {

        return $this->adapter->$method($arg);

    }
    function with($table,$params = false)
    {
        return $this->adapter->with($table,$params);
    }
    function one($table,$params = false)
    {
        return $this->adapter->one($table,$params);
    }
    function get($table,$params = false)
    {
        return $this->adapter->get($table,$params);
    }

}