# `Order` module

> **Slug:** `order`
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

### `cart_orders` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `order_reference_id` | `string` | nullable |
  | `amount` | `float` | nullable |
  | `order_status` | `string` | nullable, has-default |
  | `currency` | `string` | nullable |
  | `currency_code` | `string` | nullable |
  | `first_name` | `longText` | nullable |
  | `last_name` | `longText` | nullable |
  | `email` | `longText` | nullable |
  | `country` | `string` | nullable |
  | `city` | `text` | nullable |
  | `state` | `string` | nullable |
  | `zip` | `string` | nullable |
  | `address` | `longText` | nullable |
  | `address2` | `longText` | nullable |
  | `phone` | `text` | nullable |
  | `created_by` | `integer` | nullable |
  | `edited_by` | `integer` | nullable |
  | `session_id` | `string` | nullable |
  | `customer_id` | `integer` | nullable |
  | `invoice_id` | `integer` | nullable |
  | `order_completed` | `integer` | nullable |
  | `is_paid` | `integer` | nullable |
  | `url` | `text` | nullable |
  | `user_ip` | `string` | nullable |
  | `items_count` | `integer` | nullable |
  | `custom_fields_data` | `longText` | nullable |
  | `rel_id` | `string` | nullable |
  | `rel_type` | `string` | nullable |
  | `price` | `float` | nullable |
  | `other_info` | `longText` | nullable |
  | `promo_code` | `longText` | nullable |
  | `skip_promo_code` | `integer` | nullable |
  | `coupon_id` | `integer` | nullable |
  | `discount_type` | `string` | nullable |
  | `discount_value` | `float` | nullable |
  | `taxes_amount` | `float` | nullable |
  | `transaction_id` | `longText` | nullable |
  | `payment_provider` | `string` | nullable |
  | `payment_provider_id` | `integer` | nullable |
  | `payment_verify_token` | `string` | nullable |
  | `payment_amount` | `float` | nullable |
  | `payment_currency` | `string` | nullable |
  | `payment_status` | `string` | nullable |
  | `payment_shipping` | `float` | nullable |
  | `payment_data` | `longText` | nullable |
  | `shipping_provider` | `string` | nullable |
  | `shipping_provider_id` | `integer` | nullable |
  | `shipping_amount` | `float` | nullable |
  | `deleted_at` | `timestamp` | nullable |
  | `timestamps` | `timestamps` | — |
  | `(unnamed)` | `dropIndex` | — |
  | `user_id` | `unsignedBigInteger` | nullable |
  | `user_id` | `dropColumn` | — |
  | `shipping_tracking_number` | `string` | nullable |
  | `shipping_tracking_url` | `string` | nullable |
  | `(unnamed)` | `dropColumn` | — |

### `order_cancel_reasons` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `user_id` | `unsignedInteger` | nullable |
  | `order_id` | `unsignedInteger` | nullable |
  | `stripe_session_id` | `string` | nullable |
  | `reason` | `text` | nullable |
  | `ip_address` | `string` | nullable |
  | `timestamps` | `timestamps` | — |
  | `user_id` | `index` | — |
  | `order_id` | `index` | — |

### `order_status_history` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `order_id` | `unsignedInteger` | — |
  | `old_status` | `string` | nullable |
  | `new_status` | `string` | — |
  | `user_id` | `unsignedInteger` | nullable |
  | `note` | `text` | nullable |
  | `timestamps` | `timestamps` | — |
  | `order_id` | `index` | — |
  | `created_at` | `index` | — |
  | `order_id` | `unsignedBigInteger` | — |
  | `user_id` | `unsignedBigInteger` | nullable |
  | `user_id` | `index` | — |
  | `order_id` | `unsignedInteger` | — |
  | `user_id` | `unsignedInteger` | nullable |
  | `(unnamed)` | `dropIndex` | — |

### `order_refunds` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `order_id` | `unsignedBigInteger` | — |
  | `payment_id` | `unsignedBigInteger` | nullable |
  | `amount` | `decimal` | — |
  | `type` | `string` | — |
  | `reason` | `string` | nullable |
  | `note` | `text` | nullable |
  | `status` | `string` | has-default |
  | `refunded_by` | `unsignedBigInteger` | nullable |
  | `timestamps` | `timestamps` | — |
  | `order_id` | `index` | — |
  | `payment_id` | `index` | — |
  | `refunded_by` | `index` | — |
  | `(unnamed)` | `dropIndex` | — |

## Models

### `Modules\Order\Models\ModelFilters\OrderFilter`

Source: `Models/ModelFilters/OrderFilter.php`. 

### `Modules\Order\Models\Order`

Source: `Models/Order.php`. 

**Casts:**

  - `payment_data` → `array`
  - `custom_fields_data` → `array`

### `Modules\Order\Models\OrderAnonymousClient`

