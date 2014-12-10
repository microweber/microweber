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

    public static function fire($api_function, $data = false)
    {





        if (isset(self::$hooks[$api_function])) {
            $fn = self::$hooks[$api_function];
            if (function_exists($fn)) {
                return $fn($data);
            }


        } else if (is_string($api_function) and function_exists($api_function)) {

            return $api_function($data);

        }


        return Event::fire($api_function, array($data));
    }

    public static function event_bind($hook_name, $callback = false)
    {

        if (is_string($callback) and function_exists($callback)) {
            self::$hooks[$hook_name] = $callback;
        } else {



            Event::listen($hook_name, $callback);
        }

    }
}



