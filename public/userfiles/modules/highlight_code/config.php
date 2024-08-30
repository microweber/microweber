<?php
$config = array();
$config['name'] = "Highlight Code";
$config['author'] = "Microweber";
$config['ui'] = true;
$config['categories'] = "other";
$config['position'] = 700;
$config['version'] = "1.3";

$config['settings'] = [];
$config['settings']['allowed_html_option_keys'] = [
    'text',
];

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\HighlightCode'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\HighlightCode\Providers\HighlightCodeServiceProvider::class
];
