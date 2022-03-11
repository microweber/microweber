<?php

use Illuminate\Support\Facades\Route;

Route::name('api.')
    ->prefix('api')
    ->middleware(['api','admin','xss'])
    ->group(function () {

        Route::post('mw_save_framework_config_file', function () {

            $params = request()->all();

            $saveOnlyKeys = [
                'developer_mode',
                'force_https',
                'update_channel',
                'compile_assets',
            ];

            if (empty($params) or !is_admin()) {
                return;
            }

            $save_configs = array();
            foreach ($params as $k => $item) {
                if ($k != 'microweber') {
                    continue;
                }
                if (is_array($item) and !empty($item)) {
                    foreach ($item as $config_k => $config) {
                        if (is_string($config_k)) {
                            if (is_numeric($config)) {
                                $config = intval($config);
                            }

                            if (in_array($config_k, $saveOnlyKeys)) {
                                Config::set($k . '.' . $config_k, $config);
                                $save_configs[] = $k;
                            }
                        }
                    }
                }
            }
            if (!empty($save_configs)) {
                Config::save($save_configs);
                return array('success' => 'Config is changed!');

            }
        });
    });
