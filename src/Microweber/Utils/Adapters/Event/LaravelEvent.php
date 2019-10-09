<?php

namespace Microweber\Utils\Adapters\Event;

use Illuminate\Support\Facades\Event;

class LaravelEvent
{
    public static $hooks = array();

    public static function listen($event_name, $callback)
    {
        return self::event_bind($event_name, $callback);
    }

    /**
     * @param $api_function
     * @param mixed $data
     *
     * @return mixed
     */
    public static function fire($api_function, $data = false)
    {
        if (isset(self::$hooks[$api_function])) {
            $fns = self::$hooks[$api_function];
            if (is_array($fns)) {
                $resp = array();
                foreach ($fns as $fn) {
                    if (is_callable($fn)) {
                        $resp[] = call_user_func($fn, $data);
                    } elseif (function_exists($fn)) {
                        $resp[] = $fn($data);
                    }
                }
                return $resp;
            }
        }

        $args = func_get_args();
        array_shift($args);
        if (count($args) == 1) {
            $args = $args[0];
            if (is_array($args)) {
                $args = array($args);
            }
        }

        return Event::fire($api_function, $args);
    }

    public static function event_bind($hook_name, $callback = false)
    {
        if (is_string($callback) and (function_exists($callback))) {
            if (!isset(self::$hooks[$hook_name])) {
                self::$hooks[$hook_name] = array();
            }
            self::$hooks[$hook_name][] = $callback;
        } else {
            // Laravel Event Listen not work properly
            self::$hooks[$hook_name][] = $callback;
            //Event::listen($hook_name, $callback);
        }
    }
}
