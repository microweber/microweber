<?php

use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Omnipay;

$api_key = get_option('payzum_api_key', 'payments');
$webhook_secret = get_option('payzum_webhook_secret', 'payments');
$test_mode = get_option('payzum_test_mode', 'payments') == 'y';

$gateway = Omnipay::create('Payzum');
$gateway->setApiKey($api_key);
$gateway->setWebhookSecret($webhook_secret);
$gateway->setTestMode($test_mode);

try {
    // The HMAC-SHA-512 signature over the raw request bytes and the replay
    // window are verified before any field is readable; a forged or stale
    // delivery throws and the order is left untouched.
    $notification = $gateway->acceptNotification();

    // A settled order must never be downgraded: retries re-deliver terminal
    // events, so only the transition to paid changes anything here.
    if ($notification->getTransactionStatus() === NotificationInterface::STATUS_COMPLETED) {
        $update_order['transaction_id'] = $notification->getTransactionReference();
        $update_order['is_paid'] = 1;
        $update_order['order_completed'] = 1;
        $update_order['success'] = 'Your payment was successful!';
        $update_order['payment_data'] = $notification->getData();
    } elseif ($notification->getTransactionStatus() === NotificationInterface::STATUS_FAILED) {
        // expired or failed — underpayments stay pending and never fulfil.
        if (!isset($update_order['is_paid']) or !$update_order['is_paid']) {
            $update_order['is_paid'] = 0;
            $update_order['payment_data'] = $notification->getData();
        }
    }
} catch (\Exception $e) {
    // Invalid signature or stale event: acknowledge nothing.
    return;
}
