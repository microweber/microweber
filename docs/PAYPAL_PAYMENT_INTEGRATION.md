# PayPal Payment Gateway Integration

This document describes the PayPal payment gateway integration for Microweber.

## Overview

The PayPal payment module provides PayPal Express Checkout integration supporting both REST API and Classic API credentials.

## Features

- ✅ Express Checkout (redirect to PayPal)
- ✅ REST API and Classic API support
- ✅ Webhook handling for real-time payment updates
- ✅ Automatic payment capture
- ✅ Payment verification and status tracking
- ✅ Refund handling
- ✅ Dispute notifications
- ✅ Comprehensive test coverage

## Configuration

### Environment Variables

Add these variables to your `.env` file:

```env
# PayPal Payment Gateway
# Get your API credentials from https://developer.paypal.com/dashboard/
# Use REST API credentials (Client ID and Secret) from your PayPal App
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_client_secret
PAYPAL_WEBHOOK_ID=your_webhook_id
PAYPAL_TEST_MODE=true
```

Or configure via Filament admin panel under Payment Providers.

### Database Settings

Configure PayPal via the Payment Providers admin panel:

1. Go to **Admin > Payment > Payment Providers**
2. Click "Create Payment Provider"
3. Select "paypal" as the provider
4. Enter your API credentials:

#### REST API Credentials (Recommended)
- **Client ID**: Your PayPal app Client ID
- **Client Secret**: Your PayPal app Secret
- **Webhook ID**: Your webhook ID (optional)

#### Classic API Credentials (Legacy)
- **API Username**: Your PayPal Classic API username
- **API Password**: Your PayPal Classic API password
- **API Signature**: Your PayPal Classic API signature

### Additional Settings

- **Test Mode**: Enable to use PayPal Sandbox environment
- **Webhook ID**: For webhook verification in PayPal Dashboard

## Webhook Configuration

### Endpoint URL

Configure the webhook endpoint in your PayPal Developer Dashboard:

```
https://yourdomain.com/payment/paypal/webhook
```

### Required Events

Subscribe to these webhook events:

- `PAYMENT.CAPTURE.COMPLETED` - Payment captured successfully
- `CHECKOUT.ORDER.COMPLETED` - Checkout order completed
- `CHECKOUT.ORDER.APPROVED` - Checkout order approved by customer
- `PAYMENT.CAPTURE.DENIED` - Payment capture denied
- `PAYMENT.CAPTURE.REFUNDED` - Payment refunded
- `CUSTOMER.DISPUTE.CREATED` - Customer dispute created

### Webhook Verification

PayPal webhooks use certificate-based verification which is handled automatically. The webhook endpoint does not require signature verification like Stripe.

## API Usage

### Process Payment

```php
$data = [
    'amount' => 99.99,
    'currency' => 'USD',
    'email' => 'customer@example.com',
    'order_reference_id' => 'ORDER-123',
    'returnUrl' => route('checkout.success'),
    'cancelUrl' => route('checkout.cancel'),
];

$result = app()->payment_method_manager->process($providerId, $data);

if ($result['success']) {
    // Redirect customer to $result['redirectUrl']
    return redirect($result['redirectUrl']);
}
```

### Verify Payment

After customer returns from PayPal:

```php
$data = [
    'token' => $request->get('token'),
    'PayerID' => $request->get('PayerID'),
    'amount' => $order->amount,
    'currency' => $order->currency,
];

$result = app()->payment_method_manager->verifyPayment($providerId, $data);

if ($result['success']) {
    // Payment completed successfully
    $transactionId = $result['transactionId'];
}
```

## Webhook Events

### PAYMENT.CAPTURE.COMPLETED

Triggered when a PayPal payment is successfully captured. The order is automatically updated and a payment record is created.

### CHECKOUT.ORDER.COMPLETED

Triggered when a checkout order is completed. Similar to payment capture but for Express Checkout flow.

### CHECKOUT.ORDER.APPROVED

Triggered when a customer approves the order on PayPal. The actual capture happens separately.

### PAYMENT.CAPTURE.DENIED

Triggered when a payment capture is denied. The order status is updated to 'failed'.

### PAYMENT.CAPTURE.REFUNDED

Triggered when a payment is refunded. The payment record is updated to 'refunded' status.

### CUSTOMER.DISPUTE.CREATED

Triggered when a customer creates a dispute. A warning is logged for admin attention.

## Testing

Run the PayPal webhook tests:

```bash
php artisan test Modules/Payment/Tests/Feature/PayPalWebhookTest.php
```

Run the PayPal driver tests:

```bash
php artisan test Modules/Payment/Tests/Unit/PayPalDriverTest.php
```

### PayPal Sandbox Testing

1. Create a PayPal Developer account at https://developer.paypal.com
2. Create a Sandbox app to get Client ID and Secret
3. Enable Test Mode in settings
4. Use Sandbox buyer accounts for testing

## Security

- All webhook endpoints are stateless (CSRF protection disabled)
- API credentials are stored encrypted in the database
- Webhook events are logged for audit purposes
- Idempotent processing prevents duplicate payments

## Error Handling

The integration handles various error scenarios:

- Invalid webhook payloads (returns 400)
- Missing order references (returns 404)
- Already processed payments (idempotent, returns 200)
- Failed payments (updates order status)
- Network errors (logs and returns 500)

## Architecture

```
Modules/Payment/
├── Drivers/
│   └── PayPal.php                    # PayPal payment driver
├── Http/
│   └── Controllers/
│       └── PayPalWebhookController.php # Webhook handler
├── routes/
│   └── webhooks.php                  # Webhook routes
└── Tests/
    ├── Feature/
    │   └── PayPalWebhookTest.php     # Webhook tests
    └── Unit/
        └── PayPalDriverTest.php      # Driver tests
```

## Support

For issues or questions:
- Check PayPal documentation: https://developer.paypal.com/docs/
- Review webhook logs in PayPal Developer Dashboard
- Check application logs for error details
- Run tests to verify functionality

## Changelog

### 2026-03-21
- Initial PayPal Payment Gateway implementation
- Added Express Checkout support (REST API and Classic API)
- Implemented comprehensive webhook handling
- Created test suite for webhook events
- Added documentation
