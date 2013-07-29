<?php

namespace Microweber;


class Application
{


    protected $config = array();
    private $loaded_config_file_path; //indicates if config is being loaded from file

    public $providers = array();

    public function __construct($config = false)
    {


        if ($config != false) {
            if (is_string($config)) {
                $this->loadConfigFromFile($config);
            } else if (!empty($config)) {
                $this->config = $config;
            }
        }

    }

    public function config($k, $no_static = false)
    {
        return $this->c($k, $no_static);
    }

    public function c($k, $no_static = false)
    {

        if ($no_static == true) {
            $this->config = false;
        }

        if (isset($this->config[$k])) {
            return $this->config[$k];
        } else {
            $load_cfg = false;
            if ($this->loaded_config_file_path != false
                and is_file($this->loaded_config_file_path)
            ) {
                $load_cfg = $this->loaded_config_file_path;
            } else
                if (defined('MW_CONFIG_FILE') and MW_CONFIG_FILE != false and is_file(MW_CONFIG_FILE)) {
                    //try to get from the constant
                    $load_cfg = MW_CONFIG_FILE;
                }

            if ($load_cfg != false) {
                include_once ($load_cfg);
                if (isset($config)) {
                    $this->config = $config;
                    if (isset($this->config[$k])) {

                        return $this->config[$k];
                    }
                } else {
                    include (MW_CONFIG_FILE);
                    if (isset($config)) {
                        $this->config = $config;
                        if (isset($this->config[$k])) {

                            return $this->config[$k];
                        }
                    }
                }
            }
        }
    }

    public function loadConfigFromFile($path_to_file)
    {
        if ($this->loaded_config_file_path != $path_to_file
            and is_file($path_to_file)
        ) {
            include_once ($path_to_file);
            $this->loaded_config_file_path = $path_to_file;
            if (isset($config)) {

                $this->config = $config;
                return $this->config;
            }
        }
    }


    public function __get($property)
    {

        if (property_exists($this, $property)) {
            return $this->$property;
        } else {
            $property_orig_case = $property;
            $property = ucfirst($property);
            try {

                $mw = '\Microweber\\' . $property;
                $mw = str_replace(array('\\\\', 'Microweber\Microweber'), array('\\', 'Microweber'), $mw);


                $prop = new $mw($this);
            } catch (Exception $e) {
                $prop = new $property($this);
            }
            if (isset($prop)) {
                return $this->$property = $this->$property_orig_case = $prop;
            }

        }
    }

    public function call($provider, $args = null)
    {
        $providers = $this->providers;


        if (!method_exists($this, $provider)) {
            if (!isset($providers[$provider])) {
                if (!is_object($provider)) {
                    if ($args != null) {
                        $providers[$provider] = new $provider($args);

                    } else {
                        $providers[$provider] = new $provider;

                    }
                }
                return $providers[$provider];

            } else {

                return $this->$provider($args);
            }
        }
    }

    public function __call($class, $constructor_params)
    {


        if (!method_exists($this, $class)) {


            $class_name = strtolower($class);

            $class = ucfirst($class);
            $class = str_replace('/', '\\', $class);

            $mw = '\Microweber\\' . $class;
            $mw = str_replace(array('\\\\', 'Microweber\Microweber'), array('\\', 'Microweber'), $mw);

            if (!isset($this->providers[$class_name])) {
                if ($constructor_params == false) {

                    try {
                        $prop = new $mw($constructor_params);
                    } catch (Exception $e) {
                        $prop = new $class($constructor_params);
                    }
                    if (isset($prop)) {
                        $this->providers[$class_name] = $prop;
                    }
                } else {

                    try {
                        $prop = new $mw($constructor_params);

                    } catch (Exception $e) {
                        $prop = new $class($constructor_params);
                    }
                    if (isset($prop)) {

                        $this->providers[$class_name] = $prop;
                    }

                }

            }
            return $this->providers[$class_name];


        }


    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }


}