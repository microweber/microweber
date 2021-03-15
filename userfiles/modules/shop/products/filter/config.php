<?php
$config = array();
$config['name'] = "Products filter";
$config['author'] = "Bozhidar Slaveykov";
$config['no_cache'] = false;
$config['ui'] = true;
$config['categories'] = "online shop";
$config['version'] = 0.1;
$config['position'] = 21;

$config['settings']['service_provider'] = [
    \MicroweberPackages\Shop\Products\Filter\ProductFilterServiceProvider::class
];
