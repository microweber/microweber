<?php

$config = array();
$config['name'] = "Embed Code";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['categories'] = "miscellaneous";
$config['position'] = 38;
$config['version'] = "0.6";


//defining the table fields
//$embed_table = array("embed_title"=> "text","embed_code"=> "text","created_at"=> "datetime");

//definig the table name as "embed_code"
//$config['table'] = array('embed_code'=>$embed_table);


$config['settings'] = [];
$config['settings']['allowed_html_option_keys'] = [
    'source_code',
];

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\Embed'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Embed\Providers\EmbedServiceProvider::class
];


