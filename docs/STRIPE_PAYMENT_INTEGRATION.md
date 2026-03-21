# Stripe Payment Gateway Integration

This document describes the Stripe payment gateway integration for Microweber.

## Overview

The Stripe payment module provides two methods for processing payments:

1. **Stripe Checkout** (Hosted) - Redirects customers to Stripe's hosted checkout page
2. **Payment Intents API** (Embedded) - Allows embedded payment forms in your application

## Features

- ✅ Multiple payment methods (Checkout & Payment Intents)
- ✅ Webhook handling for real-time payment updates
- ✅ Automatic payment capture configuration
- ✅ Customer creation and management
- ✅ Payment verification and status tracking
- ✅ Refund handling
- ✅ Webhook signature verification
- ✅ Comprehensive test coverage

## Configuration

### Environment Variables

Add these variables to your `.env` file:

```env
STRIPE_PUBLISHABLE_KEY=pk_test_your_publishable_key
STRIPE_SECRET_KEY=sk_test_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

Or configure via Filament admin panel under Payment Providers.

### Database Settings

Configure Stripe via the Payment Providers admin panel:

1. Go to **Admin > Payment > Payment Providers**
2. Click "Create Payment Provider"
3. Select "stripe" as the provider
4. Enter your API keys:
   - **Publishable Key**: Your Stripe publishable key
   - **Secret Key**: Your Stripe secret key
   - **Webhook Secret**: Your webhook signing secret (optional but recommended)

### Payment Method Selection

Choose between two payment methods:

- **Stripe Checkout (Hosted)**: Redirects customers to Stripe's secure checkout page. Best for quick integration.
- **Payment Intents API (Embedded)**: Allows you to build custom payment forms using Stripe Elements. Best for custom checkout experiences.

### Additional Settings

- **Collect Phone Number**: Enable phone number collection during checkout
- **Automatic Capture**: Automatically capture payments when authorized (recommended for most use cases)

## Webhook Configuration

### Endpoint URL

Configure the webhook endpoint in your Stripe Dashboard:

```
https://yourdomain.com/payment/stripe/webhook
```

### Required Events

Subscribe to these webhook events:

- `checkout.session.completed` - Checkout session completed
- `payment_intent.succeeded` - Payment intent succeeded
- `payment_intent.payment_failed` - Payment failed
- `payment_intent.canceled` - Payment canceled
- `charge.refunded` - Payment refunded

### Webhook Verification

The webhook controller automatically verifies webhook signatures using your webhook secret. If no secret is configured, webhooks will be processed without signature verification (not recommended for production).

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
    // For Stripe Checkout: redirect to $result['redirectUrl']
    // For Payment Intents: use $result['clientSecret'] with Stripe Elements
}
```

### Verify Payment

```php
$data = [
    'order' => $order,
    'transaction_id' => $transactionId,
];

$result = app()->payment_method_manager->verifyPayment($providerId, $data);
```

## Webhook Events

### Checkout Session Completed

Triggered when a customer completes a Stripe Checkout session. The order is automatically updated and a payment record is created.

### Payment Intent Succeeded

Triggered when a Payment Intent succeeds. Used for embedded payment forms.

### Payment Intent Failed

Triggered when a Payment Intent fails. The order status is updated to 'failed'.

### Payment Intent Canceled

Triggered when a Payment Intent is canceled. The order status is updated to 'cancelled' (if not already completed).

### Charge Refunded

Triggered when a charge is refunded. The payment record is updated to 'refunded' status.

## Testing

Run the Stripe webhook tests:

```bash
php artisan test Modules/Payment/Tests/Feature/StripeWebhookTest.php
```

Test webhook events using Stripe CLI:

```bash
# Forward webhooks to your local server
stripe listen --forward-to http://localhost:8000/payment/stripe/webhook

# Trigger test events
stripe trigger checkout.session.completed
stripe trigger payment_intent.succeeded
```

## Security

- Webhook signatures are verified using the webhook secret
- API keys are stored encrypted in the database
- All webhook events are logged for audit purposes
- CSRF protection is disabled for webhook endpoints (stateless)

## Error Handling

The integration handles various error scenarios:

- Invalid webhook signatures (returns 400)
- Missing order references (returns 404)
- Already processed payments (idempotent)
- Failed payments (updates order status)
- Network errors (logs and returns 500)

## Architecture

```
Modules/Payment/
├── Drivers/
│   └── Stripe.php                    # Stripe payment driver
├── Http/
│   └── Controllers/
│       └── StripeWebhookController.php  # Webhook handler
├── routes/
│   └── webhooks.php                  # Webhook routes
└── Tests/
    └── Feature/
        └── StripeWebhookTest.php     # Webhook tests
```

## Support

For issues or questions:
- Check Stripe documentation: https://stripe.com/docs
- Review webhook logs in Stripe Dashboard
- Check application logs for error details
- Run tests to verify functionality

## Changelog

### 2026-03-21
- Initial Stripe Payment Gateway implementation
- Added Payment Intents API support
- Implemented comprehensive webhook handling
- Added webhook signature verification
- Created test suite for webhook events
