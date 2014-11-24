<?php

namespace Weber\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Weber\Utils\Database as DbUtils;



class Configuration {



    public function get($key){
        return Config::get($key);
    }

    public function set($key,$val){
        return Config::get($key,$val);
    }

    public function save(){
        return Config::save();

    }
}