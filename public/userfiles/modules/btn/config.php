<?php

$config = array();
$config['name'] = "Button";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['ui_admin'] = false;
$config['is_system'] = true;
$config['position'] = 7;
$config['version'] = "1.1";
$config['categories'] = "essentials";
$config['settings'] = [];
$config['settings']['translatable_options'] = [
    'button_action',
    'button_onclick',
    'popupcontent',
    'url_blank',
    'icon',
    'text',
    'url',
    'link'
];

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\Btn'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Btn\Providers\BtnServiceProvider::class
];



