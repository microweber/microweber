# `Order` module

> **Slug:** `order`
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

Migrations under `Modules/Order/database/migrations/`:

  - `database/migrations/2020_00_00_0000012_create_orders_table.php`
  - `database/migrations/2026_03_05_000001_create_order_cancel_reasons_table.php`
  - `database/migrations/2026_03_21_000001_add_indexes_to_cart_orders.php`
  - `database/migrations/2026_03_30_000001_add_user_id_to_cart_orders.php`
  - `database/migrations/2026_03_30_000002_backfill_user_id_from_created_by_in_cart_orders.php`
  - `database/migrations/2026_04_02_000001_create_order_status_history_table.php`
  - `database/migrations/2026_04_02_000002_add_shipping_tracking_to_cart_orders.php`
  - `database/migrations/2026_04_02_000003_create_order_refunds_table.php`
  - `database/migrations/2026_04_03_000001_fix_order_tables_column_types_and_indexes.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Order\Models\ModelFilters\OrderFilter` | `Models/ModelFilters/OrderFilter.php` |
| `Modules\Order\Models\Order` | `Models/Order.php` |
| `Modules\Order\Models\OrderAnonymousClient` | `Models/OrderAnonymousClient.php` |
| `Modules\Order\Models\OrderCancelReason` | `Models/OrderCancelReason.php` |
| `Modules\Order\Models\OrderRefund` | `Models/OrderRefund.php` |
| `Modules\Order\Models\OrderStatusHistory` | `Models/OrderStatusHistory.php` |

## API endpoints

Route files:

  - `routes/admin.php`
  - `routes/api.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Order\Http\Controllers\Admin\OrderExportController`
  - `Modules\Order\Http\Controllers\Api\OrderApiController`
  - `Modules\Order\Http\Controllers\Api\OrdersApiController`

## Service classes

  - `Modules\Order\Services\OrderService`
  - `Modules\Order\Services\OrderStatsService`

## Events

  - `Modules\Order\Events\OrderIsCreating`
  - `Modules\Order\Events\OrderIsUpdating`
  - `Modules\Order\Events\OrderWasCanceled`
  - `Modules\Order\Events\OrderWasCreated`
  - `Modules\Order\Events\OrderWasDeleted`
  - `Modules\Order\Events\OrderWasDestoyed`
  - `Modules\Order\Events\OrderWasPaid`
  - `Modules\Order\Events\OrderWasUpdated`
  - `Modules\Order\Listeners\OrderCreatedListener`
  - `Modules\Order\Listeners\OrderWasPaidListener`
  - `Modules\Order\Listeners\PaymentListener`
  - `Modules\Order\Listeners\Tratis\NewOrderNotificationTrait`

## Filament admin

  - `Modules\Order\Filament\Admin\Resources\OrderResource`
  - `Modules\Order\Filament\Admin\Resources\OrderResource\Pages\CreateOrder`
  - `Modules\Order\Filament\Admin\Resources\OrderResource\Pages\EditOrder`
  - `Modules\Order\Filament\Admin\Resources\OrderResource\Pages\ListOrders`
  - `Modules\Order\Filament\Admin\Resources\OrderResource\RelationManagers\PaymentsRelationManager`
  - `Modules\Order\Filament\Admin\Resources\OrderResource\Widgets\OrderStats`
  - `Modules\Order\Filament\Exports\OrderExporter`
  - `Modules\Order\Filament\Imports\OrderImporter`

## Tests

Run: `php vendor/bin/phpunit Modules/Order/Tests`

Test files:

  - `Tests/Filament/OrderResourceTest.php`
  - `Tests/Unit/Filament/OrderResourceTest.php`
  - `Tests/Unit/OrderApiControllerTest.php`
  - `Tests/Unit/OrderImportExportTest.php`
  - `Tests/Unit/OrderManagerTest.php`

## Service providers

  - `Modules\Order\Providers\OrderServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
