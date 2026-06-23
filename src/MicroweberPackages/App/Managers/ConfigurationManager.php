<?php

namespace MicroweberPackages\App\Managers;

use Illuminate\Support\Facades\Config;

class ConfigurationManager
{
    public function get($key)
    {
        return Config::get($key);
    }

    public function set($key, $val)
    {
        return Config::set($key, $val);
    }

    public function save()
    {
        return Config::save();
    }
}
