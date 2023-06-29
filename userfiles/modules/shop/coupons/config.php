<?php
/**
 * Microweber Coupon Module
 * Developed by: Bozhidar Slaveykov
 *
 * @category   Modules
 * @package    Config
 * @author     Bozhidar Slaveykov <selfworksbg@gmail.com>
 * @copyright  2018 Microweber
 */

$config = array();
$config['name'] ="Coupons";
$config['author'] = "Bozhidar Slaveykov";

$config['ui'] = false;
$config['ui_admin'] = true;

$config['categories'] = "online shop";
$config['version'] = "0.6";
$config['position'] = 26;


$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\Shop\\Coupons\\'
    ],
];
$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Shop\Coupons\Providers\ShopCouponServiceProvider::class,
    \MicroweberPackages\Modules\Shop\Coupons\Providers\ShopCouponEventServiceProvider::class
];


