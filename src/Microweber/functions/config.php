<?php

function mw_is_installed() {
    static $is = null;

    if ($is===null){
        $is = Config::get('microweber.is_installed');
    }

    return (bool) $is;
}


api_expose_admin('mw_save_config_file', function ($params) {
    if(empty($params)){
        return;
    }

    foreach ($params as $k => $item) {

    }
    dd($params);
});