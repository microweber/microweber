<?php
$config = array();
$config['name'] = "Invoices";
$config['author'] = "Microweber";
$config['ui_admin'] = false;
$config['ui'] = false;
$config['categories'] = "online shop";
$config['position'] = 2;
$config['version'] = 0.3;

$config['permissions'] = [
    'index' => 'module.admin.shop.invoices.index',
    'create' => 'module.admin.shop.invoices.create',
    'edit' => 'module.admin.shop.invoices.edit',
    'destroy' => 'module.admin.shop.invoices.destroy'
];