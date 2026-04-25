# `Billing` module

> **Slug:** `billing`
> **Tier:** 1
>
> Tier-1 module — owns its own data + exposes a public API.
>
> *(Auto-generated from filesystem survey on 2026-04-25;
> hand-edit to add operator-side context. The canonical
> shape lives in [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md);
> use `Modules/Settings/docs/README.md` as the
> hand-curated example.)*

## Domain

*Hand-edit this section to describe what the module does
operationally and which sibling modules it interacts
with.*

## Data model

Migrations under `Modules/Billing/database/migrations/`:

  - `database/migrations/2025_05_31_000001_update_customer_columns.php`
  - `database/migrations/2025_05_31_000002_create_subscriptions_manual_table.php`
  - `database/migrations/2025_05_31_000002_create_subscriptions_table.php`
  - `database/migrations/2025_05_31_000003_create_subscription_items_table.php`
  - `database/migrations/2025_05_31_000004_create_subscription_plans_table.php`
  - `database/migrations/2025_05_31_000005_create_subscription_plans_groups_table.php`
  - `database/migrations/2025_05_31_000007_create_subscription_plans_features_table.php`
  - `database/migrations/2025_05_31_000008_create_subscription_plans_groups_features_table.php`
  - `database/migrations/2025_05_31_000009_update_users_columns.php`
  - `database/migrations/2026_03_05_000001_update_subscription_plans_features_add_columns.php`
  - `database/migrations/2026_03_05_000002_create_subscription_cancel_reasons_table.php`
  - `database/migrations/2026_03_05_000003_create_webhook_logs_table.php`
  - `database/migrations/2026_03_05_033328_add_currency_to_subscription_plans.php`
  - `database/migrations/2026_03_15_000001_add_is_active_to_subscription_plans.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Billing\Models\BillingUser` | `Models/BillingUser.php` |
| `Modules\Billing\Models\Subscription` | `Models/Subscription.php` |
| `Modules\Billing\Models\SubscriptionCancelReason` | `Models/SubscriptionCancelReason.php` |
| `Modules\Billing\Models\SubscriptionCustomer` | `Models/SubscriptionCustomer.php` |
| `Modules\Billing\Models\SubscriptionItem` | `Models/SubscriptionItem.php` |
| `Modules\Billing\Models\SubscriptionManual` | `Models/SubscriptionManual.php` |
| `Modules\Billing\Models\SubscriptionPlan` | `Models/SubscriptionPlan.php` |
| `Modules\Billing\Models\SubscriptionPlanFeature` | `Models/SubscriptionPlanFeature.php` |
| `Modules\Billing\Models\SubscriptionPlanGroup` | `Models/SubscriptionPlanGroup.php` |
| `Modules\Billing\Models\SubscriptionPlanGroupFeature` | `Models/SubscriptionPlanGroupFeature.php` |
| `Modules\Billing\Models\WebhookLog` | `Models/WebhookLog.php` |

## API endpoints

Route files:

  - `routes/admin.php`
  - `routes/api.php`
  - `routes/web.php`
  - `routes/webhooks.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Billing\Http\Controllers\Admin\AdminController`
  - `Modules\Billing\Http\Controllers\BillingCheckoutController`
  - `Modules\Billing\Http\Controllers\CustomerProfileController`
  - `Modules\Billing\Http\Controllers\SubscribeToPlanController`
  - `Modules\Billing\Http\Controllers\WebhookController`

## Service classes

  - `Modules\Billing\Services\StripeService`
  - `Modules\Billing\Services\SubscriptionManager`
  - `Modules\Billing\Services\UserDemoActivate`

## Events

  - `Modules\Billing\Events\PaymentSucceeded`
  - `Modules\Billing\Listeners\StripeEventListener`

## Filament admin

  - `Modules\Billing\Filament\Admin\Pages\Dashboard`
  - `Modules\Billing\Filament\Admin\Pages\Settings`
  - `Modules\Billing\Filament\Admin\Resources\BillingUserResource`
  - `Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages\CreateUser`
  - `Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages\EditUser`
  - `Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages\ListUsers`
  - `Modules\Billing\Filament\Admin\Resources\BillingUserResource\RelationManagers\SubscriptionsRelationManager`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\Pages\CreateSubscriptionPlanGroups`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\Pages\EditSubscriptionPlanGroups`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\Pages\ListSubscriptionPlanGroups`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\Pages\ViewSubscriptionPlanGroups`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\RelationManagers\FeaturesRelationManager`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\RelationManagers\PlansRelationManager`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource\Pages\CreateSubscriptionPlan`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource\Pages\EditSubscriptionPlan`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource\Pages\ListSubscriptionPlans`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionResource`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionResource\Pages\EditSubscription`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionResource\Pages\ListSubscriptions`
  - `Modules\Billing\Filament\Admin\Resources\SubscriptionResource\Pages\ViewSubscription`
  - `Modules\Billing\Filament\Admin\Widgets\LatestSubscriptionsWidget`
  - `Modules\Billing\Filament\Admin\Widgets\StatsOverviewWidget`
  - `Modules\Billing\Filament\Pages\ActiveSubscriptions`
  - `Modules\Billing\Filament\Pages\PurchaseCancelPage`
  - `Modules\Billing\Filament\Pages\PurchaseSuccessPage`
  - `Modules\Billing\Filament\Pages\SubscriptionCancelPage`
  - `Modules\Billing\Filament\Pages\SubscriptionSuccessPage`
  - `Modules\Billing\Filament\Pages\UserSubscriptionPanel`

## Tests

Run: `php vendor/bin/phpunit Modules/Billing/Tests`

Test files:

  - `Tests/Feature/SubscriptionManagerTest.php`
  - `Tests/Filament/BillingResourceTest.php`
  - `Tests/Unit/AuthorizationTest.php`
  - `Tests/Unit/BillingTestCase.php`
  - `Tests/Unit/Console/AutoActivateFreeTrialTest.php`
  - `Tests/Unit/Filament/SubscriptionPlanGroupsResourceTest.php`
  - `Tests/Unit/Filament/SubscriptionPlanResourceTest.php`
  - `Tests/Unit/Filament/SubscriptionResourceTest.php`
  - `Tests/Unit/Filament/WidgetsTest.php`
  - `Tests/Unit/PestExampleTest.php`
  - `Tests/Unit/SubscriptionPlanTest.php`
  - `Tests/Unit/SubscriptionTest.php`
  - `Tests/Unit/WebhookControllerTest.php`

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
