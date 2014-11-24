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
                $this->app = wb();
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

    function __call($method, $arg = null)
    {

        return $this->adapter->$method($arg);

    }

    function filter($key, $callback)
    {
        return $this->adapter->filter($key, $callback);
    }

    function configure($key, $val = false, $connection_name = 'default')
    {
        return $this->adapter->configure($key, $val, $connection_name);
    }

    function with($table, $params = false, $connection = 'default')
    {
        return $this->adapter->with($table, $params, $connection);
    }

    function one($table, $params = false)
    {
        return $this->adapter->one($table, $params);
    }
    function debug()
    {


        return $this->adapter->debug();
    }
    function get($table, $params = false)
    {


        return $this->adapter->get($table, $params);
    }

}