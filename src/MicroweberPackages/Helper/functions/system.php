<?php

if (!function_exists('is_cli')) {
    function is_cli()
    {
        static $is;
        if ($is !== null) {
            return $is;
        }

        if(!empty($_SERVER) and isset($_SERVER['SERVER_SOFTWARE']) and isset($_SERVER['SERVER_PROTOCOL'])) {
            $is = false;
            return $is;
        }

        $php_sapi_name = false;
        if(defined('PHP_SAPI')){
            $php_sapi_name= PHP_SAPI;
        } else if (function_exists('php_sapi_name')){
            $php_sapi_name= php_sapi_name();
        }


        if (function_exists('php_sapi_name') and
            $php_sapi_name === 'apache2handler'
        ) {
            $is = false;
            return false;
        }


        if (
            defined('STDIN')
            or $php_sapi_name === 'cli'
            or $php_sapi_name === 'cli-server'
            or array_key_exists('SHELL', $_ENV)

        ) {
            $is = true;
            return true;
        }


        $is = false;
        return false;
    }
}



if (!function_exists('php_can_use_func')) {
    /**
     * Function to check if you can use a PHP function
     */
    function php_can_use_func($func_name)
    {
        if (!defined('INI_SYSTEM_CHECK_DISABLED')) {
            define('INI_SYSTEM_CHECK_DISABLED', ini_get('disable_functions'));
        }


        //if ($func_name == 'putenv') {
        $available = true;
        if (ini_get('safe_mode')) {
            $available = false;
        } else {
            $d = INI_SYSTEM_CHECK_DISABLED;
            $s = ini_get('suhosin.executor.func.blacklist');
            if ("$d$s") {
                $array = preg_split('/,\s*/', "$d,$s");
                if (in_array($func_name, $array)) {
                    $available = false;
                }
            }
        }

        if (str_contains(INI_SYSTEM_CHECK_DISABLED,  (string)$func_name)) {
            return false;
        }

        return $available;
        //}

        if (!strstr(INI_SYSTEM_CHECK_DISABLED, (string)$func_name)) {
            return true;
        }

    }
}
