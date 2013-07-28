<?php

date_default_timezone_set('UTC');
define('M', memory_get_usage());
define('MW_USE_APC_CACHE', false);
if (!defined('MW_ROOTPATH')) {
    define('MW_ROOTPATH', dirname((__FILE__)) . DIRECTORY_SEPARATOR);
}


if (!isset($_SERVER["SERVER_NAME"])) {
    $config_file_for_site = MW_ROOTPATH . 'config_localhost' . '.php';
} else {
    $no_www = str_ireplace('www.', '', $_SERVER["SERVER_NAME"]);
    $config_file_for_site = MW_ROOTPATH . 'config_' . $no_www . '.php';
}
if (is_file($config_file_for_site)) {
    define('MW_CONFIG_FILE', $config_file_for_site);

} else {
    define('MW_CONFIG_FILE', MW_ROOTPATH . 'config.php');
    $config_file_for_site = MW_ROOTPATH . 'config.php';
}

require_once (MW_ROOTPATH . 'src/Microweber/bootstrap.php');
$mw = new \Microweber\Application(MW_CONFIG_FILE);


$installed = $mw->c('installed');

if (strval($installed) != 'yes') {
    define('MW_IS_INSTALLED', false);
} else {
    define('MW_IS_INSTALLED', true);
}


if (!isset($controller) or !is_object($controller)) {
    $controller = new\Microweber\Controller($mw);
}


if (MW_IS_INSTALLED != true) {
    $controller->install();
    exit();
}

$method_full = mw('url')->string();
$m1 = mw('url')->segment(0);

if ($m1) {
    $m1 = str_replace('.', '', $m1);
    $method = $m1;
} else {
    $method = 'index';
}

$check_custom_controllers = MW_APP_PATH . 'controllers' . DIRECTORY_SEPARATOR . $method . '.php';
if (is_file($check_custom_controllers)) {
    include_once($check_custom_controllers);
    if (class_exists($method)) {
        $controller = new $method();
        $m1 = mw('url')->segment(1);

        if ($m1) {
            $m1 = str_replace('.', '', $m1);
            $method = $m1;
        } else {
            $method = 'index';
        }
    }
}


$params_for_route = mw('url')->segment();
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

        exit();
    } else {

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
    //$params_for_route = mw('url')->segment();

    if (isset($controller->functions[$method])  and is_callable($controller->functions[$method])) {

        $is_custom_controller_called = true;
        call_user_func($controller->functions[$method]);

    } else if (isset($controller->functions[$method_full]) and is_callable($controller->functions[$method_full])) {
        $is_custom_controller_called = true;

        call_user_func($controller->functions[$method_full]);
        // exit();
    } elseif (is_array($controller->functions) and !empty($controller->functions)) {
        $attached_routes = $controller->functions;
        //routing wildcard urls
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
