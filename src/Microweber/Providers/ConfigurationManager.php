<?php

namespace Microweber\Providers;

use Illuminate\Support\Facades\Config;

class ConfigurationManager
{
    public function get($key)
    {
        return Config::get($key);
    }

    public function set($key, $val)
    {
        return Config::get($key, $val);
    }

    public function save()
    {
        return Config::save();
    }
}
