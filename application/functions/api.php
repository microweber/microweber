<?php







function api($function_name, $params = false)
{
    static $c;

    if ($c == false) {
        if (!defined('MW_API_RAW')) {
            define('MW_API_RAW', true);
        }
        $c = new MwController();

    }
    $res = $c->api($function_name, $params);
    return $res;

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

function exec_action($api_function, $data = false)
{
    global $mw_action_hook_index;
    $hooks = $mw_action_hook_index;


    if (isset($hooks[$api_function]) and is_array($hooks[$api_function]) and !empty($hooks[$api_function])) {

        foreach ($hooks[$api_function] as $hook_key => $hook_value) {

            if (function_exists($hook_value)) {
                if ($data != false) {
                    $hook_value($data);
                } else {

                    $hook_value();
                }
                unset($hooks[$api_function][$hook_key]);

            } else {


                try {
                    if ($data != false) {
                        call_user_func($hook_value,$data); // As of PHP 5.3.0
                    } else {
                        call_user_func($hook_value,false);
                    }
                } catch (Exception $e) {

                }

            }
        }
    }
}

$mw_action_hook_index = array();
function action_hook($function_name, $next_function_name = false)
{
    global $mw_action_hook_index;

    if (is_bool($function_name)) {
        $mw_action_hook_index = ($mw_action_hook_index);
        return $mw_action_hook_index;
    } else {
        if (!isset($mw_action_hook_index[$function_name])) {
            $mw_action_hook_index[$function_name] = array();
        }

        $mw_action_hook_index[$function_name][] = $next_function_name;

        //  $index .= ' ' . $function_name;
    }
}

$mw_api_hooks = array();
function api_hook($function_name, $next_function_name = false)
{
    //static $index = array();
    global $mw_api_hooks;
    if (is_bool($function_name)) {
        $index = array_unique($mw_api_hooks);
        return $index;
    } else {
        //d($function_name);
        $function_name = trim($function_name);
        $mw_api_hooks[$function_name][] = $next_function_name;

        // $index .= ' ' . $function_name;
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
                //                for ($index = 0; $index < 20000; $index++) {
                //                     $l = $api_function($l);
                //                }
                $l = $api_function($l);
            }
        }
    }
    //$l = parse_micrwober_tags($l, $options = false);

    return $l;
}

/* JS Usage:
 *
 * var source = new EventSource('<?php print site_url('api/event_stream')?>');
 *	source.onmessage = function (event) {
 *
 * 	mw.$('#mw-admin-manage-orders').html(event.data);
 *	};
 *
 *
 *  */
api_expose('event_stream');
function event_stream()
{

    header("Content-Type: text/event-stream\n\n");

    for ($i = 0; $i < 10; $i++) {

        echo 'data: ' . $i . rand() . rand() . rand() . rand() . rand() . rand() . "\n";

    }

    exit();
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

$mw_var_storage = array();
function mw_vars_destroy()
{
    global $mw_var_storage;
    $mw_var_storage = array();
}

function mw_var($key, $new_val = false)
{
    global $mw_var_storage;
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




action_hook('mw_cron', 'mw_cron');
api_expose('mw_cron');
function mw_cron()
{






    $file_loc = CACHEDIR_ROOT . "cron" . DS;
    $file_loc_hour = $file_loc . 'cron_lock' .'.php';

    $time = time();
    if(!is_file($file_loc_hour)){
        @touch($file_loc_hour);
    } else {
        if((filemtime($file_loc_hour)) >  $time - 2){
            touch($file_loc_hour);
            return true;
        }
    }



    // touch($file_loc_hour);
    $cron = new \mw\utils\Cron;
    //$cron->run();

    $scheduler = new \mw\utils\Events();

    // schedule a global scope function:
    //$scheduler->registerShutdownEvent("\mw\utils\Backup", $params);

    $scheduler->registerShutdownEvent(array($cron, 'run'));

    $file_loc = CACHEDIR_ROOT . "cron" . DS;

    $some_hour = date('Ymd');
    $file_loc_hour = $file_loc . 'cron_lock' . $some_hour . '.php';
    if (is_file($file_loc_hour)) {
        return true;
    } else {

        $opts = get_options("option_key2=cronjob");
        if ($opts != false) {

            //d($file_loc);
            if (!is_dir($file_loc)) {
                if (!mkdir($file_loc)) {
                    return false;
                }
            }

            if (!defined('MW_CRON_EXEC')) {
                define('MW_CRON_EXEC', true);
            }

            foreach ($opts as $item) {

                if (isset($item['module']) and $item['module'] != '' and is_module_installed($item['module'])) {
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