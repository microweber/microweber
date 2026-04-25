# `Coupons` module

> **Slug:** `coupons`
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

### `cart_coupon_logs` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `coupon_id` | `integer` | nullable |
  | `customer_email` | `string` | nullable |
  | `customer_id` | `string` | nullable |
  | `customer_ip` | `string` | nullable |
  | `coupon_code` | `string` | nullable |
  | `discount_type` | `string` | nullable |
  | `discount_value` | `decimal` | nullable, has-default |
  | `cart_total` | `decimal` | nullable, has-default |
  | `discount_amount` | `decimal` | nullable, has-default |
  | `uses_count` | `integer` | nullable |
  | `use_date` | `dateTime` | nullable |
  | `timestamps` | `timestamps` | — |

### `cart_coupons` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `coupon_name` | `string` | nullable |
  | `coupon_code` | `string` | nullable |
  | `discount_type` | `string` | nullable |
  | `discount_value` | `decimal` | nullable, has-default |
  | `total_amount` | `integer` | nullable |
  | `uses_per_coupon` | `integer` | nullable |
  | `uses_per_customer` | `integer` | nullable |
  | `is_active` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |
  | `id` | `id` | — |
  | `coupon_name` | `string` | nullable |
  | `coupon_code` | `string` | unique |
  | `discount_type` | `string` | nullable |
  | `discount_value` | `decimal` | — |
  | `total_amount` | `decimal` | nullable |
  | `uses_per_coupon` | `integer` | nullable |
  | `uses_per_customer` | `integer` | nullable |
  | `is_active` | `boolean` | has-default |
  | `timestamps` | `timestamps` | — |
  | `is_stackable` | `boolean` | has-default |
  | `customer_group_ids` | `text` | nullable |
  | `category_ids` | `text` | nullable |
  | `excluded_product_ids` | `text` | nullable |
  | `first_time_only` | `boolean` | has-default |
  | `auto_apply` | `boolean` | has-default |
  | `free_shipping` | `boolean` | has-default |
  | `max_discount_amount` | `decimal` | nullable |
  | `times_used` | `unsignedInteger` | has-default |
  | `total_discount_given` | `decimal` | has-default |
  | `description` | `text` | nullable |
  | `auto_apply` | `index` | — |
  | `(unnamed)` | `dropColumn` | — |
  | `cart_coupons_is_active_is_stackable_index` | `dropIndex` | — |
  | `cart_coupons_auto_apply_index` | `dropIndex` | — |
  | `bogo_enabled` | `boolean` | has-default |
  | `bogo_buy_quantity` | `unsignedInteger` | nullable |
  | `bogo_get_quantity` | `unsignedInteger` | nullable |
  | `bogo_discount_percent` | `decimal` | nullable |
  | `bogo_apply_to` | `string` | nullable |
  | `tiered_enabled` | `boolean` | has-default |
  | `tiered_rules` | `json` | nullable |
  | `conditional_rules` | `json` | nullable |
  | `max_discount_per_order` | `decimal` | nullable |
  | `min_items_count` | `unsignedInteger` | nullable |
  | `max_items_count` | `unsignedInteger` | nullable |
  | `discount_shipping` | `boolean` | has-default |
  | `bogo_enabled` | `index` | — |
  | `tiered_enabled` | `index` | — |
  | `(unnamed)` | `dropColumn` | — |
  | `cart_coupons_bogo_enabled_index` | `dropIndex` | — |
  | `cart_coupons_tiered_enabled_index` | `dropIndex` | — |
  | `valid_from` | `dateTime` | nullable |
  | `valid_to` | `dateTime` | nullable |
  | `(unnamed)` | `dropColumn` | — |
  | `product_ids` | `text` | nullable |
  | `product_ids` | `dropColumn` | — |

### `cart_orders` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `coupon_code` | `string` | nullable |
  | `discount_type` | `string` | nullable |
  | `discount_value` | `decimal` | nullable |

### `coupon_rule_usage` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `coupon_id` | `unsignedInteger` | — |
  | `coupon_id` | `foreign` | — |
  | `rule_type` | `string` | — |
  | `rule_context` | `json` | — |
  | `discount_amount` | `decimal` | has-default |
  | `created_at` | `timestamp` | — |

## Models

### `Modules\Coupons\Models\CartCoupon`

Source: `Models/CartCoupon.php`. Table: `cart_coupons`. 

### `Modules\Coupons\Models\CartCouponLog`

Source: `Models/CartCouponLog.php`. Table: `cart_coupon_logs`. 

**Fillable:** `coupon_code`, `coupon_id`, `discount_type`, `discount_value`, `customer_email`, `customer_ip`, `cart_total`, `discount_amount`

