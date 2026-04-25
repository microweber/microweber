# `Billing` module

> **Slug:** `billing`
> **Tier:** 1
>
> *Auto-generated from filesystem survey on 2026-04-25 with
> column / route / method extraction. Domain section is
> the only hand-edit needed; the rest of this file is
> regenerable from source.*

## Domain

*Hand-edit this section: describe what the module does
operationally, who consumes it, and which sibling modules
it interacts with.*

## Data model

### `customers` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `stripe_id` | `string` | nullable, indexed |
  | `pm_type` | `string` | nullable |
  | `status` | `string` | nullable |
  | `pm_last_four` | `string` | nullable |
  | `trial_ends_at` | `timestamp` | nullable |
  | `(unnamed)` | `dropColumn` | — |

### `subscriptions_manual` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `subscription_plan_id` | `integer` | nullable |
  | `user_id` | `integer` | nullable |
  | `auto_activate_free_trial_after_date` | `tinyInteger` | nullable |
  | `activate_free_trial_after_date` | `dateTime` | nullable |
  | `timestamps` | `timestamps` | — |

### `subscriptions` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `customer_id` | `foreignId` | foreign-key |
  | `user_id` | `integer` | nullable |
  | `subscription_plan_id` | `integer` | nullable |
  | `subscription_customer_id` | `string` | nullable |
  | `type` | `string` | nullable |
  | `name` | `string` | nullable |
  | `stripe_id` | `string` | nullable |
  | `stripe_status` | `string` | nullable |
  | `stripe_price` | `string` | nullable |
  | `quantity` | `integer` | nullable |
  | `trial_ends_at` | `timestamp` | nullable |
  | `starts_at` | `timestamp` | nullable |
  | `ends_at` | `timestamp` | nullable |
  | `timestamps` | `timestamps` | — |
  | `stripe_id` | `unique` | — |

### `subscription_items` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `subscription_id` | `foreignId` | foreign-key |
  | `stripe_id` | `string` | — |
  | `stripe_product` | `string` | — |
  | `stripe_price` | `string` | — |
  | `quantity` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |
  | `stripe_id` | `unique` | — |

### `subscription_plans` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | nullable |
  | `sku` | `string` | nullable |
  | `description` | `longText` | nullable |
  | `type` | `string` | nullable |
  | `subscription_plan_group_id` | `integer` | nullable |
  | `plan_data` | `json` | nullable |
  | `price` | `longText` | nullable |
  | `discount_price` | `longText` | nullable |
  | `save_price` | `longText` | nullable |
  | `save_price_badge` | `longText` | nullable |
  | `auto_apply_coupon_code` | `longText` | nullable |
  | `billing_interval` | `longText` | nullable |
  | `trial_days` | `integer` | nullable |
  | `default_interval` | `string` | nullable |
  | `remote_provider` | `string` | nullable |
  | `remote_provider_id` | `string` | nullable |
  | `remote_provider_price_id` | `string` | nullable |
  | `alternative_annual_plan_id` | `integer` | nullable |
  | `sort_order` | `integer` | nullable |
  | `is_hidden` | `integer` | nullable |
  | `sku` | `unique` | — |
  | `currency` | `string` | has-default |
  | `currency` | `dropColumn` | — |
  | `is_active` | `boolean` | has-default |
  | `is_active` | `dropColumn` | — |

### `subscription_plans_groups` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | nullable |
  | `description` | `text` | nullable |
  | `sku` | `string` | nullable |
  | `type` | `string` | nullable |
  | `position` | `integer` | nullable |
  | `icon` | `string` | nullable |

### `subscription_plans_features` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `subscription_plan_id` | `integer` | nullable |
  | `key` | `string` | nullable |
  | `value` | `string` | nullable |
  | `position` | `string` | nullable |
  | `description` | `text` | nullable |
  | `limit` | `string` | nullable |
  | `description` | `dropColumn` | — |
  | `limit` | `dropColumn` | — |

### `subscription_plans_groups_features` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `subscription_plan_group_id` | `integer` | nullable |
  | `name` | `string` | nullable |
  | `sort` | `integer` | nullable |

### `users` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `demo_expired` | `string` | nullable |
  | `demo_started_at` | `dateTime` | nullable |
  | `demo_expired_at` | `dateTime` | nullable |
  | `(unnamed)` | `dropColumn` | — |

### `subscription_cancel_reasons` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `user_id` | `unsignedInteger` | nullable |
  | `subscription_id` | `unsignedBigInteger` | nullable |
  | `stripe_session_id` | `string` | nullable |
  | `reason` | `text` | nullable |
  | `ip_address` | `string` | nullable |
  | `timestamps` | `timestamps` | — |
  | `user_id` | `index` | — |
  | `subscription_id` | `index` | — |

