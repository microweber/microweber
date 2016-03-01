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
 * Event.
 *
 * @category Event
 * @desc  Event
 */
class Event
{
    /**
     * An instance of the event adapter to use.
     *
     * @var
     */
    public static $adapter;

    private $callbacks = array(); // array to store user callbacks

    public function __construct()
    {
        if (!is_object(self::$adapter)) {
            self::$adapter = new LaravelEvent();
        }

        register_shutdown_function(array($this, 'callRegisteredShutdown'));
    }

    public function on($event_name, $callback)
    {
        return self::$adapter->listen($event_name, $callback);
    }

    /**
     * Emits event.
     *
     * @param $event_name
     * @param bool|callable|mixed $data
     *
     * @return array|mixed|false
     */
    public function trigger($event_name, $data = false)
    {
        $args = func_get_args();
        $query = array_shift($args);
        if (count($args) == 1) {
            $args = $args[0];
        }

        return self::$adapter->fire($event_name, $args);
    }

    public function registerShutdownEvent()
    {
        $callback = func_get_args();

        if (empty($callback)) {
            trigger_error('No callback passed to '.__FUNCTION__.' method', E_USER_ERROR);

            return false;
        }
        if (!is_callable($callback[0])) {
            trigger_error('Invalid callback passed to the '.$callback[0].__FUNCTION__.' method', E_USER_ERROR);

            return false;
        }
        $this->callbacks[] = $callback;

        return true;
    }

    public function callRegisteredShutdown()
    {
        if (!empty($this->callbacks)) {
            foreach ($this->callbacks as $arguments) {
                $callback = array_shift($arguments);
                call_user_func_array($callback, $arguments);
            }
        }
    }
}
