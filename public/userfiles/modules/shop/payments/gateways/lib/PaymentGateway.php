<?php

/**
 * Payment Gateway
 *
 * This library provides generic payment gateway handling functionlity
 * to the other payment gateway classes in an uniform way. Please have
 * a look on them for the implementation details.
 *
 * @package     Payment Gateway
 * @category    Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @link        http://www.phpfour.com
 */

abstract class PaymentGateway
{
    /**
     * Holds the last error encountered
     *
     * @var string
     */
    public $lastError;

    /**
     * Do we need to log IPN results ?
     *
     * @var boolean
     */
    public $logIpn;

    /**
     * File to log IPN results
     *
     * @var string
     */
    public $ipnLogFile;

    /**
     * Payment gateway IPN response
     *
     * @var string
     */
    public $ipnResponse;

    /**
     * Are we in test mode ?
     *
     * @var boolean
     */
    public $testMode;

    /**
     * Field array to submit to gateway
     *
     * @var array
     */
    public $fields = array();

    /**
     * IPN post values as array
     *
     * @var array
     */
    public $ipnData = array();

    /**
     * Payment gateway URL
     *
     * @var string
     */
    public $gatewayUrl;

    /**
     * Initialization constructor
     *
     * @param none
     * @return void
     */
    public function __construct()
    {
        // Some default values of the class
        $this->lastError = '';
        $this->logIpn = TRUE;
        $this->ipnResponse = '';
        $this->testMode = FALSE;
    }

    /**
     * Adds a key=>value pair to the fields array
     *
     * @param string key of field
     * @param string value of field
     * @return
     */
    public function addField($field, $value)
    {
        $this->fields["$field"] = $value;
    }

    /**
     * Submit Payment Request
     *
     * Generates a form with hidden elements from the fields array
     * and submits it to the payment gateway URL. The user is presented
     * a redirecting message along with a button to click.
     *
     * @param none
     * @return void
     */
    public function submitPayment()
    {

        $this->prepareSubmit();

//$rand = uniqid();

$aj = mw()->url_manager->is_ajax();
$ret = '';
if($aj  == false){
        $ret .="<html>\n";
          $ret .= "<head><title>Processing Payment...</title></head>\n";
         $ret .= "<body onLoad=\"document.forms['gateway_form'].submit();\">\n";
		
} else {

 

	
}
$rand = uniqid();
		
		 $ret .= '<script  type="text/javascript">
$(document).ready(function(){
 
 
 $("#gateway_form_'.$rand .'").submit();

});
</script>';
		 $ret .= "<p style=\"text-align:center;\"><h2>Please wait, your order is being processed and you";
         $ret .= " will be redirected to the payment website.</h2></p>\n";
         $ret .= "<form method=\"POST\" name=\"gateway_form\" id='gateway_form_{$rand}' ";
         $ret .= "action=\"" . $this->gatewayUrl . "\">\n";


         if($this->testMode){
             $ret .= '<img alt="" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">'."   \n";
         } else {
             $ret .= '<img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">'."   \n";

         }

        foreach ($this->fields as $name => $value)
        {
              $ret .= "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
        }


         $ret .= "<p style=\"text-align:center;\"><br/><br/>If you are not automatically redirected to ";
         $ret .= "payment website within 5 seconds...<br/><br/>\n";
         $ret .= "<input type=\"submit\" class='mw-ui-btn' value=\"Click Here\"></p>\n";

         $ret .= "</form>\n";
		if($aj  == false){
            $ret .= "</body></html>\n";
		}
		return  $ret;
		
		
    }

    /**
     * Perform any pre-posting actions
     *
     * @param none
     * @return none
     */
    protected function prepareSubmit()
    {
        // Fill if needed
    }

    /**
     * Enables the test mode
     *
     * @param none
     * @return none
     */
    abstract protected function enableTestMode();

    /**
     * Validate the IPN notification
     *
     * @param none
     * @return boolean
     */
    abstract protected function validateIpn();

    /**
     * Logs the IPN results
     *
     * @param boolean IPN result
     * @return void
     */
    public function logResults($success)
    {

        if (!$this->logIpn) return;

        // Timestamp
        $text = '[' . date('m/d/Y g:i A').'] - ';

        // Success or failure being logged?
        $text .= ($success) ? "SUCCESS!\n" : 'FAIL: ' . $this->lastError . "\n";

        // Log the POST variables
        $text .= "IPN POST Vars from gateway:\n";
        foreach ($this->ipnData as $key=>$value)
        {
            $text .= "$key=$value, ";
        }

        // Log the response from the paypal server
        $text .= "\nIPN Response from gateway Server:\n " . $this->ipnResponse;

        // Write to log
        $fp = fopen($this->ipnLogFile,'a');
        fwrite($fp, $text . "\n\n");
        fclose($fp);
    }
}
