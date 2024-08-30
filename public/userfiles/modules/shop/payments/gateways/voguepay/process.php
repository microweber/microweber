<?php

$voguepay_developer_code = get_option('voguepay_developer_code', 'payments');
$voguepay_merchant_id = get_option('voguepay_merchant_id', 'payments');
$voguepay_test_mode = (get_option('voguepay_test_mode', 'payments') == 1);


if (!$voguepay_developer_code or !$voguepay_merchant_id) {
    $place_order['error'] = 'API keys are not set.';
    return;
}


$host = mw()->url_manager->hostname();

$rand = uniqid();


$form = "<form method='POST' id='gateway_form_{$rand}' action='https://voguepay.com/pay/' name='gateway_form'>

<input type='hidden' name='v_merchant_id' value='{$voguepay_merchant_id}' />
<input type='hidden' name='merchant_ref' value='{$place_order['payment_verify_token']}' />
<input type='hidden' name='memo' value='{$place_order['item_name']}' />

<input type='hidden' name='notify_url' value='{$mw_ipn_url}' />
<input type='hidden' name='success_url' value='{$mw_return_url}' />
<input type='hidden' name='fail_url' value='{$mw_cancel_url}' />
<input type='hidden' name='store_id' value='{$host}' />

<input type='hidden' name='developer_code' value='{$voguepay_developer_code}' />
 
<input type='hidden' name='total' value='{$place_order['amount']}' />
<input type='hidden' name='cur' value='{$place_order['currency']}' />

<input type='image' src='//voguepay.com/images/buttons/buynow_blue.png' alt='Redirecting to payment gateway...' />

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
$place_order['success'] =$form;



