# Product Module — API Reference

Complete reference for every model, service, event, listener, command, and HTTP endpoint owned by the Product module.

---

## Models

### Product

`Modules\Product\Models\Product` — extends `Modules\Content\Models\Content`.

The canonical product class. Backed by the `content` table; the global `ProductScope` adds `where('content_type', 'product')` to every query.

**Inherited (Content):** `categories()`, `tags()`, `customFields()`, `contentData()`, `setContentData()`, `setCustomField()`, `getContentData()`, soft-delete behaviour, multi-language URL handling.

**Product-specific:**

| Property / accessor | Type | Notes |
|---|---|---|
| `price` | float | Resolved from the CustomField `type='price'` row. Read-only accessor. |
| `qty` | int | From `content_data.qty`. Read-only accessor. |
| `sku` | string | From `content_data.sku`. Read-only accessor. |
| `content_data` | array | All key/value rows from `content_data` for this product. |

**Method**

- `modelFilter() : string` — returns `\Modules\Product\Models\ModelFilters\ProductFilter::class`. Enables `Product::filter([...])` queries.

**Boot:**

- Applies `ProductScope` (the `content_type='product'` filter).
- Forces `content_type='product'` + `subtype='product'` on `creating`.

---

### ProductVariant

`Modules\Product\Models\ProductVariant` — extends `Product`.

Used internally to represent variant rows when they're stored as their own Content entries (rare; most installs use `ProductVariantCombination` instead). Sets `content_type='product_variant'` + `subtype='product_variant'`.

> Most code paths interact with `ProductVariantCombination` (the SKU table), not this class.

---

### ProductVariantAttribute

`Modules\Product\Models\ProductVariantAttribute` — `product_variant_attributes` table.

Project-wide attribute definition ("Size", "Color", "Material").

**Fillable:** `name`, `key`, `description`, `type`, `position`, `is_active`, `settings`.
**Casts:** `is_active` → bool, `position` → int, `settings` → json.
**Boots:** auto-generates `key` (slug) from `name`.

**Relations:**
- `values() : HasMany` → `ProductVariantAttributeValue` (ordered by position).
- `activeValues() : HasMany` → values filtered by `is_active=true`.

**Scopes:**
- `active()` — `where is_active = true`.
- `ordered()` — `orderBy position asc, name asc`.

**Methods:**
- `static findByKey(string $key) : ?ProductVariantAttribute`
- `isColorType() : bool` — true when `type === 'color'`.
- `getNameWithCountAttribute() : string` — e.g. `"Size (4 values)"`.

---

### ProductVariantAttributeValue

`Modules\Product\Models\ProductVariantAttributeValue` — `product_variant_attribute_values` table.

The actual values that belong to an attribute ("S", "M", "L" under Size).

**Fillable:** `attribute_id`, `value`, `key`, `description`, `color_code`, `image`, `position`, `is_active`, `metadata`.
**Casts:** `is_active` → bool, `position` → int, `metadata` → json.
**Traits:** `MaxPositionTrait` (auto-positions on create).

**Relations:**
- `attribute() : BelongsTo` → `ProductVariantAttribute`.
- `combinations() : BelongsToMany` → `ProductVariantCombination` via the pivot.

**Scopes:**
- `active()`, `ordered()`, `forAttribute(int $attributeId)`.

**Methods:**
- `static findByKeys(int $attributeId, array $keys) : Collection`
- `hasColor() : bool` — `color_code` is not null.
- `getDisplayValueAttribute() : string` — formatted for UI ("Red (#ff0000)" when color_code present).
- `getFormattedOptionAttribute() : string` — option-tag-friendly label.

---

### ProductVariantCombination

`Modules\Product\Models\ProductVariantCombination` — `product_variants_combinations` table.

**The actual purchasable SKU** for a specific product. This is what cart/checkout/order ultimately link to.

