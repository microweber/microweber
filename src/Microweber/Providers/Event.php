<?php


/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://microweber.com/license/
 *
 */


namespace Microweber\Providers;


use Microweber\Utils\Adapters\Event\LaravelEvent as LaravelEvent;


/**
 * Content class is used to get and save content in the database.
 *
 * @package Content
 * @category Content
 * @desc  These functions will allow you to get and save content in the database.
 *
 */
class Event
{

    /**
     * An instance of the event adapter to use
     *
     * @var $adapter
     */
    public $adapter;

    function __construct()
    {
        $this->adapter = new LaravelEvent();


    }

    public function on($event_name, $callback)
    {
        return $this->adapter->listen($event_name, $callback);
    }

    /**
     * Emits event
     *
     * @param $event_name
     * @param bool|callable|mixed $data
     * @return array|mixed|false
     */
    public function trigger($event_name, $data = false)
    {



        return $this->adapter->fire($event_name, $data);

    }

}


