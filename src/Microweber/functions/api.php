<?php
function router($constructor_params = null)
{
    global $_mw_global_router_object;

    if (!is_object($_mw_global_router_object)) {
        $_mw_global_router_object = \Microweber\Router::getInstance($constructor_params);
    }
    return $_mw_global_router_object;
}

function api($function_name, $params = false)
{
    static $c;

    if ($c == false) {
        if (!defined('MW_API_RAW')) {
            define('MW_API_RAW', true);
        }
        $c = new \Microweber\Controller();

    }
    $res = $c->api($function_name, $params);
    return $res;

}

function api_link($str = '')
{
    return mw('url')->api_link($str);

}


function api_expose($function_name)
{
    static $index = ' ';
    if (is_bool($function_name)) {
        return $index;
    } else {
        $index .= ' ' . $function_name;
    }
}

function api_expose_admin($function_name)
{
    static $index = ' ';
    if (is_bool($function_name)) {
        return $index;
    } else {
        $index .= ' ' . $function_name;
    }
}

function api_hook($function_name, $next_function_name = false)
{
    static $mw_api_hooks;
    if (is_bool($function_name)) {
        if (is_array($mw_api_hooks)) {
            $index = ($mw_api_hooks);
            return $index;
        }

    } else {
        $function_name = trim($function_name);
        $mw_api_hooks[$function_name][] = $next_function_name;

    }
}

function document_ready($function_name)
{
    static $index = ' ';
    if (is_bool($function_name)) {

        return $index;
    } else {
        $index .= ' ' . $function_name;
    }
}

function execute_document_ready($l)
{

    $document_ready_exposed = (document_ready(true));

    if ($document_ready_exposed != false) {
        $document_ready_exposed = explode(' ', $document_ready_exposed);
        $document_ready_exposed = array_unique($document_ready_exposed);
        $document_ready_exposed = array_trim($document_ready_exposed);

        foreach ($document_ready_exposed as $api_function) {
            if (function_exists($api_function)) {
                $l = $api_function($l);
            }
        }
    }

    return $l;
}


function array_to_module_params($params, $filter = false)
{
    $str = '';
    if (is_array($params)) {
        foreach ($params as $key => $value) {

            if ($filter == false) {
                $str .= $key . '="' . $value . '" ';
            } else if (is_array($filter) and !empty($filter)) {
                if (in_array($key, $filter)) {
                    $str .= $key . '="' . $value . '" ';
                }
            } else {
                if ($key == $filter) {
                    $str .= $key . '="' . $value . '" ';
                }
            }

        }
    }
    return $str;
}


function parse_params($params)
{
    $params2 = array();
    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
        unset($params2);
    }
    return $params;
}


function mw_var($key, $new_val = false)
{
    static $mw_var_storage;
    $contstant = ($key);
    if ($new_val == false) {
        if (isset($mw_var_storage[$contstant]) != false) {
            return $mw_var_storage[$contstant];
        } else {
            return false;
        }
    } else {
        if (isset($mw_var_storage[$contstant]) == false) {
            $mw_var_storage[$contstant] = $new_val;
            return $new_val;
        }
    }
    return false;
}


event_bind('mw_cron', 'mw_cron');
api_expose('mw_cron');
function mw_cron()
{

    $file_loc = MW_CACHE_ROOT_DIR . "cron" . DS;

    if (!is_dir($file_loc)) {
        mkdir_recursive($file_loc);
    }
    $file_loc_hour = $file_loc . 'cron_lock' . '.php';
    $time = time();
    if (!is_file($file_loc_hour)) {
        @touch($file_loc_hour);
    } else {
        if ((filemtime($file_loc_hour)) > $time - 4) {
            @touch($file_loc_hour);
            return true;
        }
    }
    // touch($file_loc_hour);
    $cron = new \Microweber\Utils\Cron;
    //$cron->run();

    $scheduler = new \Microweber\Utils\Events();

    // schedule a global scope function:
    //$scheduler->registerShutdownEvent("\Microweber\Utils\Backup", $params);

    $scheduler->registerShutdownEvent(array($cron, 'run'));

    $file_loc = MW_CACHE_ROOT_DIR . "cron" . DS;

    $some_hour = date('Ymd');
    $file_loc_hour = $file_loc . 'cron_lock' . $some_hour . '.php';
    if (is_file($file_loc_hour)) {
        return true;
    } else {

        $opts = mw('option')->get("option_key2=cronjob");
        if ($opts != false) {
            if (!is_dir($file_loc)) {
                if (!mkdir($file_loc)) {
                    return false;
                }
            }

            if (!defined('MW_CRON_EXEC')) {
                define('MW_CRON_EXEC', true);
            }

            foreach ($opts as $item) {

                if (isset($item['module']) and $item['module'] != '' and mw('module')->is_installed($item['module'])) {
                    if (isset($item['option_value']) and $item['option_value'] != 'n') {
                        $when = strtotime($item['option_value']);
                        if ($when != false) {
                            $when_date = date('Ymd', $when);
                            $file_loc_date = $file_loc . '' . $item['option_key'] . $item['id'] . $when_date . '.php';
                            if (!is_file($file_loc_date)) {
                                touch($file_loc_date);
                                $md = module_data('module=' . $item['module'] . '/cron');
                            }
                        }
                    }
                } else {
                    //	d($item);
                }
            }
            touch($file_loc_hour);
        }
    }
}


function event_trigger($api_function, $data = false)
{
    $event = new \Microweber\Event();
    return $event->emit($api_function, $data);
}

function action_hook($function_name, $next_function_name = false)
{
    return event_bind($function_name, $next_function_name);
}

/**
 * Adds event callback
 *
 * @param $function_name
 * @param bool|mixed|callable $next_function_name
 * @return array|mixed|false
 */
function event_bind($function_name, $next_function_name = false)
{
    $event = new \Microweber\Event();
    return $event->on($function_name, $next_function_name);


}