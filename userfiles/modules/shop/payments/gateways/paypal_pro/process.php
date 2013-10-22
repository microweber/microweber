<?php
$parent_dir = dirname(dirname(__FILE__)).DS;
 
include_once ($parent_dir.'lib/paypal_pro.inc.php');

$firstName = urlencode(trim($_POST['cc_first_name']));
$lastName = urlencode(trim($_POST['cc_last_name']));
$creditCardType = urlencode(trim($_POST['cc_type']));
$creditCardNumber = urlencode(trim($_POST['cc_number']));
$expDateMonth = urlencode(trim($_POST['cc_month']));
$padDateMonth = str_pad(trim($expDateMonth), 2, '0', STR_PAD_LEFT);
$expDateYear = urlencode(trim($_POST['cc_year']));
$cvv2Number = urlencode(trim($_POST['cc_verification_value']));

$address1 = '';
if (isset($_POST['address'])) {
	$address1 = urlencode($_POST['address']);
}
$address2 = '';

$city = '';
if (isset($_POST['city'])) {
	$city = urlencode($_POST['city']);
}

$state = '';
if (isset($_POST['state'])) {
	$state = urlencode($_POST['state']);
}

$zip = '';
if (isset($_POST['zip'])) {
	$zip = urlencode($_POST['zip']);
}

$amount = ($place_order['amount']);
if (isset($place_order['shipping']) and intval($place_order['shipping']) > 0) {
	$amount = $amount + floatval($place_order['shipping']);
}




$currencies_list_paypal = mw('shop')->currency_get_for_paypal();
$currencyCode = $place_order['currency'];

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
	}
}

$amount = urlencode($amount);

$paymentAction = urlencode("Sale");

$nvpRecurring = '';
$methodToCall = 'doDirectPayment';

//$nvpstr='&PAYMENTACTION='.$paymentAction.'&AMT='.$amount.'&CREDITCARDTYPE='.$creditCardType.'&ACCT='.$creditCardNumber.'&EXPDATE='.         $padDateMonth.$expDateYear.'&CVV2='.$cvv2Number.'&FIRSTNAME='.$firstName.'&LASTNAME='.$lastName.'&STREET='.$address1.'&CITY='.$city.'&STATE='.$state.'&ZIP='.$zip.'&COUNTRYCODE=US&CURRENCYCODE='.$currencyCode.$nvpRecurring;
//$nvpstr='&PAYMENTACTION='.$paymentAction.'&AMT='.$amount.'&CREDITCARDTYPE='.$creditCardType.'&ACCT='.$creditCardNumber.'&EXPDATE='.         $padDateMonth.$expDateYear.'&CVV2='.$cvv2Number.'&FIRSTNAME='.$firstName.'&LASTNAME='.$lastName.'&STREET='.$address1.'&CITY='.$city.'&STATE='.$state.'&ZIP='.$zip.'&COUNTRYCODE=US&CURRENCYCODE='.$currencyCode.$nvpRecurring;

$nvpstr = '&PAYMENTACTION=' . $paymentAction . '&AMT=' . $amount . '&CREDITCARDTYPE=' . $creditCardType . '&ACCT=' . $creditCardNumber . '&EXPDATE=' . $padDateMonth . $expDateYear . '&CVV2=' . $cvv2Number . '&FIRSTNAME=' . $firstName . '&LASTNAME=' . $lastName . '&COUNTRYCODE=US&CURRENCYCODE=' . $currencyCode . $nvpRecurring;
if (isset($place_order['currency']) and ($place_order['currency']) != false) {
	$nvpstr .= '&CURRENCY_CODE=' . $place_order['currency'].'&CURRENCY=' . $place_order['currency'];

}
//$paypalpro_username = trim(get_option('paypalpro_username', 'payments'));
$paypalpro_apikey = trim(get_option('paypalpro_apikey', 'payments'));
$paypalpro_apipassword = trim(get_option('paypalpro_apipassword', 'payments'));
$paypalpro_apisignature = trim(get_option('paypalpro_apisignature', 'payments'));
$paypalpro_is_test = (get_option('paypalpro_testmode', 'payments')) == 'n';

 
$paypalPro = new paypal_pro($paypalpro_apikey, $paypalpro_apipassword, $paypalpro_apisignature, '', '', $paypalpro_is_test, FALSE);
$resArray = $paypalPro -> hash_call($methodToCall, $nvpstr);
$ack = strtoupper($resArray["ACK"]);
$res = array();
if ($ack != "SUCCESS") {
 
 if(isset($resArray["L_LONGMESSAGE0"])){
	 	$res['error'] = 'Error: '.$resArray["L_LONGMESSAGE0"];

 } else {
	 	$res['error'] = 'Error: Please check that all provided information is correct!';

 }
 
 
	$res['ack'] = $resArray["ACK"];
	$res['correlation_id'] = $resArray['CORRELATIONID'];
$place_order = $res;
} else {

	
	//$place_order['amount'] = $currencyCode . $resArray['AMT'];
	$place_order['transaction_id'] = $resArray['TRANSACTIONID'];
	if (isset($place_order['shipping']) and intval($place_order['shipping']) > 0) {
		$place_order['shipping'] = floatval($place_order['shipping']);
	}
	
	$place_order['payment_name'] = $firstName . ' ' . $lastName;
	$place_order['payer_id'] =  substr_replace($creditCardNumber, '*****', 0, strlen($creditCardNumber)-4)  ;
	$place_order['payment_amount'] = $resArray['AMT'];
	$place_order['is_paid'] = 'y';
	$place_order['order_completed'] = 'y';
	$place_order['payment_currency'] = $currencyCode;
 $place_order['success'] = 'Your payment was successful! Transaction id: ' . $resArray['TRANSACTIONID'];
}
 
$result =  $place_order;
//print json_encode($res);