Source: `Models/OrderAnonymousClient.php`. 

### `Modules\Order\Models\OrderCancelReason`

Source: `Models/OrderCancelReason.php`. Table: `order_cancel_reasons`. 

**Fillable:** `user_id`, `order_id`, `stripe_session_id`, `reason`, `ip_address`

### `Modules\Order\Models\OrderRefund`

Source: `Models/OrderRefund.php`. Table: `order_refunds`. 

**Fillable:** `order_id`, `payment_id`, `amount`, `type`, `reason`, `note`, `status`, `refunded_by`

**Casts:**

  - `amount` → `decimal:2`

### `Modules\Order\Models\OrderStatusHistory`

Source: `Models/OrderStatusHistory.php`. Table: `order_status_history`. 

**Fillable:** `order_id`, `old_status`, `new_status`, `user_id`, `note`

**Casts:**

  - `order_id` → `integer`
  - `user_id` → `integer`

## API endpoints

### `routes/admin.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/export` | `OrderExportController::export` |

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `OrdersApiController::index` |
  | `GET` | `/{order}` | `OrdersApiController::show` |
  | `POST` | `/` | `OrdersApiController::store` |
  | `PUT` | `/{order}` | `OrdersApiController::update` |
  | `PATCH` | `/{order}` | `OrdersApiController::update` |
  | `DELETE` | `/{order}` | `OrdersApiController::destroy` |

## Controllers

### `Modules\Order\Http\Controllers\Admin\OrderExportController`

Source: `Http/Controllers/Admin/OrderExportController.php`.

  - `export(Request $request): StreamedResponse`

### `Modules\Order\Http\Controllers\Api\OrderApiController`

Source: `Http/Controllers/Api/OrderApiController.php`.

  - `index(Request $request)`
  - `store(Request $request)`
  - `show($id)`
  - `update(Request $request, $order)`
  - `destroy($id)`

### `Modules\Order\Http\Controllers\Api\OrdersApiController`

Source: `Http/Controllers/Api/OrdersApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(Request $request, int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

## Service classes

### `Modules\Order\Services\OrderService`

Source: `Services/OrderService.php`.

  - `place_order($place_order = array()`
  - `save($params = false)`
  - `export_orders()`
  - `update_quantities($order_id = false)`

### `Modules\Order\Services\OrderStatsService`

Source: `Services/OrderStatsService.php`.

  - `getOrdersTotalSumForPeriod($params = []): int`
  - `getOrdersCountForPeriod($params = []): int`
  - `getBestSellingCategoriesForPeriod($params = []): array`
  - `getBestSellingProductsForPeriod($params = []): array`
  - `getOrderItemsCountForPeriod($params = []): int`
  - `getOrdersCountGroupedByDate($params = []): array`

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

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Order\Filament\Admin\Resources\OrderResource` | Shop | — |
  | `Modules\Order\Filament\Admin\Resources\OrderResource\Pages\CreateOrder` | — | — |
  | `Modules\Order\Filament\Admin\Resources\OrderResource\Pages\EditOrder` | — | — |
  | `Modules\Order\Filament\Admin\Resources\OrderResource\Pages\ListOrders` | — | — |
  | `Modules\Order\Filament\Admin\Resources\OrderResource\RelationManagers\PaymentsRelationManager` | — | — |
  | `Modules\Order\Filament\Admin\Resources\OrderResource\Widgets\OrderStats` | — | — |
  | `Modules\Order\Filament\Exports\OrderExporter` | — | — |
  | `Modules\Order\Filament\Imports\OrderImporter` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Order/Tests`

### `Tests/Filament/OrderResourceTest.php`

  - `it_resource_has_correct_model`

### `Tests/Unit/Filament/OrderResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_index_page_supports_search`
  - `it_create_page_validates_required_fields`
  - `it_order_has_customer_relationship`
  - `it_navigation_badge_methods_exist`

### `Tests/Unit/OrderImportExportTest.php`

  - `it_can_get_importer_columns`
  - `it_can_import_orders_with_valid_data`
  - `it_can_update_existing_orders_via_import`
  - `it_importer_provides_notification_body`
  - `it_importer_has_options_form_components`
  - `it_importer_has_model_attribute`
  - `it_validates_required_fields_in_import`
  - `it_can_filter_orders_before_export`
  - `it_provides_import_summary_notification`
  - `it_exports_shipping_information_correctly`

### `Tests/Unit/OrderManagerTest.php`

  - `it_creates_order_manager_without_app`
  - `it_sanitizes_order_data`
  - `it_handles_save_with_false_params`
  - `it_gets_order_by_id_as_string`
  - `it_gets_items_for_order_id`
  - `it_throws_exception_when_order_id_is_false`

## Service providers

  - `Modules\Order\Providers\OrderServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
