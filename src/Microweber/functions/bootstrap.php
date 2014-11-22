<?php

if (version_compare(phpversion(), "5.3.0", "<=")) {
    exit("Error: You must have PHP version 5.3 or greater to run Microweber");
}


if (!defined('MW_VERSION')) {
    define('MW_VERSION', 0.96);
}

function mw($class = null)
{
    if ($class == null or $class == false or strtolower($class) == 'application') {
        return app();
    } else {
        return app()->make($class);
    }
}

if (!function_exists('d')) {
    function d($dump)
    {
        var_dump($dump);
    }
}

//function mw_set_application($app)
//{
//    global $____mw_global_object;
//    $____mw_global_object = $app;
//    return $____mw_global_object;
//}