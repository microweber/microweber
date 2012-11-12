<?php

// Include the paypal library
include_once ('Authorize.php');

// Create an instance of the authorize.net library
$myAuthorize = new Authorize();

// Specify your authorize.net login and secret
$myAuthorize->setUserInfo('YOUR_LOGIN', 'YOUR_SECRET_KEY');

// Specify the url where authorize.net will send the user on success/failure
$myAuthorize->addField('x_Receipt_Link_URL', 'http://YOUR_HOST/payment/authorize_success.php');

// Specify the url where authorize.net will send the IPN
$myAuthorize->addField('x_Relay_URL', 'http://YOUR_HOST/payment/authorize_ipn.php');

// Specify the product information
$myAuthorize->addField('x_Description', 'T-Shirt');
$myAuthorize->addField('x_Amount', '9.99');
$myAuthorize->addField('x_Invoice_num', rand(1, 100));
$myAuthorize->addField('x_Cust_ID', 'muri-khao');

// Enable test mode if needed
$myAuthorize->enableTestMode();

// Let's start the train!
$myAuthorize->submitPayment();