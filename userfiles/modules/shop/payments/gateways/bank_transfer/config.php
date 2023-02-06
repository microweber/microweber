<?php

$config = array();
$config['name'] ="Bank Transfer";
$config['author'] = "Bozhidar Slaveykov";
$config['author.2'] = "Microweber";
$config['ui'] = false;
$config['categories'] = "online shop";
$config['position'] = 110;
$config['type'] = "payment_gateway";
$config['version'] = "1.0";

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Payment\\Providers\\BankTransfer\\'
    ],
];
$config['settings']['service_provider'] = [
    \MicroweberPackages\Payment\Providers\BankTransfer\BankTransferServiceProvider::class,
 ];
