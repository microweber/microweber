<?php


class Filter
{
    public static $filters = array();

    public static function bind($filter_name, $callback)
    {
        self::$filters[$filter_name] = $callback;
    }

    public static function get($filter_name, $params, $app)
    {
        if (isset(self::$filters[$filter_name])) {
            $fn = self::$filters[$filter_name];

            return call_user_func($fn, $params, $app);
        }

        return $app;
    }
}
