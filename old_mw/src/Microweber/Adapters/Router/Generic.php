<?php

namespace Microweber\Adapters\Router;



class Generic
{
    public static $_instance;
    public $functions = array();
    public $vars = array();
    public $controller;
    public $routes = array();
    public $routes_post = array();
    public $url = null; //url class
    function __construct()
    {
        self::$_instance = $this;
        
        $this->url = new \Microweber\Url();

    }

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __get($name)
    {
        if (isset($this->vars[$name]))
            return $this->vars[$name];
    }

    function __set($name, $data)
    {
        if (is_callable($data))
            $this->functions[$name] = $data;
        else
            $this->vars[$name] = $data;
    }

    function __call($method, $args)
    {
        if (isset($this->functions[$method])) {
            call_user_func_array($this->functions[$method], $args);
        }
    }

    function get($name, $data)
    {
        $this->routes[$name] = $data;
    }

    function post($name, $data)
    {
        $this->routes_post[$name] = $data;
    }

    function map($controller)
    {
        $is_custom_controller_called = false;
        if (is_object($controller)) {

            $class_methods = get_class_methods($controller);
            if (!empty($class_methods)) {
                foreach ($class_methods as $method_name) {

                    if (is_string($method_name) and is_callable($controller->$method_name)) {

                        $this->functions[$method_name] = call_user_func($controller->$method_name);
                    }

                }
            }
            $this->controller = $controller;
        }
    }

    function run()
    {
        event_trigger('app_run', false);



        $url_full_string = $this->url->string();
        $url_first_segment = $this->url->segment(0);
        $url_second_segment = $this->url->segment(1);
        if ($url_first_segment) {
            $url_first_segment = str_replace('.', '', $url_first_segment);
            $method = $url_first_segment;
        } else {
            $method = 'index';
        }

        $routes = $this->routes;
        $router_data = array();
        if (isset($_POST) and !empty($_POST)) {
            $router_data = ($_POST);
        }
        if (isset($_GET) and !empty($_GET)) {
            $router_data = array_merge($router_data,$_GET);
        }

        if (isset($_POST) and !empty($_POST)) {
            if (!empty($this->routes_post)) {
                $routes = array_merge($routes, $this->routes_post);
            }
        }

        if (!empty($routes)) {
            foreach ($routes as $route => $callback) {
                $try_call = false;
                if ($url_first_segment == '' and $route == '/') {
                    $try_call = true;
                } elseif ($url_first_segment == $route or $url_first_segment . '/' == $route) {
                    $try_call = true;
                } elseif ($url_full_string == $route or $url_full_string . '/' == $route) {
                    $try_call = true;
                }
                if ($try_call == true) {
                    if (is_callable($callback)) {
                        return call_user_func($callback,$router_data);
                    } elseif (is_string($callback) and class_exists($callback)) {
                        $sub_contoller = new $callback();
                        if (method_exists($sub_contoller, $method)) {
                            return $sub_contoller->$method();
                        }
                        if ($url_second_segment != false and method_exists($sub_contoller, $url_second_segment)) {
                            return $sub_contoller->$url_second_segment();
                        } elseif (method_exists($sub_contoller, 'index')) {
                            return $sub_contoller->index();
                        }
                    }
                }
            }

        }

        $controller = $this->controller;
        //perform custom routing
        $is_custom_controller_called = false;
        if (is_object($controller) and isset($controller->functions) and is_array($controller->functions)) {
            //$params_for_route = $this->url->segment();
            if (isset($controller->functions[$method])  and is_callable($controller->functions[$method])) {
                $is_custom_controller_called = true;
                return call_user_func($controller->functions[$method],$router_data);
            } else if (isset($controller->functions[$url_full_string]) and is_callable($controller->functions[$url_full_string])) {
                $is_custom_controller_called = true;
                return call_user_func($controller->functions[$url_full_string],$router_data);
            } elseif (is_array($controller->functions) and !empty($controller->functions)) {
                $attached_routes = $controller->functions;
                //routing wildcard urls
                foreach ($attached_routes as $k => $v) {
                    if (strstr($k, '*')) {
                        $if_route_found = preg_match(sprintf('#%s\d*#', $k), $url_full_string);
                        if ($if_route_found == true) {
                            $is_custom_controller_called = true;
                            return call_user_func($controller->functions[$k],$router_data);
                        }
                    }

                }
            }
        }
        if ($is_custom_controller_called == false) {
            if (method_exists($controller, $method)) {
                return $controller->$method();
            } else {
                if (isset($this->vars[$method]) and is_string($this->vars[$method])) {

                    if (class_exists($this->vars[$method], 1)) {
                        $sub_contoller = new $this->vars[$method];
                        if (method_exists($sub_contoller, $method)) {
                            return $sub_contoller->$method();
                        }
                        if ($url_second_segment != false and method_exists($sub_contoller, $url_second_segment)) {
                            return $sub_contoller->$url_second_segment();
                        } elseif (method_exists($sub_contoller, 'index')) {
                            return $sub_contoller->index();
                        }
                    }
                }
                return $controller->index();
            }
        }

        if (isset($this->functions[$method])  and is_callable($this->functions[$method])) {
            $is_custom_controller_called = true;
            return $this->callback = call_user_func($this->functions[$method]);
        } else if (isset($this->functions[$url_full_string]) and is_callable($this->functions[$url_full_string])) {
            $is_custom_controller_called = true;
            return $this->callback = call_user_func($this->functions[$url_full_string],$router_data);
        } elseif (!empty($this->functions)) {
            $attached_routes = $this->functions;
            //routing wildcard urls
            foreach ($attached_routes as $k => $v) {
                if (strstr($k, '*')) {
                    $if_route_found = preg_match(sprintf('#%s\d*#', $k), $url_full_string);
                    if ($if_route_found == true) {
                        $is_custom_controller_called = true;
                        return $this->callback = call_user_func($this->functions[$k],$router_data);
                    }
                }
            }
        }
    }
}