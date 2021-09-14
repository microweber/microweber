<?php
$config = array();
$config['name'] = "Blog";
$config['author'] = "Microweber";
$config['ui'] = true;
$config['ui_admin'] = true;
$config['is_system'] = true;
$config['position'] = 200;
$config['version'] = 0.2;
$config['categories'] = "content";

$config['settings']['service_provider'] = [
    \MicroweberPackages\Blog\BlogServiceProvider::class
];


