<?php
namespace Weber\Utils\Adapters\Event;

use Illuminate\Cache\tags;
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
        return Event::fire($api_function, $data);
    }
    public static function event_bind($function_name, $callback = false)
    {
        Event::listen($function_name, $callback);
    }
}



