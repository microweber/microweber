<?php

// Include the paypal library
include_once ('TwoCo.php');

// Create an instance of the authorize.net library
$my2CO = new TwoCo();

// Specify your 2CheckOut vendor id
$my2CO->addField('sid', 'YOUR_VENDOR_ID');

// Specify the order information
$my2CO->addField('cart_order_id', rand(1, 100));
$my2CO->addField('total', '9.99');

// Specify the url where authorize.net will send the IPN
$my2CO->addField('x_Receipt_Link_URL', 'http://YOUR_HOST/payment/twoco_ipn.php');
$my2CO->addField('tco_currency', 'USD');
$my2CO->addField('custom', 'muri');

// Enable test mode if needed
$my2CO->enableTestMode();

// Let's start the train!
$my2CO->submitPayment();
