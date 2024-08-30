<?php

if (!isset($update_order['transaction_id'])) {
    return;
}

use Omnipay\Omnipay;

$api_key = get_option('mollie_api_key', 'payments');


$gateway = Omnipay::create('Mollie');
$gateway->setApiKey($api_key);


$response = $gateway->fetchTransaction(
    array(

        'transactionReference' => $update_order['transaction_id'],

    )
)->send();
if ($response->isSuccessful()) {
    $data = $response->getData();
    if (isset($data['status'])) {

        if (isset($data['amount']) and is_array($data['amount'])) {
            $data['payment_amount'] = $data['amount']['value'];
            $data['payment_currency'] = $data['amount']['currency'];

        }
        $data['payment_status'] = $data['status'];
        if (isset($data['status']) and $data['status'] == 'paid') {
            $update_order['is_paid'] = 1;
         //   $update_order['order_completed'] = 1;

        } else {
            $update_order['is_paid'] = 0;
          //  $update_order['order_completed'] = 1;

        }
        if (isset($data['profileId'])) {
            $update_order['payer_id'] = $data['profileId'];
        }
        $update_order['payment_data'] = $data;

    }

}


