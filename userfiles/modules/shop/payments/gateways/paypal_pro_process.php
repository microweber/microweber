<?php

 

include_once ('lib/paypal_pro.inc.php');
 
 
$firstName =urlencode(trim( $_POST['cc_first_name']));
$lastName =urlencode( trim($_POST['cc_last_name']));
$creditCardType =urlencode(trim( $_POST['cc_type']));
$creditCardNumber = urlencode(trim($_POST['cc_number']));
$expDateMonth =urlencode( trim($_POST['cc_month']));
$padDateMonth = str_pad(trim($expDateMonth), 2, '0', STR_PAD_LEFT);
$expDateYear =urlencode(trim( $_POST['cc_year']));
$cvv2Number = urlencode(trim($_POST['cc_verification_value']));






$address1 = urlencode($_POST['address']);
$address2 = '';
$city = urlencode($_POST['city']);
$state =urlencode( $_POST['state']);
$zip = urlencode($_POST['zip']);

$amount = ($place_order['amount']);
if(isset($place_order['shipping']) and intval($place_order['shipping']) > 0){
	$amount = $amount+floatval($place_order['shipping']);
}

$amount = urlencode($amount);



$currencyCode="USD";
$paymentAction = urlencode("Sale");
 
	$nvpRecurring = '';
	$methodToCall = 'doDirectPayment';
 


//$nvpstr='&PAYMENTACTION='.$paymentAction.'&AMT='.$amount.'&CREDITCARDTYPE='.$creditCardType.'&ACCT='.$creditCardNumber.'&EXPDATE='.         $padDateMonth.$expDateYear.'&CVV2='.$cvv2Number.'&FIRSTNAME='.$firstName.'&LASTNAME='.$lastName.'&STREET='.$address1.'&CITY='.$city.'&STATE='.$state.'&ZIP='.$zip.'&COUNTRYCODE=US&CURRENCYCODE='.$currencyCode.$nvpRecurring;
//$nvpstr='&PAYMENTACTION='.$paymentAction.'&AMT='.$amount.'&CREDITCARDTYPE='.$creditCardType.'&ACCT='.$creditCardNumber.'&EXPDATE='.         $padDateMonth.$expDateYear.'&CVV2='.$cvv2Number.'&FIRSTNAME='.$firstName.'&LASTNAME='.$lastName.'&STREET='.$address1.'&CITY='.$city.'&STATE='.$state.'&ZIP='.$zip.'&COUNTRYCODE=US&CURRENCYCODE='.$currencyCode.$nvpRecurring;
 
$nvpstr='&PAYMENTACTION='.$paymentAction.'&AMT='.$amount.'&CREDITCARDTYPE='.$creditCardType.'&ACCT='.$creditCardNumber.'&EXPDATE='.$padDateMonth.$expDateYear.'&CVV2='.$cvv2Number.'&FIRSTNAME='.$firstName.'&LASTNAME='.$lastName.'&COUNTRYCODE=US&CURRENCYCODE='.$currencyCode.$nvpRecurring;
 
$paypalpro_username = trim(get_option('paypalpro_username', 'payments'));
$paypalpro_apikey = trim(get_option('paypalpro_apikey', 'payments'));
$paypalpro_apipassword = trim(get_option('paypalpro_apipassword', 'payments'));
$paypalpro_apisignature = trim(get_option('paypalpro_apisignature', 'payments'));
 
 

$paypalPro = new paypal_pro($paypalpro_apikey,$paypalpro_apipassword, $paypalpro_apisignature, '', '', FALSE, FALSE );
$resArray = $paypalPro->hash_call($methodToCall,$nvpstr);
$ack = strtoupper($resArray["ACK"]);
$res = array();
if($ack!="SUCCESS")
{
	
	$res['error'] = 'Error: Please check that all provided information is correct!';
	$res['ack'] = $resArray["ACK"];
	$res['correlation_id'] = $resArray['CORRELATIONID'] ;
	 
}
else
{
	
		
	$res['success'] = 'Your payment was successful! Transaction id: '.$resArray['TRANSACTIONID']; 
	$res['amount'] = $currencyCode.$resArray['AMT'];
	$res['transaction_id'] = $resArray['TRANSACTIONID'] ;
	if(isset($place_order['shipping']) and intval($place_order['shipping']) > 0){
	$res['shipping'] = floatval($place_order['shipping']);
}
	
		$place_order['payment_amount'] = $resArray['AMT'];
$place_order['is_paid'] = 'y';
$place_order['order_completed'] = 'y';
$place_order['curency'] = $currencyCode;	
 

}
print json_encode($res);