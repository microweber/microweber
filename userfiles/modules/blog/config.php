<?php
$config = array();
$config['name'] = "Blog";
$config['author'] = "Microweber";
$config['ui'] = true;
$config['ui_admin'] = true;
$config['is_system'] = true;
$config['position'] = 7;
$config['version'] = 0.1;
$config['categories'] = "content";

$config['settings']['service_provider'] = [
    \MicroweberPackages\Blog\BlogServiceProvider::class
];


