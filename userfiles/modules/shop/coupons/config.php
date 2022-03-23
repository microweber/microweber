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
$config['ui_admin'] = false;

$config['categories'] = "online shop";
$config['version'] = "0.3";
$config['position'] = 26;

/*
$config['settings']['autoload_namespace'] = [
    [
        'path' => _DIR_ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\Shop\\Coupons\\'
    ],
];*/
$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Shop\Coupons\ShopCouponServiceProvider::class
];

$config['tables'] = array(
	'cart_coupons' => array(
		'id' => 'integer',
		'coupon_name' => 'string',
		'coupon_code' => 'string',
		'discount_type' => 'string',
		'discount_value' => 'string',
		'total_amount' => 'string',
		'uses_per_coupon' => 'integer',
		'uses_per_customer' => 'integer',
		'is_active' => 'integer',
	),
	'cart_coupon_logs' => array(
		'id' => 'integer',
		'coupon_id' => 'integer',
		'coupon_code' => 'string',
		'customer_email' => 'string',
		'customer_ip' => 'string',
		'uses_count' => 'integer',
		'use_date' => 'dateTime'
	)
);