### `webhook_logs` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `provider` | `string` | indexed |
  | `event_type` | `string` | indexed |
  | `event_id` | `string` | unique |
  | `payload` | `json` | — |
  | `status` | `string` | indexed, has-default |
  | `attempts` | `unsignedInteger` | has-default |
  | `error_message` | `text` | nullable |
  | `processed_at` | `timestamp` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Billing\Models\BillingUser`

Source: `Models/BillingUser.php`. Table: `users`. 

**Fillable:** `subscription_plan_id`, `auto_activate_free_trial_after_date`, `activate_free_trial_after_date`

### `Modules\Billing\Models\Subscription`

Source: `Models/Subscription.php`. Table: `subscriptions`. 

### `Modules\Billing\Models\SubscriptionCancelReason`

Source: `Models/SubscriptionCancelReason.php`. Table: `subscription_cancel_reasons`. 

**Fillable:** `user_id`, `subscription_id`, `stripe_session_id`, `reason`, `ip_address`

### `Modules\Billing\Models\SubscriptionCustomer`

Source: `Models/SubscriptionCustomer.php`. 

### `Modules\Billing\Models\SubscriptionItem`

Source: `Models/SubscriptionItem.php`. Table: `subscription_items`. 

### `Modules\Billing\Models\SubscriptionManual`

Source: `Models/SubscriptionManual.php`. Table: `subscriptions_manual`. 

**Fillable:** `user_id`, `activate_free_trial_after_date`, `auto_activate_free_trial_after_date`

**Casts:**

  - `activate_free_trial_after_date` → `datetime`

### `Modules\Billing\Models\SubscriptionPlan`

Source: `Models/SubscriptionPlan.php`. Table: `subscription_plans`. 

**Fillable:** `name`, `sku`, `subscription_plan_group_id`, `remote_provider`, `remote_provider_id`, `remote_provider_price_id`, `price`, `currency`, `discount_price`, `save_price`, `save_price_badge`, `auto_apply_coupon_code`, `billing_interval`, `trial_days`, `alternative_annual_plan_id`, `description`, `sort_order`, `is_active`

**Casts:**

  - `plan_data` → `array`
  - `is_active` → `boolean`

### `Modules\Billing\Models\SubscriptionPlanFeature`

Source: `Models/SubscriptionPlanFeature.php`. Table: `subscription_plans_features`. 

**Fillable:** `subscription_plan_id`, `key`, `value`, `description`, `limit`, `position`

### `Modules\Billing\Models\SubscriptionPlanGroup`

Source: `Models/SubscriptionPlanGroup.php`. Table: `subscription_plans_groups`. 

**Fillable:** `name`, `description`, `sku`, `type`, `position`, `icon`

**Casts:**

  - `position` → `integer`

### `Modules\Billing\Models\SubscriptionPlanGroupFeature`

Source: `Models/SubscriptionPlanGroupFeature.php`. Table: `subscription_plans_groups_features`. 

**Fillable:** `name`, `sort`

### `Modules\Billing\Models\WebhookLog`

Source: `Models/WebhookLog.php`. 

**Fillable:** `provider`, `event_type`, `event_id`, `payload`, `status`, `attempts`, `error_message`, `processed_at`

**Casts:**

  - `payload` → `array`
  - `processed_at` → `datetime`

## API endpoints

### `routes/web.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/checkout/billing-portal` | `BillingCheckoutController::billingPortal` |
  | `GET` | `/checkout/subscription-success` | `BillingCheckoutController::subscriptionSuccess` |
  | `GET` | `/checkout/subscription-cancel` | `BillingCheckoutController::subscriptionCancel` |
  | `GET` | `/checkout/purchase-success` | `BillingCheckoutController::purchaseSuccess` |
  | `GET` | `/checkout/purchase-cancel` | `BillingCheckoutController::purchaseCancel` |
  | `POST` | `/billing/subscribe-to-plan` | `SubscribeToPlanController::subscribeToPlan` |
  | `POST` | `/billing/save-customer-profile` | `CustomerProfileController::saveCustomerProfile` |

## Controllers

### `Modules\Billing\Http\Controllers\Admin\AdminController`

Source: `Http/Controllers/Admin/AdminController.php`.

  - `index(Request $request)`
  - `users(Request $request)`
  - `subscriptionPlans(Request $request)`
  - `subscriptionPlanGroups(Request $request)`
  - `settings(Request $request)`

### `Modules\Billing\Http\Controllers\BillingCheckoutController`

Source: `Http/Controllers/BillingCheckoutController.php`.

  - `billingPortal(Request $request)`
  - `subscriptionSuccess(Request $request)`
  - `subscriptionCancel(Request $request)`
  - `purchaseSuccess(Request $request)`
  - `purchaseCancel(Request $request)`

### `Modules\Billing\Http\Controllers\CustomerProfileController`

Source: `Http/Controllers/CustomerProfileController.php`.

  - `saveCustomerProfile(Request $request)`

### `Modules\Billing\Http\Controllers\SubscribeToPlanController`

Source: `Http/Controllers/SubscribeToPlanController.php`.

  - `subscribeToPlan(\Illuminate\Http\Request $request)`
  - `swapSubscription($subscriptionCustomer, $plan, $newPlan)`
  - `newSubscription($subscriptionCustomer, $plan)`

### `Modules\Billing\Http\Controllers\WebhookController`

Source: `Http/Controllers/WebhookController.php`.

  - `handleWebhook(Request $request)`

## Service classes

### `Modules\Billing\Services\StripeService`

Source: `Services/StripeService.php`.

  - `getProducts(array $params = [])`
  - `getPrices(array $params = [])`
  - `getInvoices(array $params = [])`
  - `getPaymentProivderId(): int`
  - `getPaymentProivderType(): string`
  - `testConnection(): bool`
  - `syncProducts(): int`
  - `syncCustomers(): int`

### `Modules\Billing\Services\SubscriptionManager`

Source: `Services/SubscriptionManager.php`.

  - `getSubscriptionCustomer()`
  - `subscribeToPlan($sku, $referer = null)`
  - `swapSubscription(SubscriptionCustomer $subscriptionCustomer, $plan, $newPlan)`
  - `newSubscription(SubscriptionCustomer $subscriptionCustomer, $plan): \Laravel\Cashier\Checkout`
  - `newPurchase(SubscriptionCustomer $subscriptionCustomer, $plan): \Laravel\Cashier\Checkout`

### `Modules\Billing\Services\UserDemoActivate`

Source: `Services/UserDemoActivate.php`.

  - `activate($userId)`
  - `deactivate($userId)`
  - `get($userId)`

## Events

  - `Modules\Billing\Events\PaymentSucceeded`
  - `Modules\Billing\Listeners\StripeEventListener`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Billing\Filament\Admin\Pages\Dashboard` | — | — |
  | `Modules\Billing\Filament\Admin\Pages\Settings` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\BillingUserResource` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages\CreateUser` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages\EditUser` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages\ListUsers` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\BillingUserResource\RelationManagers\SubscriptionsRelationManager` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource` | Billing | Plan Groups |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\Pages\CreateSubscriptionPlanGroups` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\Pages\EditSubscriptionPlanGroups` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\Pages\ListSubscriptionPlanGroups` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\Pages\ViewSubscriptionPlanGroups` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\RelationManagers\FeaturesRelationManager` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\RelationManagers\PlansRelationManager` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource` | Billing | — |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource\Pages\CreateSubscriptionPlan` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource\Pages\EditSubscriptionPlan` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource\Pages\ListSubscriptionPlans` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionResource` | Billing | Subscriptions |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionResource\Pages\EditSubscription` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionResource\Pages\ListSubscriptions` | — | — |
  | `Modules\Billing\Filament\Admin\Resources\SubscriptionResource\Pages\ViewSubscription` | — | — |
  | `Modules\Billing\Filament\Admin\Widgets\LatestSubscriptionsWidget` | — | — |
  | `Modules\Billing\Filament\Admin\Widgets\StatsOverviewWidget` | — | — |
  | `Modules\Billing\Filament\Pages\ActiveSubscriptions` | — | — |
  | `Modules\Billing\Filament\Pages\PurchaseCancelPage` | — | — |
  | `Modules\Billing\Filament\Pages\PurchaseSuccessPage` | — | — |
  | `Modules\Billing\Filament\Pages\SubscriptionCancelPage` | — | — |
  | `Modules\Billing\Filament\Pages\SubscriptionSuccessPage` | — | — |
  | `Modules\Billing\Filament\Pages\UserSubscriptionPanel` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Billing/Tests`

