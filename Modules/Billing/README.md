# Billing

Subscription billing and plan management built on Laravel Cashier (Stripe). Handles recurring payments, subscription plans with feature groups, free trials, and webhook processing.

## Key Features

- Subscription plan management with feature lists
- Plan groups for tiered pricing presentation
- Stripe integration via Laravel Cashier
- Manual subscription support (non-Stripe)
- Free trial auto-activation
- Webhook logging and Stripe event processing
- Cancellation with reason tracking
- Frontend subscription management panel
- Multi-currency support for plans

## Key Classes

| Class | Purpose |
|---|---|
| `Services\StripeService` | Stripe API operations (singleton) |
| `Services\SubscriptionManager` | Subscription lifecycle management (singleton) |
| `Services\UserDemoActivate` | Free trial activation service |
| `Models\Subscription` | Cashier subscription model |
| `Models\SubscriptionPlan` | Plan definitions with pricing |
| `Models\SubscriptionPlanGroup` | Plan grouping for display |
| `Models\SubscriptionPlanFeature` | Features included in a plan |
| `Models\SubscriptionCustomer` | Cashier customer model |
| `Models\WebhookLog` | Stripe webhook audit log |
| `Listeners\StripeEventListener` | Processes incoming Stripe webhook events |

## Events

- `PaymentSucceeded` -- dispatched on successful payment
- Listens to: `WebhookReceived` (Laravel Cashier) via `StripeEventListener`

## Database Tables

- `subscriptions` / `subscription_items` -- Cashier subscription data
- `subscriptions_manual` -- non-Stripe manual subscriptions
- `subscription_plans` -- plan definitions (with currency, active flag)
- `subscription_plans_groups` -- plan grouping
- `subscription_plans_features` / `subscription_plans_groups_features` -- feature lists
- `subscription_cancel_reasons` -- cancellation reason catalog
- `webhook_logs` -- Stripe webhook audit trail

## Configuration

Stripe credentials are loaded from the `payment_providers` table (provider = `stripe`). Additional options via `get_option()`:

| Option | Group | Description |
|---|---|---|
| `cashier_billing_payment_provider_id` | `payments` | Stripe provider ID |
| `cashier_currency` | `payments` | Billing currency |
| `cashier_currency_locale` | `payments` | Currency locale |

## Admin Panel (Filament)

Livewire-based admin pages: Settings, Subscription Plans, Subscriptions, Users.

## Artisan Commands

- `php artisan billing:auto-activate-free-trial` -- activate free trials for eligible users

## Routes

Web, API, admin, and webhook routes in `routes/`.
