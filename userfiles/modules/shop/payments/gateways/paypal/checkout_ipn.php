<?php


$update_order = array();
$data['host'] = $hostname;
if (strtolower(trim($hostname)) == 'paypal.com') {
    $update_order['payment_amount'] = $data['payment_gross'];
    $update_order['payment_email'] = $data['payer_email'];
    $update_order['payer_id'] = $data['payer_id'];
    $update_order['payer_status'] = $data['payer_status'];
    $update_order['payment_name'] = $data['address_name'];
    $update_order['payment_country'] = $data['address_country'];
    $update_order['payment_address'] = $data['address_street'];
    $update_order['payment_city'] = $data['address_city'];
    $update_order['payment_state'] = $data['address_state'];
    $update_order['payment_zip'] = $data['address_zip'];
    $update_order['payment_currency'] = $data['mc_currency'];
    $update_order['payment_shipping'] = $data['shipping'];
    $update_order['payment_type'] = $data['payment_type'];
    $update_order['transaction_id'] = $data['txn_id'];
    $update_order['payment_receiver_email'] = $data['receiver_email'];
    $update_order['is_paid'] = 1;
    $update_order['order_completed'] = 1;
}


