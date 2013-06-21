<?php

/* if (file_exists(__DIR__ . '/' . $_SERVER['REQUEST_URI'])) {
 //   return false; // serve the requested resource as-is.
 } */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
date_default_timezone_set('UTC');
// Setup system and load controller
define('T', $mtime);
unset($mtime);
define('M', memory_get_usage());
define('AJAX', strtolower(getenv('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest');

require_once ('bootstrap.php');
$c_file = MW_CONFIG_FILE;

$go_to_install = false;
if (!is_file($c_file)) {

    $default_config = INCLUDES_PATH . DS . 'install' . DS . 'config.base.php';
    copy($default_config, $c_file);
    $go_to_install = true;
}

require_once (MW_APPPATH . 'functions.php');
$installed = c('installed');
if (strval($installed) != 'yes') {
    define('MW_IS_INSTALLED', false);
} else {
    define('MW_IS_INSTALLED', true);
}

// require ('appication/functions.php');

require_once (MW_APPPATH_FULL . 'functions' . DS . 'mw_functions.php');

//set_error_handler('error');

function error($e, $f = false, $l = false)
{
    include_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'language.php');

    $v = new MwView(ADMIN_VIEWS_PATH . 'error.php');
    $v->e = $e;
    $v->f = $f;
    $v->l = $l;
    // _log($e -> getMessage() . ' ' . $e -> getFile());
    die($v);
}

$default_timezone = c('default_timezone');
if ($default_timezone == false or $default_timezone == '{default_timezone}') {

} else {
    date_default_timezone_set($default_timezone);
}


if (!defined('MW_BARE_BONES')) {


    if (!isset($controller) or !is_object($controller)) {
        $controller = new MwController();
    }


    if (MW_IS_INSTALLED != true or $go_to_install == true) {
        $controller->install();
        exit();
    }
    $close_conn = function_exists('db_query');
    $method_full = url_string();
    $m1 = url_segment(0);

    if ($m1) {
        $m1 = str_replace('.', '', $m1);
        $method = $m1;
    } else {
        $method = 'index';
    }

    $check_custom_controllers = MW_APPPATH_FULL . 'controllers' . DIRECTORY_SEPARATOR . $method . '.php';
    if (is_file($check_custom_controllers)) {
        include_once($check_custom_controllers);
        if (class_exists($method)) {
            $controller = new $method();
            $m1 = url_segment(1);

            if ($m1) {
                $m1 = str_replace('.', '', $m1);
                $method = $m1;
            } else {
                $method = 'index';
            }
        }
    }


    $params_for_route = url_segment();
    //loading custom routes
    $routes_file = MW_ROOTPATH . 'routes.php';
    if (is_file($routes_file)) {
        include_once($routes_file);
    }


//    $perform_routing = route_exec($method);
//    if($perform_routing != false){
//        return $perform_routing;
//    }


    $admin_url = c('admin_url');
    if ($method == 'admin' or $method == $admin_url) {
        if ($admin_url == $method) {

            if (!defined('IN_ADMIN')) {
                define('IN_ADMIN', true);
            }

            $controller->admin();
            if ($close_conn == true and $installed == true) {
                db_query('close');
            }
            exit();
        } else {
            if ($close_conn == true and $installed == true) {
                db_query('close');
            }
            error('No access allowed to admin');
            exit();
        }
    }

    if ($method == 'api.js') {
        $method = 'apijs';
    }


    //perform custom routing

    $is_custom_controller_called = false;
    if (is_object($controller) and isset($controller->functions) and is_array($controller->functions)) {
        //$params_for_route = url_segment();
 
        if (isset($controller->functions[$method])  and is_callable($controller->functions[$method])) {

            $is_custom_controller_called = true;
            call_user_func($controller->functions[$method]);

        } else if (isset($controller->functions[$method_full]) and is_callable($controller->functions[$method_full])) {
            $is_custom_controller_called = true;

            call_user_func($controller->functions[$method_full]);
            // exit();
        } elseif (is_array($controller->functions) and !empty($controller->functions) and function_exists('preg_grep')) {
            $attached_routes = $controller->functions;
            foreach ($attached_routes as $k => $v) {
                if (strstr($k, '*')) {
                    $if_route_found = preg_match(sprintf('#%s\d*#', $k), $method_full);
                    if ($if_route_found == true) {
                        $is_custom_controller_called = true;

                        call_user_func($controller->functions[$k]);
                        //   exit();
                    }
                }

            }


        }
    }


    if ($is_custom_controller_called == false) {
        if (method_exists($controller, $method)) {

            $controller->$method();

        } else {

            $controller->index();

        }
    }


    if ($close_conn == true and $installed == true) {
        db_query('close');
    }
    //exit('No method');
}

/*call_user_func_array(array(
 $controller,
 $method
 ), array_slice(url(), 2));*/
//$controller -> render();