### `Tests/Feature/SubscriptionManagerTest.php`

  - `it_subscribe_to_plan_returns_error_for_invalid_sku`

### `Tests/Filament/BillingResourceTest.php`

  - `it_subscription_plan_resource_exists`
  - `it_billing_user_resource_exists`
  - `it_subscription_plan_resource_has_model`

### `Tests/Unit/Filament/WidgetsTest.php`

  - `stats_overview_widget_has_sort_order_zero`
  - `latest_subscriptions_widget_has_polling_interval_of_60_seconds`
  - `latest_subscriptions_widget_displays_correct_columns`
  - `widgets_are_registered_on_dashboard`

### `Tests/Unit/PestExampleTest.php`

  - `test_subscription_can_be_created_with_factory`
  - `test_subscription_has_required_attributes`
  - `test_can_validate_subscription_data`

## Service providers

  - `Modules\Billing\Providers\BillingCashierServiceProvider`
  - `Modules\Billing\Providers\BillingEventServiceProvider`
  - `Modules\Billing\Providers\BillingFilamentAdminPanelProvider`
  - `Modules\Billing\Providers\BillingFilamentFrontendPanelProvider`
  - `Modules\Billing\Providers\BillingServiceProvider`
  - `Modules\Billing\Providers\EventServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
