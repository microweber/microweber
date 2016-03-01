<?php

namespace Microweber\Utils;

class Http
{
    /**
     * An instance of the HTTP adapter to use.
     *
     * @var
     */
    public $adapter;
    /**
     * An instance of the Microweber Application class.
     *
     * @var
     */
    public $app;

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
        if (!is_object($this->adapter)) {
            $this->adapter = new Adapters\Http\Guzzle($app);
        }
    }

    public function url($url)
    {
        return $this->set_url($url);
    }

    public function set_url($url)
    {
        $this->adapter->url = $url;

        return $this;
    }

    public function set_timeout($seconds)
    {
        $this->adapter->timeout = $seconds;

        return $this;
    }

    public function get($params = false)
    {
        return $this->adapter->get($params);
    }

    public function post($params = false)
    {
        return $this->adapter->post($params);
    }

    public function download($save_to_filename, $post_params = false)
    {
        return $this->adapter->download($save_to_filename, $post_params);
    }
}
