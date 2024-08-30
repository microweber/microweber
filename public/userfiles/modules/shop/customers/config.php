<?php
$config = array();
$config['name'] = "Customers";
$config['author'] = "Microweber";
$config['ui_admin'] = false;
$config['ui'] = false;
$config['categories'] = "online shop";
$config['position'] = 2;
$config['version'] = 0.3;

$config['permissions'] = [
    'index' => 'module.admin.shop.customers.index',
    'create' => 'module.admin.shop.customers.create',
    'edit' => 'module.admin.shop.customers.edit',
    'destroy' => 'module.admin.shop.customers.destroy'
];