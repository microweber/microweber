<?php

/**
 * 2CheckOut Class
 *
 * Integrate the 2CheckOut payment gateway in your site using this easy
 * to use library. Just see the example code to know how you should
 * proceed. Btw, this library does not support the recurring payment
 * system. If you need that, drop me a note and I will send to you.
 *
 * @package     Payment Gateway
 * @category    Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @link        http://www.phpfour.com
 */

include_once ('PaymentGateway.php');

class TwoCo extends PaymentGateway
{
    /**
     * Secret word to be used for IPN verification
     *
     * @var string
     */
    public $secret;

    /**
     * Initialize the 2CheckOut gateway
     *
     * @param none
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Some default values of the class
        $this->gatewayUrl = 'https://www.2checkout.com/checkout/purchase';
        $this->ipnLogFile = '2co.ipn_results.log';
    }

    /**
     * Enables the test mode
     *
     * @param none
     * @return none
     */
    public function enableTestMode()
    {
        $this->testMode = TRUE;
        $this->addField('demo', 'Y');
    }

    /**
     * Set the secret word
     *
     * @param string the scret word
     * @return void
     */
    public function setSecret($word)
    {
        if (!empty($word))
        {
            $this->secret = $word;
        }
    }

    /**
     * Validate the IPN notification
     *
     * @param none
     * @return boolean
     */
    public function validateIpn()
    {
        foreach ($_POST as $field=>$value)
        {
            $this->ipnData["$field"] = $value;
        }

        $vendorNumber   = ($this->ipnData["vendor_number"] != '') ? $this->ipnData["vendor_number"] : $this->ipnData["sid"];
        $orderNumber    = $this->ipnData["order_number"];
        $orderTotal     = $this->ipnData["total"];

        // If demo mode, the order number must be forced to 1
        if($this->demo == "Y" || $this->ipnData['demo'] == 'Y')
        {
            $orderNumber = "1";
        }

        // Calculate md5 hash as 2co formula: md5(secret_word + vendor_number + order_number + total)
        $key = strtoupper(md5($this->secret . $vendorNumber . $orderNumber . $orderTotal));

        // verify if the key is accurate
        if($this->ipnData["key"] == $key || $this->ipnData["x_MD5_Hash"] == $key)
        {
            $this->logResults(true);
            return true;
        }
        else
        {
            $this->lastError = "Verification failed: MD5 does not match!";
            $this->logResults(false);
            return false;
        }
    }
}