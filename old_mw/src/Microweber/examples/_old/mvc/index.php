<?php

 

date_default_timezone_set('UTC');
define('T', microtime());


define('DS', DIRECTORY_SEPARATOR);
define('BASE_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('M', memory_get_usage());
define('AJAX', strtolower(getenv('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest');
 
require_once (BASE_DIR . 'functions.php');
 
  

 if (!isset($controller) or !is_object($controller)) {
        $controller = new DefaultController();
    }

  
     $method_full = url_string();
     $call_method = url_segment(0);

    if ($call_method) {
        $call_method = str_replace('.', '', $call_method);
        $method = $call_method;
    } else {
        $method = 'index';
    }

    $check_custom_controllers = BASE_DIR . 'controllers' . DIRECTORY_SEPARATOR . $method . '.php';
    if (is_file($check_custom_controllers)) {
        include_once($check_custom_controllers);
        if (class_exists($method)) {
            $controller = new $method();
            $call_method = url_segment(1);

            if ($call_method) {
                $call_method = str_replace('.', '', $call_method);
                $method = $call_method;
            } else {
                $method = 'index';
            }
        }
    }


    $params_for_route = url_segment();
    //loading custom routes
    $routes_file = BASE_DIR . 'routes.php';
    if (is_file($routes_file)) {
        include_once($routes_file);
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


 