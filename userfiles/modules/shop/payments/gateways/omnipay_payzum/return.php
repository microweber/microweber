<?php

if (!isset($update_order['transaction_id'])) {
    return;
}

use Omnipay\Omnipay;

$api_key = get_option('payzum_api_key', 'payments');
$webhook_secret = get_option('payzum_webhook_secret', 'payments');
$test_mode = get_option('payzum_test_mode', 'payments') == 'y';

$gateway = Omnipay::create('Payzum');
$gateway->setApiKey($api_key);
$gateway->setWebhookSecret($webhook_secret);
$gateway->setTestMode($test_mode);

// Crypto confirmation is asynchronous — the buyer usually lands here before
// the invoice is final. The order is fulfilled from notify.php (signed IPN);
// this read is only to show an up-to-date status if it already settled.
$response = $gateway->fetchTransaction(
    array(
        'transactionReference' => $update_order['transaction_id'],
    )
)->send();

// isSuccessful() on this driver means the invoice is paid in full ("finished").
if ($response->isSuccessful()) {
    $data = $response->getData();
    $data['payment_amount'] = isset($data['price_amount']) ? $data['price_amount'] : null;
    $data['payment_currency'] = isset($data['price_currency']) ? $data['price_currency'] : null;
    $data['payment_status'] = isset($data['payment_status']) ? $data['payment_status'] : 'finished';

    $update_order['is_paid'] = 1;
    $update_order['payment_data'] = $data;
}
// waiting / partially_paid: leave the order as-is; notify.php settles it.
