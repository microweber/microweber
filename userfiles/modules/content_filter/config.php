<?php
$config = array();
$config['name'] = "Content filter";
$config['author'] = "Bozhidar Slaveykov";
$config['no_cache'] = false;
$config['categories'] = "content";
$config['version'] = 0.1;
$config['ui'] = false;
$config['ui_admin'] = false;
$config['is_system'] = false;
$config['position'] = 21;

$config['settings']['service_provider'] = [
    \MicroweberPackages\ContentFilter\ContentFilterServiceProvider::class
];