**Fillable:** `product_id`, `sku`, `barcode`, `price`, `compare_price`, `cost_price`, `quantity`, `quantity_type`, `track_quantity`, `allow_backorders`, `weight`, `image`, `is_default`, `is_active`, `metadata`.
**Casts:** prices + weight → `decimal:2`; `quantity` → int; booleans cast; `metadata` → json.

**Relations:**
- `product() : BelongsTo` → `Content` (the parent product row).
- `attributeValues() : BelongsToMany` → `ProductVariantAttributeValue` via `product_variant_combination_attributes`.

**Scopes:**
- `active()` — `is_active = true`.
- `default()` — `is_default = true`.
- `forProduct(int $productId)`.

**Methods:**
- `isInStock() : bool` — quantity > 0 or `allow_backorders = true`.
- `getAvailableQuantity() : int` — net of reservations.
- `getFinalPrice(?int $customerId = null) : float` — applies pricing rules.
- `getAttributesArray() : array` — `['size' => 'M', 'color' => 'Red']`.
- `matchesAttributes(array $attrs) : bool`
- `static findByAttributes(int $productId, array $attrs) : ?ProductVariantCombination`

**Boot:** enforces "one default per product" — saving with `is_default=true` clears the previous default.

---

### ProductPricingRule

`Modules\Product\Models\ProductPricingRule` — `product_pricing_rules` table.

**Constants:**

```php
const RULE_TYPE_BULK_QUANTITY      = 'bulk_quantity';
const RULE_TYPE_BULK_AMOUNT        = 'bulk_amount';
const RULE_TYPE_CUSTOMER_SPECIFIC  = 'customer_specific';
const RULE_TYPE_CUSTOMER_GROUP     = 'customer_group';
const RULE_TYPE_VARIANT_OVERRIDE   = 'variant_override';
const RULE_TYPE_BUNDLE_DISCOUNT    = 'bundle_discount';

const PRICE_TYPE_FIXED                 = 'fixed';
const PRICE_TYPE_PERCENTAGE_DISCOUNT   = 'percentage_discount';
const PRICE_TYPE_FIXED_DISCOUNT        = 'fixed_discount';
```

**Scopes:** `active()` (date-aware + `is_active=true` + below `max_usage_count`), `bulk()`, `customerSpecific()`, `byPriority()` (DESC).

**Methods:**

- `appliesToProduct(int $productId) : bool`
- `appliesToCategory(int $categoryId) : bool`
- `appliesToCustomer(?int $customerId, ?int $customerGroupId) : bool`
- `isCurrentlyValid() : bool` — within `valid_from`/`valid_to` window, under usage caps, `is_active=true`.
- `hasReachedLimit(?int $customerId = null) : bool` — checks `max_usage_count` + `max_usage_per_customer`.
- `calculatePrice(float $basePrice, int $quantity, float $totalAmount) : array` — returns `['price', 'discount', 'tier']`.
- `incrementUsage() : void` — `++usage_count`.
- `getTierForQuantity(int $quantity) : ?array` — returns the matching tier from `tiers` JSON.
- `canStackWith(ProductPricingRule $other) : bool` — checks `is_stackable` + `cannot_stack_with`.

---

### ProductCustomerPricing

`Modules\Product\Models\ProductCustomerPricing` — `product_customer_pricing` table.

Per-customer hard-coded prices. Highest precedence — beats all other rules.

**Fillable:** `product_id`, `user_id`, `price`, `compare_price`, `minimum_quantity`, `valid_from`, `valid_to`, `is_active`, `metadata`.

**Scopes:**
- `active()` — date-aware + `is_active=true`.
- `forCustomer(int $customerId)`
- `forProduct(int $productId)`
- `forQuantity(int $qty)` — `minimum_quantity <= qty`.

**Methods:**
- `isCurrentlyValid() : bool`
- `appliesToQuantity(int $qty) : bool`
- `getFormattedPriceAttribute() : string`
- `getFormattedComparePriceAttribute() : string`
- `getDiscountPercentageAttribute() : float`

