<?php

// Include the paypal library
include_once ('TwoCo.php');

// Create an instance of the authorize.net library
$my2CO = new TwoCo();

// Log the IPN results
$my2CO->ipnLog = TRUE;

// Specify your authorize login and secret
$my2CO->setSecret('YOUR_SECRET_KEY');

// Enable test mode if needed
$my2CO->enableTestMode();

// Check validity and write down it
if ($my2CO->validateIpn())
{
    file_put_contents('2co.txt', 'SUCCESS');
}
else
{
    file_put_contents('2co.txt', "FAILURE\n\n" . $my2CO->ipnData);
}