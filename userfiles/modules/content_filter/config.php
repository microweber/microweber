<?php
$config = array();
$config['name'] = "Content filter";
$config['author'] = "Bozhidar Slaveykov";
$config['no_cache'] = false;
$config['ui'] = true;
$config['categories'] = "blog";
$config['version'] = 0.1;
$config['position'] = 21;

$config['settings']['service_provider'] = [
    \MicroweberPackages\ContentFilter\ContentFilterServiceProvider::class
];