---

### ProductInventoryMovement

`Modules\Product\Models\ProductInventoryMovement` — `product_inventory_movements` table.

Append-only audit log. Every change to `content_data.qty` or `product_variants_combinations.quantity` should produce one of these rows.

**Constants:**

```php
const TYPE_SALE         = 'sale';
const TYPE_RESTOCK      = 'restock';
const TYPE_ADJUSTMENT   = 'adjustment';
const TYPE_RESERVATION  = 'reservation';
const TYPE_RETURN       = 'return';
const TYPE_DAMAGED      = 'damaged';
const TYPE_LOST         = 'lost';
const TYPE_TRANSFER_IN  = 'transfer_in';
const TYPE_TRANSFER_OUT = 'transfer_out';
const TYPE_INITIAL      = 'initial';
```

**Fillable:** `product_id`, `variant_id`, `quantity_change`, `quantity_before`, `quantity_after`, `type`, `reference_type`, `reference_id`, `notes`, `user_id`, `metadata`.

**Relations:** `product()`, `variant()`, `user()`. Plus polymorphic `reference()` (e.g. links to `Order`).

**Scopes:** `ofType(string)`, `forProduct(int)`, `forVariant(int)`, `sales()`, `restocks()`, `inDateRange(Carbon $from, Carbon $to)`.

**Methods:** `isSale() : bool`, `isRestock() : bool`, `isAdjustment() : bool`.

---

### ProductInventoryAlert

`Modules\Product\Models\ProductInventoryAlert` — `product_inventory_alerts` table.

Created automatically by `InventoryService` when a movement brings stock below threshold.

**Constants:** `TYPE_LOW_STOCK`, `TYPE_OUT_OF_STOCK`, `TYPE_CRITICAL`.

**Scopes:** `unresolved()`, `resolved()`, `ofType(string)`, `lowStock()`, `outOfStock()`, `critical()`, `forProduct(int)`, `forVariant(int)`.

**Methods:**
- `resolve(int $userId, ?string $notes = null) : void` — sets `is_resolved=true`, `resolved_at=now()`.
- `isLowStock()`, `isOutOfStock()`, `isCritical() : bool`
- `getSeverityAttribute() : string` — `low|out|critical`.
- `getSeverityColorAttribute() : string` — `warning|danger|danger` (for Filament badges).

---

### ProductStockReservation

`Modules\Product\Models\ProductStockReservation` — `product_stock_reservations` table.

Holds stock while a customer's cart is active. Releases automatically after `expires_at`.

**Constants:** `TYPE_CART`, `TYPE_ORDER`, `TYPE_HOLD`, `TYPE_PREORDER`.

**Fillable:** `product_id`, `variant_id`, `quantity`, `reservation_type`, `session_id`, `user_id`, `order_id`, `expires_at`, `is_active`, `metadata`.

**Scopes:** `active()`, `expired()`, `ofType(string)`, `cart()`, `order()`, `forProduct(int)`, `forVariant(int)`, `forSession(string)`, `forUser(int)`.

**Methods:**
- `isExpired() : bool`
- `isValid() : bool` — `is_active && !isExpired()`.
- `release() : void` — sets `is_active=false`.
- `extend(int $minutes) : void` — pushes `expires_at`.
- `convertToCart(string $sessionId) : self` — promotes a hold to a cart reservation.
- `convertToOrder(int $orderId) : self` — promotes cart → order (called by Checkout).

---

### ProductMetaData *(legacy)*

`Modules\Product\Models\ProductMetaData` — `product_meta_data` table.

Kept for older installs. New code should write to `content_data` instead.

---

## Services

### InventoryService

`Modules\Product\Services\InventoryService` (binds to `Modules\Product\Contracts\InventoryServiceContract`).

