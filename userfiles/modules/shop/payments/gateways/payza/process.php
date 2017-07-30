<?php


$payza_username = get_option('payza_username', 'payments');
$payza_api_password = get_option('payza_api_password', 'payments');
$ap_test = get_option('payza_testmode', 'payments') == 'y';
if (!$payza_username) {
    $place_order['error'] = 'Payza USERNAME s are not set.';
    return;
}

$currencyCode = $place_order['currency'];
$amount = $place_order['amount'];
$payment_shipping = $place_order['shipping'];



$host = mw()->url_manager->hostname();





$myUserName = trim($payza_username); // Your Payza user name which is your email address
$apiPassword = trim($payza_api_password); // Your API password
$websiteUrl = $host; // Your website URL
$purchaseType = 'item'; // The type of the purchase
$itemName = $place_order['item_name']; // The name of the item or service
$itemDescription = $place_order['item_name']; // Description for the item
$itemCode = $place_order['item_name']; // Item code
$amount = $place_order['amount']; // The amount of the purchase
$currency = $currencyCode; // The 3 letter ISO-4217 currency code
$shippingAmount = $place_order['shipping']; // Shipping charges
$addAmount = '0'; // Additional charges
$taxAmount = '0'; // Tax charges
$returnUrl = $mw_return_url; // URL to redirect if the purchase is successful
$cancelUrl = $mw_cancel_url; // URL to redirect if the purchase is canceled
$cancelUrl = $mw_cancel_url; // URL to redirect if the purchase is canceled
$apc_1 = ''; // Additional info field 1
$apc_2 = ''; // Additional info field 2
$apc_3 = ''; // Additional info field 3
$apc_4 = ''; // Additional info field 4
$apc_5 = ''; // Additional info field 5
$apc_6 = ''; // Additional info field 6

$taxAmount = floatval($shippingAmount);
$shippingAmount = floatval($taxAmount);
$amount = floatval($amount);
$addAmount = floatval($addAmount);


$responseArray; // The API's response variables
$server = 'api.payza.com'; // The server address of the API
$url = '/svc/api.svc/GetPaymentToken'; // The exact URL of the API
$dataToSend = ''; // The data that will be sent to the API

$post_data = array();
//$post_data['USER'] = $myUserName;
//$post_data['PASSWORD'] = $apiPassword;
$post_data['ap_merchant'] = $myUserName;
$post_data['ap_url'] = $websiteUrl;
$post_data['ap_purchasetype'] = $purchaseType;
$post_data['ap_itemname'] = $itemName;
$post_data['ap_description'] = $itemDescription;
//$post_data['ap_itemcode'] = $itemCode;
$post_data['ap_amount'] = $amount;
$post_data['ap_currency'] = $currency;
$post_data['ap_shippingcharges'] = $shippingAmount;
$post_data['ap_additionalcharges'] = $addAmount;
$post_data['ap_taxamount'] = $taxAmount;

$post_data['ap_ipnversion'] = 2;
$post_data['ap_test'] = $ap_test;

$post_data['ap_returnurl'] = $returnUrl;
$post_data['ap_cancelurl'] = $cancelUrl;
$post_data['ap_alerturl'] = $mw_ipn_url;

$rand = uniqid();

$form_fields = '';
foreach ($post_data as $k=>$v){
    $form_fields .=   " <input type='hidden' name='{$k}' value='{$v}' /> ";
}

$form = "<form method=\"post\" name='gateway_form' id='gateway_form_{$rand}' action=\"https://secure.payza.com/checkout\">
    
    {$form_fields} 
 


    <input type=\"image\" src=\"https://www.payza.com/images/payza-buy-now.png\"/>
</form>";

$aj = mw()->url_manager->is_ajax();

if ($aj == false) {
    $ret = '';
    $ret .= "<html>\n";
    $ret .= "<head><title>Processing Payment...</title></head>\n";
    $ret .= "<body onLoad=\"document.forms['gateway_form'].submit();\">\n";
    $ret .= $form;
    $ret .= "</body></html>\n";
    $form = $ret;

} else {
    $form .= '<script  type="text/javascript">
$(document).ready(function(){
 $("#gateway_form_' . $rand . '").submit();
});
</script>';
}




$place_order['order_completed'] = 1;
$place_order['is_paid'] = 0;
$place_order['success'] = $form;

