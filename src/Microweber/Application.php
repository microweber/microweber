<?php

namespace Microweber;


class Application
{


    public $config = array();
    public $loaded_config_file_path; //indicates if config is being loaded from file

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

        global $_mw_global_object;
        //  if (!is_object($_mw_global_object)) {
        $_mw_global_object = $this;
        //}


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

        $property_orig_case = $property;
        $property = ucfirst($property);
        $property2 = strtolower($property);
        if (isset($this->$property2)) {

            return $this->$property2;
        } else if (isset($this->$property)) {

            return $this->$property;
        } else if (isset($this->providers[$property])) {
            return $this->providers[$property];
        } elseif (isset($this->providers[$property2])) {
            return $this->providers[$property2];
        } else if (property_exists($this, $property2)) {
            return $this->$property2;
        }


        if (property_exists($this, $property)) {
            return $this->$property;
        } else if (property_exists($this, $property2)) {
            return $this->$property2;
        } else {


            try {

                $mw = '\Microweber\\' . $property;
              //  $mw = str_ireplace(array('\\\\', 'Microweber\Microweber'), array('\\', 'Microweber'), $mw);
                $mw = str_ireplace(array('/','\\\\', 'Microweber\Microweber'), array('\\','\\', 'Microweber'), $mw);

                $prop = new $mw($this);
            } catch (Exception $e) {
                $prop = new $property($this);
            }
            if (isset($prop)) {

                return $this->$property = $this->providers[$property] = $this->$property_orig_case = $prop;
            }

        }
    }

    public function call($provider, $args = null)
    {


        if (!method_exists($this, $provider)) {
            if (!isset($this->providers[$provider])) {
                if (!is_object($provider)) {
                    if ($args != null) {
                        $this->providers[$provider] = new $provider($args);

                    } else {
                        $this->providers[$provider] = new $provider;

                    }
                }
                return $this->providers[$provider];

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

        if (strtolower($property) == 'application') {


            return;

            //prevent recursion ?
            return $this;
        }


        if (!property_exists($this, $property) and !isset($this->providers[$property])) {

            $property = ucfirst($property);
            $property2 = strtolower($property);

            return $this->$property = $this->$property2 = $this->providers[$property] = $this->providers[$property2] = $value;

        }
    }

    public function __seddddt($property, $value)
    {

        if (strtolower($property) == 'application') {


            return;

            //prevent recursion ?
            return $this;
        }
        if (!is_object($value)) {
            $property = str_ireplace(array('\\\\', 'Microweber', '\\'), array('\\', '', ''), $property);

            $property = ucfirst($property);
            $property2 = strtolower($property);

            if (isset($this->providers[$property])) {

                return $this->$property = $this->$property2 = $this->providers[$property];
            } else if (isset($this->providers[$property2])) {

                return $this->$property = $this->$property2 = $this->providers[$property2];
            }


            if (!property_exists($this, $property)) {

                $this->$property = $this->$property2 = $this->providers[$property] = $this->providers[$property2] = $value;
                //   return $this->$property;
            }
        } else {
            $this->$property = $this->providers[$property] = $value;
        }

        return $this;
    }


}