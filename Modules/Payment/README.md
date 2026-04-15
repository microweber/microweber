# Payment

Payment gateway integration with a driver-based architecture. Ships with Stripe, PayPal, Mollie, MoMo MTN, and Pay on Delivery, and supports custom payment drivers.

## Key Features

- Pluggable payment driver system via `PaymentMethodManager`
- Built-in drivers: Stripe, PayPal, Mollie, MoMo MTN, Pay on Delivery
- Payment status tracking: Pending, Completed, Cancelled, Refunded, Failed
- Payment provider configuration stored in database
- Webhook routes for async payment notifications

## Drivers

| Driver | Class |
|---|---|
| `pay_on_delivery` | `Drivers\PayOnDelivery` |
| `paypal` | `Drivers\PayPal` |
| `stripe` | `Drivers\Stripe` |
| `mollie` | `Drivers\Mollie` |
| `momomtn` | `Drivers\MomoMtn` |

Register custom drivers:

```php
app('payment_method_manager')->extend('custom', function () {
    return new CustomPaymentDriver();
});
```

## Key Classes

| Class | Purpose |
|---|---|
| `Services\PaymentMethodManager` | Driver registry (`app('payment_method_manager')`) |
| `Models\Payment` | Payment transaction record |
| `Models\PaymentProvider` | Provider configuration (keys, settings) |
| `Enums\PaymentStatus` | Status enum |
| `Drivers\AbstractPaymentMethod` | Base class for payment drivers |

## Events

- `PaymentWasCreated` / `PaymentWasUpdated` / `PaymentWasDeleted`
- `PaymentWasProcessed` -- fired after successful processing

## Database Tables

- `payment_providers` -- provider configuration and credentials
- `payments` -- individual payment transaction records

## Admin Panel (Filament)

- **PaymentProviderResource** -- configure payment gateways
- **PaymentResource** -- view and manage payment transactions

## Webhook Routes

Defined in `routes/webhooks.php` for asynchronous payment provider callbacks.

## Usage

```php
$manager = app('payment_method_manager');
$stripe = $manager->driver('stripe');
$payment = \Modules\Payment\Models\Payment::find(1);
$payment->status; // PaymentStatus enum
```
