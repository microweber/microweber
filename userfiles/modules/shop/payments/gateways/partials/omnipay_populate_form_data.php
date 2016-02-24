<?php
if(!isset($omnipay_request_data)){
	$omnipay_request_data = $_POST;
}

 
if(isset($omnipay_request_data['first_name'])){
	$omnipay_request_data['firstName'] = (trim($omnipay_request_data['first_name']));
}
if(isset($omnipay_request_data['last_name'])){
	$omnipay_request_data['lastName'] = (trim($omnipay_request_data['last_name']));
} 
 
 
 
if(isset($omnipay_request_data['cc_first_name'])){
	$omnipay_request_data['firstName'] = (trim($omnipay_request_data['cc_first_name']));
}
if(isset($omnipay_request_data['cc_last_name'])){
	$omnipay_request_data['lastName'] = (trim($omnipay_request_data['cc_last_name']));
}
if(isset($omnipay_request_data['cc_type'])){
	$omnipay_request_data['type'] = (trim($omnipay_request_data['cc_type']));
}
if(isset($omnipay_request_data['cc_number'])){
	$omnipay_request_data['number'] = (trim($omnipay_request_data['cc_number']));
}
if(isset($omnipay_request_data['cc_month'])){
	$expDateMonth = (trim($omnipay_request_data['cc_month']));
	$padDateMonth = str_pad(trim($expDateMonth), 2, '0', STR_PAD_LEFT);
	$omnipay_request_data['expiryMonth'] = $padDateMonth;
}
if(isset($omnipay_request_data['cc_year'])){
	$omnipay_request_data['expiryYear'] = (trim($omnipay_request_data['cc_year']));
}
if(isset($omnipay_request_data['cc_verification_value'])){
	$omnipay_request_data['cvv'] = (trim($omnipay_request_data['cc_verification_value']));
}
/*$lastName = (trim($omnipay_request_data['cc_last_name']));
$creditCardType = (trim($omnipay_request_data['cc_type']));
$creditCardNumber = (trim($omnipay_request_data['cc_number']));
$expDateMonth = (trim($omnipay_request_data['cc_month']));
$padDateMonth = str_pad(trim($expDateMonth), 2, '0', STR_PAD_LEFT);
$expDateYear = (trim($omnipay_request_data['cc_year']));
$cvv2Number = (trim($omnipay_request_data['cc_verification_value']));

*/
 

$formData_populate = array(
'subject',
'firstName',
'lastName',
'number',
'expiryMonth',
'expiryYear',
'startMonth',
'startYear',
'cvv',
'issueNumber',
'type',
'billingAddress1',
'billingAddress2',
'billingCity',
'billingPostcode',
'billingState',
'billingCountry',
'billingPhone',
'shippingAddress1',
'shippingAddress2',
'shippingCity',
'shippingPostcode',
'shippingState',
'shippingCountry',
'shippingPhone',
'company',
'email'
);
$formData = array();
foreach ($formData_populate as $formData_item) {
	if (isset($params[ $formData_item ])){
		$formData[ $formData_item ] = $params[ $formData_item ];
	} elseif (isset($omnipay_request_data[ $formData_item ])) {
		$formData[ $formData_item ] = $omnipay_request_data[ $formData_item ];
	}
}

return $formData;