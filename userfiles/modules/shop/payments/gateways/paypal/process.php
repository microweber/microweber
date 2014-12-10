<?php

/**
 * Paypal Class
 *
 * Integrate the Paypal payment gateway in your site using this easy
 * to use library. Just see the example code to know how you should
 * proceed. Btw, this library does not support the recurring payment
 * system. If you need that, drop me a note and I will send to you.
 *
 * @package		Payment Gateway
 * @category	Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @link        http://www.phpfour.com
 */
$parent_dir = dirname(dirname(__FILE__)).DS;
include_once ($parent_dir.'lib/PaymentGateway.php');

class Paypal extends PaymentGateway {

	/**
	 * Initialize the Paypal gateway
	 *
	 * @param none
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		// Some default values of the class
		$this->gatewayUrl = 'https://www.paypal.com/cgi-bin/webscr';
		$this->ipnLogFile = 'paypal.ipn_results.log';

		// Populate $fields array with a few default
		$this->addField('rm', '2');
		// Return method = POST
		$this->addField('cmd', '_xclick');
	}

	/**
	 * Enables the test mode
	 *
	 * @param none
	 * @return none
	 */
	public function enableTestMode() {
		$this->testMode = TRUE;
		$this->gatewayUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	}

	/**
	 * Validate the IPN notification
	 *
	 * @param none
	 * @return boolean
	 */
	public function validateIpn() {
		// parse the paypal URL
		$urlParsed = parse_url($this->gatewayUrl);

		// generate the post string from the _POST vars
		$postString = '';

		foreach ($_POST as $field => $value) {
			$this->ipnData["$field"] = $value;
			$postString .= $field . '=' . urlencode(stripslashes($value)) . '&';
		}

		$postString .= "cmd=_notify-validate";
		// append ipn command

		// open the connection to paypal
		$fp = fsockopen($urlParsed[host], "80", $errNum, $errStr, 30);

		if (!$fp) {
			// Could not open the connection, log error if enabled
			$this->lastError = "fsockopen error no. $errNum: $errStr";
			$this->logResults(false);

			return false;
		} else {
			// Post the data back to paypal

			fputs($fp, "POST $urlParsed[path] HTTP/1.1\r\n");
			fputs($fp, "Host: $urlParsed[host]\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: " . strlen($postString) . "\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $postString . "\r\n\r\n");

			// loop through the response from the server and append to variable
			while (!feof($fp)) {
				$this->ipnResponse .= fgets($fp, 1024);
			}

			fclose($fp);
			// close connection
		}

		if (eregi("VERIFIED", $this->ipnResponse)) {
			// Valid IPN transaction.
			$this->logResults(true);
			return true;
		} else {
			// Invalid IPN transaction.  Check the log for details.
			$this->lastError = "IPN Validation Failed . $urlParsed[path] : $urlParsed[host]";
			$this->logResults(false);
			return false;
		}
	}

}

$url1 = parse_url($place_order['url']);
if (isset($url1["query"]) and $url1["query"] != '') {
	$qsign = '&';
} else {
	$qsign = '?';
}

// Create an instance of the paypal library
$myPaypal = new Paypal();

// Specify your paypal email
$myPaypal -> addField('business', trim(get_option('paypalexpress_username', 'payments')));



$currencies_list_paypal = mw()->shop_manager->currency_get_for_paypal();
$currencyCode = $place_order['currency'];
$amount = $place_order['amount'];
$place_order['payment_amount'] = $amount;

$place_order['payment_shipping'] = $place_order['shipping'];
if (!in_array(strtoupper($place_order['currency']), $currencies_list_paypal)){
	 $payment_currency = get_option('payment_currency', 'payments');  
  	$payment_currency_rate = get_option('payment_currency_rate', 'payments'); 
	if($payment_currency_rate != false){
	 $payment_currency_rate = str_replace(',','.',$payment_currency_rate);
	 $payment_currency_rate = floatval( $payment_currency_rate);
	
	}
	
	if($payment_currency_rate  != 0.00){
		$currencyCode =$payment_currency;
		$amount= $amount*$payment_currency_rate;
		$place_order['payment_shipping']= $place_order['payment_shipping']*$payment_currency_rate;
		$place_order['payment_amount'] = $amount;
 
	}
}


if (is_array($posted_fields)) {
	foreach ($posted_fields as $k => $value) {
		$myPaypal -> addField($k, $value);
	}
}
if (is_array($place_order)) {
	foreach ($place_order as $k => $value) {
		$myPaypal -> addField($k, $value);
	}
}
$place_order['payment_currency'] = $currencyCode;
// Specify the currency
if (isset($place_order['payment_currency']) and ($place_order['payment_currency']) != false) {
	$myPaypal -> addField('currency_code', $place_order['payment_currency']);
} else {
	$myPaypal -> addField('currency_code', 'USD');
}
// Specify the url where paypal will send the user on success/failure
$myPaypal -> addField('return', $mw_return_url);
$myPaypal -> addField('cancel_return', $mw_cancel_url);

// Specify the url where paypal will send the IPN
$myPaypal -> addField('notify_url', $mw_ipn_url);

// Specify the product information
$myPaypal -> addField('item_name', $place_order['item_name']);
$myPaypal -> addField('amount', $place_order['payment_amount']);
$myPaypal -> addField('shipping', $place_order['payment_shipping']);

//$myPaypal->addField('item_number', $cart['session_id']);

// Specify any custom value
$myPaypal -> addField('total_items', $place_order['items_count']);
// Enable test mode if needed

$paypal_is_test = (get_option('paypalexpress_testmode', 'payments')) == 'y';
if($paypal_is_test  == true){
$myPaypal -> enableTestMode();
}
// Let's start the train! 
$place_order['order_completed'] = 1;
$place_order['is_paid'] = 0;
$place_order['success'] = $myPaypal -> submitPayment();
//dd($place_order);