# `Cart` module

> **Slug:** `cart`
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

### `cart` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `title` | `longText` | nullable |
  | `is_active` | `string` | nullable |
  | `rel_id` | `integer` | nullable |
  | `rel_type` | `string` | nullable |
  | `order_id` | `string` | nullable |
  | `qty` | `integer` | nullable |
  | `price` | `float` | nullable |
  | `currency` | `string` | nullable |
  | `order_completed` | `integer` | nullable, has-default |
  | `session_id` | `string` | nullable |
  | `other_info` | `longText` | nullable |
  | `skip_promo_code` | `string` | nullable |
  | `item_image` | `string` | nullable |
  | `link` | `string` | nullable |
  | `description` | `longText` | nullable |
  | `custom_fields_data` | `longText` | nullable |
  | `custom_fields_json` | `longText` | nullable |
  | `created_by` | `integer` | nullable |
  | `updated_at` | `dateTime` | nullable |
  | `created_at` | `dateTime` | nullable |
  | `deleted_at` | `dateTime` | nullable |
  | `session_id` | `index` | — |
  | `order_id` | `index` | — |
  | `rel_id` | `index` | — |
  | `(unnamed)` | `dropIndex` | — |

## Models

### `Modules\Cart\Models\Cart`

Source: `Models/Cart.php`. 

**Casts:**

  - `custom_fields_data` → `array`

### `Modules\Cart\Models\ModelFilters\CartFilter`

Source: `Models/ModelFilters/CartFilter.php`. 

### `Modules\Cart\Models\UserCart`

Source: `Models/UserCart.php`. 

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `CartApiController::index` |
  | `POST` | `/` | `CartApiController::store` |
  | `GET` | `/totals` | `CartApiController::totals` |
  | `DELETE` | `/empty` | `CartApiController::empty` |
  | `POST` | `/coupon` | `CartApiController::applyCoupon` |
  | `DELETE` | `/coupon` | `CartApiController::removeCoupon` |
  | `PUT` | `/{id}` | `CartApiController::update` |
  | `DELETE` | `/{id}` | `CartApiController::destroy` |

### `routes/web.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `POST` | `api/update_cart` | `CartApiController::updateCart` |
  | `POST` | `api/remove_cart_item` | `CartApiController::removeCartItem` |
  | `POST` | `api/update_cart_item_qty` | `CartApiController::updateCartItemQty` |
  | `POST` | `api/cart_sum` | `CartApiController::sumCart` |
  | `POST` | `api/empty_cart` | `CartApiController::emptyCart` |

## Controllers

### `Modules\Cart\Http\Controllers\Api\CartApiController`

Source: `Http/Controllers/Api/CartApiController.php`.

  - `index(Request $request): JsonResponse`
  - `store(Request $request): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(int $id): JsonResponse`
  - `empty(): JsonResponse`
  - `totals(): JsonResponse`
  - `applyCoupon(Request $request): JsonResponse`
  - `removeCoupon(): JsonResponse`

### `Modules\Cart\Http\Controllers\CartApiController`

Source: `Http/Controllers/CartApiController.php`.

  - `updateCart(Request $request)`
  - `emptyCart(Request $request)`
  - `removeCartItem(Request $request)`
  - `sumCart(Request $request)`
  - `updateCartItemQty(Request $request)`

## Service classes

### `Modules\Cart\Services\CartCouponService`

Source: `Services/CartCouponService.php`.

  - `getDiscountValue(): float|false`
  - `getDiscountType(): string|false`
  - `getDiscountText(): string`
  - `isCouponValid(string $couponCode): bool`
  - `getCouponDataFromSession(): array|false`
  - `clearCouponSession(): void`
  - `applyCoupon(string $couponCode, ?string $customerEmail = null, ?string $customerIp = null): array`
  - `consumeCoupon(string $couponCode, string $customerEmail, string $customerIp): void`

### `Modules\Cart\Services\CartService`

Source: `Services/CartService.php`.

  - `getCart($params = []): array`
  - `getByOrderId(int $orderId): array`
  - `removeItem($data): array`
  - `updateItemQty(array $data): array`
  - `emptyCart(): array`
  - `deleteCart($params): void`
  - `updateCart(array $data): array`
  - `recoverCart($orderId = false): void`
  - `isProductInStock(int $contentId): bool`
  - `getCartItemImage(int $cartItemId)`

### `Modules\Cart\Services\CartTotalsService`

Source: `Services/CartTotalsService.php`.

  - `totals(string $return = 'all', array $location = []): array`
  - `total(): float`
  - `sum(bool $returnAmount = true): float|int`
  - `getTax(array $location = []): float`
  - `getTaxBreakdown(array $location = []): array`
  - `getDiscount(): float|false`
  - `getDiscountText(): string`
  - `getDiscountType(): string|false`
  - `getDiscountValue(): float|false`

## Events

  - `Modules\Cart\Events\AddToCartEvent`
  - `Modules\Cart\Events\RemoveFromCartEvent`
  - `Modules\Cart\Listeners\UserLoginListener`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Cart\Filament\CartAddModuleSettings` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Cart/Tests`

### `Tests/Unit/CartCouponServiceTest.php`

  - `it_returns_false_for_discount_type_when_no_coupon_in_session`
  - `it_returns_false_for_coupon_data_when_no_coupon_in_session`
  - `it_gets_discount_type_from_session`
  - `it_returns_discount_text_for_fixed_amount`
  - `it_clears_coupon_session`
  - `it_returns_percentage_discount_text_even_with_zero`
  - `it_returns_false_when_coupon_service_null`
  - `it_gets_discount_value_for_cart_above_minimum`
  - `it_applies_coupon_and_returns_error_for_invalid_coupon`

### `Tests/Unit/CartModelTest.php`

  - `it_products_relationship`

### `Tests/Unit/CartTotalsServiceTest.php`

  - `it_counts_cart_items`
  - `it_handles_multiple_products`
  - `it_returns_labels_for_total_components`
  - `it_returns_discount_text_when_no_coupon`
  - `it_returns_false_for_discount_type_when_no_coupon`
  - `it_handles_float_precision_in_calculations`

### `Tests/Unit/UserCartTest.php`

  - `it_user_cart_has_same_attributes_as_cart`

## Service providers

  - `Modules\Cart\Providers\CartEventServiceProvider`
  - `Modules\Cart\Providers\CartServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
