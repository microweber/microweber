<?php

namespace Microweber;

class Router
{

    public $functions = array();
    public $vars = array();

    public $controller;

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
        } else {
            // error out
        }
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

        $method_full = mw('url')->string();
        $m1 = mw('url')->segment(0);

        if ($m1) {
            $m1 = str_replace('.', '', $m1);
            $method = $m1;
        } else {
            $method = 'index';
        }


        $controller = $this->controller;

        //perform custom routing

        $is_custom_controller_called = false;
        if (is_object($controller) and isset($controller->functions) and is_array($controller->functions)) {
            //$params_for_route = mw('url')->segment();

            if (isset($controller->functions[$method])  and is_callable($controller->functions[$method])) {

                $is_custom_controller_called = true;
                return call_user_func($controller->functions[$method]);


            } else if (isset($controller->functions[$method_full]) and is_callable($controller->functions[$method_full])) {
                $is_custom_controller_called = true;

                return call_user_func($controller->functions[$method_full]);

                // exit();
            } elseif (is_array($controller->functions) and !empty($controller->functions)) {
                $attached_routes = $controller->functions;
                //routing wildcard urls
                foreach ($attached_routes as $k => $v) {
                    if (strstr($k, '*')) {
                        $if_route_found = preg_match(sprintf('#%s\d*#', $k), $method_full);
                        if ($if_route_found == true) {
                            $is_custom_controller_called = true;

                            return call_user_func($controller->functions[$k]);

                            //   exit();
                        }
                    }

                }


            }
        }


        if ($is_custom_controller_called == false) {
            if (method_exists($controller, $method)) {

                return $controller->$method();

            } else {

                return $controller->index();

            }


        }


        if (isset($this->functions[$method])  and is_callable($this->functions[$method])) {

            $is_custom_controller_called = true;
            return $this->callback = call_user_func($this->functions[$method]);

        } else if (isset($this->functions[$method_full]) and is_callable($this->functions[$method_full])) {
            $is_custom_controller_called = true;

            return $this->callback = call_user_func($this->functions[$method_full]);
            // exit();
        } elseif (!empty($this->functions)) {
            $attached_routes = $this->functions;
            //routing wildcard urls
            foreach ($attached_routes as $k => $v) {
                if (strstr($k, '*')) {
                    $if_route_found = preg_match(sprintf('#%s\d*#', $k), $method_full);
                    if ($if_route_found == true) {
                        $is_custom_controller_called = true;

                        return $this->callback = call_user_func($this->functions[$k]);
                        //   exit();
                    }
                }

            }


        }
    }
}