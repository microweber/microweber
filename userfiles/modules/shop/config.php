<?php
$config = array();
$config['name'] = "Online shop";
$config['author'] = "Microweber";
$config['ui_admin'] = true;
$config['ui'] = true;
$config['categories'] = "online shop";
$config['position'] = 200;
$config['version'] = 0.4;

$config['permissions'] = [
    'index' => 'module.admin.shop.index',
    'create' => 'module.admin.shop.create',
    'edit' => 'module.admin.shop.edit',
    'destroy' => 'module.admin.shop.destroy'
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Shop\ShopServiceProvider::class
];