```php
public const DEFAULT_RESERVATION_MINUTES = 30;
public const DEFAULT_LOW_STOCK_THRESHOLD = 10;
```

**Methods (signatures stable across 2.x):**

- `getStock(int $productId, ?int $variantId = null) : int`
- `getAvailableQuantity(int $productId, ?int $variantId = null) : int`
- `getReservedQuantity(int $productId, ?int $variantId = null) : int`
- `hasStock(int $productId, int $quantity, ?int $variantId = null) : bool`
- `adjust(int $productId, int $quantityChange, string $type, ?string $notes = null, ?int $userId = null, ?int $variantId = null) : ProductInventoryMovement` — atomic.
- `reserve(int $productId, int $quantity, string $sessionId, ?int $variantId = null, int $expiresInMinutes = self::DEFAULT_RESERVATION_MINUTES) : ProductStockReservation`
- `releaseReservation(int $reservationId) : bool`
- `confirmReservation(int $reservationId, int $orderId) : ProductStockReservation` — cart → order.
- `commitOrderReservation(int $orderId) : void` — order → `sale` movement.
- `cleanupExpiredReservations() : int` — invoked by the artisan command.
- `checkLowStockAndAlert(int $productId, ?int $variantId = null) : ?ProductInventoryAlert`

---

### ProductVariantService

`Modules\Product\Services\ProductVariantService`

- `createAttribute(array $data) : ProductVariantAttribute`
- `updateAttribute(int $attributeId, array $data) : ProductVariantAttribute`
- `createAttributeValue(int $attributeId, array $data) : ProductVariantAttributeValue`
- `updateAttributeValue(int $valueId, array $data) : ProductVariantAttributeValue`
- `syncAttributeValues(ProductVariantAttribute $attribute, array $values) : void`
- `generateVariantCombinations(Content $product, array $attributeIds) : Collection`
- `deleteCombinations(int $productId) : int`

---

### AdvancedPricingService

`Modules\Product\Services\AdvancedPricingService`

Cache TTL: 3600 seconds.

```php
public function calculatePrice(
    int $productId,
    int $quantity = 1,
    ?float $basePrice = null,
    ?int $customerId = null,
    ?int $customerGroupId = null,
) : array;
```

Returns:

```php
[
    'base_price'          => 29.99,
    'final_price'         => 24.99,
    'discount'            => 5.00,
    'discount_percentage' => 16.67,
    'rules_applied'       => ['Bulk 5+ Discount', 'Wholesale Group'],
]
```

Helpers:

- `getBasePrice(int $productId) : float`
- `flushCache() : void`

---

## Events

