<?php
/**
 * Microweber Offers Module
 * Developed by: Ezyweb.uk
 *
 * @category   Modules
 * @package    Config
 * @author     Nick Woolley <nick@ezyweb.uk>
 * @copyright  2018 Microweber
 */

$config = array();
$config['name'] = "Offers";
$config['author'] = "Microweber";
$config['ui'] = false;
$config['ui_admin'] = true;
$config['categories'] = "online shop";
$config['version'] = 1.1;
$config['position'] = 27;

$config['settings']['service_provider'] = [
    \MicroweberPackages\Offer\Providers\EventServiceProvider::class,
    \MicroweberPackages\Offer\Providers\OfferServiceProvider::class,
];

//
// moved to src/MicroweberPackages/Offer/migrations/2024_01_26_000000_offers_table.php
//$config['tables'] = array(
//	'offers' => array(
//		'id' => 'integer',
//		'product_id' => 'integer',
//		'price_id' => 'integer',
//	//	'price_key' => 'string',
//		'offer_price' => 'float',
//		'created_at' => 'datetime',
//		'updated_at' => 'datetime',
//		'expires_at' => 'datetime',
//		'created_by' => 'integer',
//		'edited_by' => 'integer',
//		'is_active' => 'integer',
//	)
//);
