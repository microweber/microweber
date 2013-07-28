<?php

namespace Mw;


class Application
{


    protected $config = array();
    private $loaded_config_file_path; //indicates if config is being loaded from file

    public $providers = array();

    public function __construct($config = false)
    {
        if (is_string($config)) {
            $this->loadConfigFromFile($config);
        } else if (!empty($config)) {
            $this->config = $config;
        } else {
            //leave config for later
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
            return $this->$property = new $property($this);

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

    public function __call($method, $args)
    {


        if (!method_exists($this, $method)) {
            print '111';
            $this->$method = new $method($args);

            exit();
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