All under `Modules\Product\Events\`. Each event carries a public `$product` (or for delete/destroy events, the model state at the moment of the operation).

| Event | Fires when | Use for |
|---|---|---|
| `ProductIsCreating` | Before insert | Validation / data prep |
| `ProductWasCreated` | After insert | Webhooks, search indexing |
| `ProductIsUpdating` | Before update | Pre-save audit, blocking checks |
| `ProductWasUpdated` | After update | Cache invalidation, re-index |
| `ProductWasDeleted` | After soft delete | Hide from search, notify admins |
| `ProductWasDestroyed` | After hard delete | Cleanup external state |

These fire **after** the corresponding Content events, so subscribers can rely on the Content-level state being finalised.

---

## Listeners

| Listener | Listens for | What it does |
|---|---|---|
| `UpdateInventoryOnOrderPaid` | `Modules\Order\Events\OrderPaid` (or equivalent) | Walks the order's line items, calls `InventoryService::commitOrderReservation()`. |

Registered in `ProductServiceProvider::boot()`.

---

## Notifications

| Notification | Channels (default) | Trigger |
|---|---|---|
| `LowStockNotification` | mail, database | Stock drops below `low_stock_threshold` |
| `ProductOutOfStockNotification` | mail, database | Stock reaches 0 |

Recipients: users with `is_admin=1`. Override the recipient query in `InventoryService::notifyLowStock()` if you have a custom role model.

---

## Console commands

| Command | Class | Cadence (recommended) |
|---|---|---|
| `inventory:cleanup-reservations` | `Modules\Product\Console\CleanupExpiredStockReservations` | every 5 minutes |

---

## HTTP endpoints

### Admin / authenticated CRUD

| Method | Path | Controller | Notes |
|---|---|---|---|
| GET | `/api/product` | `ProductApiController@index` | List + filter |
| POST | `/api/product` | `ProductApiController@store` | Create |
| GET | `/api/product/{id}` | `ProductApiController@show` | One product |
| PUT/PATCH | `/api/product/{id}` | `ProductApiController@update` | Update |
| DELETE | `/api/product/{id}` | `ProductApiController@destroy` | Soft-delete |
| GET | `/api/product_variant` | `ProductVariantApiController@index` | Variants |
| POST | `/api/product_variant` | `ProductVariantApiController@store` | |
| GET | `/api/product_variant/parent/{id}/options` | (legacy) | Returns attributes + values for one product |
| POST | `/api/product_variant/parent/{id}/options` | (legacy) | Bulk-update attributes |
| POST | `/api/product_variant_save` | (legacy) | Bulk-save combinations |

All `/api/product*` admin endpoints require Sanctum auth + `is_admin` scope.

### Public / headless

| Method | Path | Controller | Notes |
|---|---|---|---|
| GET | `/api/module/products` | `ProductsApiController@index` | Public list (active only) |
| GET | `/api/module/products/{id}` | `ProductsApiController@show` | Public single |
| POST | `/api/module/products` | `ProductsApiController@store` | Token-auth required |
| PUT/DELETE | `/api/module/products/{id}` | `ProductsApiController@update/destroy` | Token-auth required |

### Frontend helpers

| Method | Path | Controller | Notes |
|---|---|---|---|
| GET | `/api/product/quick-view` | `ProductQuickViewController` | Returns modal-ready product card |
| GET | `/admin/product/export` | `ProductExportController` | CSV/Excel export of catalog |

Responses are serialised by `Modules\Product\Http\Resources\ProductResource` (JSON:API-style).

---

## Filament resources

| Resource | Model | Navigation |
|---|---|---|
| `ProductResource` | `Product` | Shop → Products |
| `ProductVariantAttributeResource` | `ProductVariantAttribute` | Shop Settings → Product Variant Attributes |
| `ProductInventoryResource` | `ProductInventoryMovement` | Shop Settings → Inventory Movement |
| `ProductPricingRuleResource` | `ProductPricingRule` | Shop Settings → Pricing Rules |

Plus a settings page `ProductsModuleSettings` for module-wide knobs and a `ProductVariantManager` Livewire component embedded in the product form's Variants tab.

---

## Traits, scopes, validators

| Class | Purpose |
|---|---|
| `Modules\Product\Traits\CustomFieldPriceTrait` | Syncs price/special_price between the product instance and `custom_fields` table. |
| `Modules\Product\Scopes\PriceScope` | Filters `custom_fields` to `type='price'` rows. |
| `Modules\Product\Scopes\SpecialPriceScope` | Filters `custom_fields` to `type='special_price'` rows. |
| `Modules\Product\Validators\PriceValidator` | Validates non-negative price input (null/0 allowed; <0 rejected). |
| `Modules\Product\FrontendFilter\ShopFilter` | Public-site filter engine; uses `PriceFilter` + `CustomFieldsTrait`. |

---

## Configuration

The module has no dedicated config file — settings live in:

- `Modules/Product/Services/InventoryService.php` — reservation window + default threshold constants.
- `content.low_stock_threshold` column — per-product threshold.
- `product_variants_combinations.low_stock_threshold` column — per-variant threshold.
- The options table under `option_group='shop'` — for cross-module shop settings like default currency.
