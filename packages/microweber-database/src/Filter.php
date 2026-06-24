<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Database;

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
