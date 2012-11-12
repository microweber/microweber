<?php

// Include the paypal library
include_once ('Paypal.php');

// Create an instance of the paypal library
$myPaypal = new Paypal();

// Log the IPN results
$myPaypal->ipnLog = TRUE;

// Enable test mode if needed
$myPaypal->enableTestMode();

// Check validity and write down it
if ($myPaypal->validateIpn())
{
    if ($myPaypal->ipnData['payment_status'] == 'Completed')
    {
         file_put_contents('paypal.txt', 'SUCCESS');
    }
    else
    {
         file_put_contents('paypal.txt', "FAILURE\n\n" . $myPaypal->ipnData);
    }
}