### `Modules\Coupons\Models\Coupon`

Source: `Models/Coupon.php`. Table: `cart_coupons`. 

**Fillable:** `coupon_name`, `coupon_code`, `description`, `discount_type`, `discount_value`, `max_discount_amount`, `total_amount`, `uses_per_coupon`, `uses_per_customer`, `is_active`, `is_stackable`, `first_time_only`, `auto_apply`, `free_shipping`, `valid_from`, `valid_to`, `product_ids`, `excluded_product_ids`, `category_ids`, `customer_group_ids`, `times_used`, `total_discount_given`, `bogo_enabled`, `bogo_buy_quantity`, `bogo_get_quantity`, `bogo_discount_percent`, `bogo_apply_to`, `tiered_enabled`, `tiered_rules`, `conditional_rules`, `max_discount_per_order`, `min_items_count`, `max_items_count`, `discount_shipping`

**Casts:**

  - `is_active` → `boolean`
  - `discount_value` → `decimal:2`
  - `total_amount` → `decimal:2`
  - `uses_per_coupon` → `integer`
  - `uses_per_customer` → `integer`
  - `valid_from` → `datetime`
  - `valid_to` → `datetime`
  - `bogo_enabled` → `boolean`
  - `bogo_buy_quantity` → `integer`
  - `bogo_get_quantity` → `integer`
  - `bogo_discount_percent` → `decimal:2`
  - `tiered_enabled` → `boolean`
  - `tiered_rules` → `array`
  - `conditional_rules` → `array`
  - `max_discount_per_order` → `decimal:2`
  - `min_items_count` → `integer`
  - `max_items_count` → `integer`
  - `discount_shipping` → `boolean`

### `Modules\Coupons\Models\CouponLog`

Source: `Models/CouponLog.php`. Table: `cart_coupon_logs`. 

**Fillable:** `coupon_id`, `coupon_code`, `customer_email`, `customer_ip`, `uses_count`, `discount_type`, `discount_amount`, `discount_value`, `cart_total`, `use_date`

**Casts:**

  - `use_date` → `datetime`
  - `uses_count` → `integer`

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `CouponsApiController::index` |
  | `GET` | `/{coupon}` | `CouponsApiController::show` |
  | `POST` | `/` | `CouponsApiController::store` |
  | `PUT` | `/{coupon}` | `CouponsApiController::update` |
  | `PATCH` | `/{coupon}` | `CouponsApiController::update` |
  | `DELETE` | `/{coupon}` | `CouponsApiController::destroy` |

## Controllers

### `Modules\Coupons\Http\Controllers\Api\CouponsApiController`

Source: `Http/Controllers/Api/CouponsApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(Request $request, int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

## Service classes

### `Modules\Coupons\Services\CouponService`

Source: `Services/CouponService.php`.

  - `generateCouponCode(): string`
  - `generateUniqueCouponCode(?string $prefix = null, int $length = 8): string`
  - `getCouponSession(): array`
  - `getStackedCoupons(): array`
  - `clearCouponSession(): void`
  - `canApplyCoupon(string $code, float $cartTotal, array $context = []): array`
  - `applyCoupon(string $code, float $cartTotal, ?string $customerEmail = null, ?string $customerIp = null, array $context = []): array`
  - `calculateTotalDiscount(float $cartTotal): float`
  - `getAutoApplyCoupons(float $cartTotal, array $context = []): array`
  - `consumeCoupon(string $code, string $customerEmail, string $customerIp): void`
  - `getAppliedCoupon(): ?array`
  - `getAppliedDiscount(float $cartTotal): float`
  - `getCouponStats(string $code): ?array`

## Events

  - `Modules\Coupons\Listeners\OrderWasCreatedCouponCodeLogger`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Coupons\Filament\Resources\CouponResource` | Shop Settings | — |
  | `Modules\Coupons\Filament\Resources\CouponResource\Pages\CreateCoupon` | — | — |
  | `Modules\Coupons\Filament\Resources\CouponResource\Pages\EditCoupon` | — | — |
  | `Modules\Coupons\Filament\Resources\CouponResource\Pages\ListCoupons` | — | — |
  | `Modules\Coupons\Filament\Resources\CouponResource\RelationManagers\LogsRelationManager` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Coupons/Tests`

### `Tests/Filament/CouponResourceTest.php`

  - `it_resource_has_correct_model`

### `Tests/Unit/CouponServiceTest.php`

  - `it_session_management`

### `Tests/Unit/Filament/CouponResourceTest.php`

  - `it_index_page_shows_all_records`

## Service providers

  - `Modules\Coupons\Providers\CouponOrderEventServiceProvider`
  - `Modules\Coupons\Providers\CouponsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
