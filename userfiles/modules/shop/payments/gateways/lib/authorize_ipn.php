<?php

// Include the paypal library
include_once ('Authorize.php');

// Create an instance of the authorize.net library
$myAuthorize = new Authorize();

// Log the IPN results
$myAuthorize->ipnLog = TRUE;

// Specify your authorize login and secret
$myAuthorize->setUserInfo('YOUR_LOGIN', 'YOUR_SECRET_KEY');

// Enable test mode if needed
$myAuthorize->enableTestMode();

// Check validity and write down it
if ($myAuthorize->validateIpn())
{
    file_put_contents('authorize.txt', 'SUCCESS');
}
else
{
    file_put_contents('authorize.txt', "FAILURE\n\n" . $myAuthorize->ipnData);
}