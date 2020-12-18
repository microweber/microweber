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

    public function response($event_name, $criteria)
    {
        $override = $this->trigger($event_name, $criteria);

        if (is_array($override) and !empty($override)) {
            $original_criteria = $criteria;
            foreach ($override as $resp) {
                if (is_array($resp) and !empty($resp)) {
                    $keys_diff = array_diff_key($original_criteria, $resp);
                    if ($keys_diff) {
                        foreach ($keys_diff as $keys_diff_orig_key => $keys_diff_orig_value) {
                            if (!isset($resp[$keys_diff_orig_key])) {
                                unset($criteria[$keys_diff_orig_key]);
                            }
                        }
                    }
                    foreach ($resp as $resp_key => $resp_value) {
                        if (is_string($resp_key) || is_numeric($resp_key)) {
                            if (substr($resp_key, 0, 2) == '__') {
                                $criteria[$resp_key] = $resp_value;
                            }
                            if (isset($original_criteria[$resp_key]) and ($original_criteria[$resp_key] != $resp_value)) {
                                $criteria[$resp_key] = $resp_value;
                            }
                        }

                    }
                }
            }
        }

        return $criteria;
    }

    public function registerShutdownEvent()
    {
        $callback = func_get_args();

        if (empty($callback)) {
            trigger_error('No callback passed to ' . __FUNCTION__ . ' method', E_USER_ERROR);

            return false;
        }
        if (!is_callable($callback[0])) {
            trigger_error('Invalid callback passed to the ' . $callback[0] . __FUNCTION__ . ' method', E_USER_ERROR);

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
