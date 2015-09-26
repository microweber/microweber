<?php

function mw_is_installed() {
    static $is = null;

    if ($is===null){
        $is = Config::get('microweber.is_installed');
    }

    return (bool) $is;
}


api_expose_admin('mw_save_framework_config_file', function ($params) {
    if (empty($params)){
        return;
    }
    $save_configs = array();
    foreach ($params as $k => $item) {
        if (is_array($item) and !empty($item)){
            foreach ($item as $config_k => $config) {
                if (is_string($config_k)){
                    Config::set($k . '.' . $config_k, $config);
                    $save_configs[] = $k;
                }
            }
        }
    }
    if (!empty($save_configs)){
        Config::save($save_configs);
    }

});