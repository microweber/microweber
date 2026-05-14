# Cart Module — API Reference

Complete reference for every model, service, helper, event, listener, exception, and HTTP endpoint owned by the Cart module.

---

## Models

### Cart

`Modules\Cart\Models\Cart` — extends `Illuminate\Database\Eloquent\Model`.

**Table:** `cart`.

**Each row is ONE line item.** There is no separate cart-container table. The "cart" is the collection of rows with the same `session_id` and `order_completed = 0`.

**Guarded:** `id`, `session_id`, `user_id`, `amount`, `is_paid`, `confirmed_at`, `created_at`, `updated_at`. Mass-assigning these is rejected — they're set by the service layer only.

**Casts:** `custom_fields_data` → array.

**Constants:**

```php
public const MAX_ITEMS_PER_SESSION = 500;
```

Hard cap on rows per session (DoS protection).

**Static query helpers:**

| Method | Returns | Notes |
|---|---|---|
| `Cart::queryCartItems(string $sessionId) : array` | Active items as plain arrays | Caps at `MAX_ITEMS_PER_SESSION` |
| `Cart::queryCartAmountForSession(string $sessionId) : float` | `SUM(qty * price)` | SQL-side aggregation |
| `Cart::queryCartItemsCountForSession(string $sessionId) : int` | `SUM(qty)` | SQL-side aggregation |
| `Cart::queryCartAmount(array $cartItems) : float` | In-memory fallback sum | When you already have items |
| `Cart::queryCartItemsCount(array $cartItems) : int` | In-memory fallback count | Same |

**Relations:**

- `order() : HasOne` → `Modules\Order\Models\Order`
- `products() : HasMany` → `Modules\Product\Models\Product` (via `rel_id`)

**Filter:** `Cart::modelFilter()` returns `Modules\Cart\Models\ModelFilters\CartFilter::class`. Enables `Cart::filter([...])` with title/url/keyword traits.

---

### UserCart

`Modules\Cart\Models\UserCart` — extends `Cart`.

Applies the `UserCartScope` globally, which adds `where('session_id', session()->getId())->where('is_completed', 0)`. Use this when you specifically want "the current user's active cart" without writing the where clause every time.

```php
$myActiveItems = UserCart::all();   // already scoped
```

---

### `cart` table schema (key columns)

| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `session_id` | string | The de-facto cart identifier |
| `user_id` | int nullable | NULL for guest carts |
| `order_id` | string nullable | Set when cart → order conversion happens |
| `order_completed` | int | 0 = active, 1 = converted |
| `rel_id` | int | Product / content id |
| `rel_type` | string | Polymorphic type, usually `Modules\Content\Models\Content` |
| `title` | longText | Snapshot at insert time |
| `qty` | int | |
| `price` | float | **Server-canonical** unit price |
| `currency` | string | |
| `custom_fields_data` | longText | Base64-serialised variant choices |
| `custom_fields_json` | longText | JSON variant choices (same data, modern format) |
| `item_image`, `link`, `description` | string | Snapshot fields |
| `other_info` | longText | Free-form metadata |
| `skip_promo_code` | string | "1" to exclude from coupons |
| `is_active`, `is_paid` | string/bool | Lifecycle flags |
| `created_at`, `updated_at`, `deleted_at` | datetime | |

**Indexes:**

- single: `session_id`, `order_id`, `rel_id`
- compound: `(session_id, order_completed)`, `(session_id, is_active)`, `(rel_type, rel_id)`

---

## Services

### CartService

`Modules\Cart\Services\CartService` (singleton; container key `cart_service`).

Owns add/remove/update operations. This is where the business rules live.

```php
public function getCart(array|string $params) : array;
public function getByOrderId(int $orderId) : array;
public function updateCart(array $data) : array;     // add or upsert
public function removeItem(array|int $data) : array; // emits RemoveFromCartEvent
public function updateItemQty(array $data) : array;  // qty=0 ↔ remove
public function emptyCart() : array;
public function deleteCart(array|string $params) : void;
public function recoverCart(int|false $orderId) : void;
public function isProductInStock(int $contentId) : bool;
public function getCartItemImage(int $cartItemId) : string|false;
```

`updateCart()` is where canonical pricing + stock check + inventory reservation happen. Don't bypass it.

---

### CartTotalsService

`Modules\Cart\Services\CartTotalsService` (container key `cart_totals_service`).

Computes everything. Stores nothing.

```php
public function totals(string $return = 'all', array $location = []) : array;
public function total() : float;
public function sum(bool $returnAmount = true) : float|int;   // true → amount, false → count
public function getTax(array $location = []) : float;
public function getTaxBreakdown(array $location = []) : array; // per-tax-rate
public function getDiscount() : float|false;
public function getDiscountType() : string|false;             // 'percentage' | 'fixed_amount'
public function getDiscountText() : string;
```

`totals('all', $location)` is the canonical "give me everything" call:

```php
[
    'sub_total'        => 49.98,
    'tax_amount'       => 5.00,
    'tax_breakdown'    => [
        ['rate_name' => 'VAT', 'rate' => 20, 'amount' => 5.00],
    ],
    'shipping_cost'    => 4.99,
    'discount_amount'  => 5.00,
    'discount_type'    => 'percentage',
    'discount_text'    => '10% off',
    'total'            => 54.97,
    'currency'         => 'USD',
    'stock_warnings'   => [],
]
```

---

### CartCouponService

`Modules\Cart\Services\CartCouponService` (container key `cart_coupon_service`).

Stateless w.r.t. the database — operates against the session for the active coupon. Validates against the Coupons module.

```php
public function applyCoupon(
    string $couponCode,
    ?string $customerEmail = null,
    ?string $customerIp = null,
    array $context = [],          // auto-built if empty
) : array;

public function isCouponValid(string $couponCode) : bool;
public function getCouponDataFromSession() : array|false;
public function clearCouponSession() : void;

public function getDiscountValue() : float|false;
public function getDiscountType() : string|false;
public function getDiscountText() : string;

public function consumeCoupon(string $couponCode, string $customerEmail, string $customerIp) : void;
public function resolveCustomerEmail() : ?string;
public function buildCouponContext() : array;
```

`applyCoupon()` response shape:

```php
[
    'success'         => bool,
    'message'         => string,         // user-facing
    'code'            => string|null,    // applied code on success
    'discount_value'  => float|null,
    'discount_type'   => 'percentage'|'fixed_amount'|null,
]
```

---

### CartRepository

`Modules\Cart\Repositories\CartRepository` (container key `cart_repository`).

Caching layer over the `cart` table. Per-request memoization plus the standard cache-tag invalidation.

```php
public function getCartItems() : array;
public function getCartAmount() : float;
public function getCartItemsCount() : int;
public function flushCache() : void;
```

Internal use mostly — prefer the service or helper APIs. When you do raw `\DB` writes (in tests, migrations, or imports), call `flushCache()` afterwards.

---

### CartManager

`Modules\Cart\Repositories\CartManager` (container key `cart_manager`; implements `CartManagerContract`).

Backward-compat facade that delegates to the four services above. Mirrors the snake_case helper names so legacy templates keep working. New code should prefer the underlying services directly.

```php
public function get_cart(array|string $params) : array;
public function get(array $params) : array;
public function update_cart(array $data) : array;
public function remove_item($data) : array;
public function update_item_qty(array $data) : array;
public function empty_cart() : array;
public function delete_cart(array|string $params) : void;

public function sum(bool $return_amount = true) : float;
public function total() : float;
public function totals(string $return = 'all') : array;
public function get_tax() : float;

public function get_discount() : float|false;
public function get_discount_type() : string|false;
public function get_discount_text() : string;
public function get_discount_value() : float|false;

public function get_cart_item_image(int $cartItemId) : string|false;
public function is_product_in_stock(int $content_id) : bool;
public function recover_cart(int|false $ord_id) : void;
public function couponCodeCheckIfValid(string $coupon_code) : bool;
public function couponCodeGetDataFromSession() : array|false;
```

---

## Contracts

### CartManagerContract

`Modules\Cart\Contracts\CartManagerContract` — interface pinning the public surface for DI.

```php
interface CartManagerContract
{
    public function get_cart(array $params) : array;
    public function get(array $params) : array;
    public function update_cart(array $data) : array;
    public function remove_item($data) : array;
    public function update_item_qty(array $data) : array;
    public function empty_cart() : array;
    public function getTotal() : float;
}
```

Bound to `cart_manager` in `CartServiceProvider::register()`.

---

## Helper functions

Auto-loaded via `Modules/Cart/Support/helpers.php` (registered in `module.json`'s `files` array).

| Helper | Maps to | Notes |
|---|---|---|
| `get_cart($params = '')` | `CartManager::get_cart()` | |
| `update_cart($data)` | `CartManager::update_cart()` | Canonical add/update |
| `update_cart_item_qty($data)` | `CartManager::update_item_qty()` | |
| `remove_cart_item($data)` | `CartManager::remove_item()` | |
| `empty_cart()` | `CartManager::empty_cart()` | |
| `cart_sum($return_amount = true)` | `CartManager::sum()` | |
| `cart_get_items_count()` | `CartManager::sum(false)` | |
| `cart_total()` | `CartManager::total()` | |
| `cart_totals($return = 'all')` | `CartManager::totals()` | |
| `cart_get_tax()` | `CartManager::get_tax()` | |
| `cart_get_discount()` | `CartManager::get_discount()` | |
| `cart_get_discount_text()` | `CartManager::get_discount_text()` | |
| `mw_shop_recover_shopping_cart($order_id)` | `CartManager::recover_cart()` | |

---

## Events

All under `Modules\Cart\Events\`.

| Event | Payload | Fires when |
|---|---|---|
| `AddToCartEvent` | `$cartData : array` (full response) | A line is inserted or quantity-incremented |
| `RemoveFromCartEvent` | `$product : array` (the removed row's snapshot) | A line is removed |

Both are standard `Illuminate\Foundation\Events\Dispatchable` events. Subscribe via `Event::listen(...)` or via `EventServiceProvider::$listen`.

---

## Listeners

| Listener | Listens for | What it does |
|---|---|---|
| `UserLoginListener` | `Illuminate\Auth\Events\Login` | Rewrites `session_id` on all the just-logged-in user's active cart rows from the pre-login `old_sid` to the current authenticated session id, then flushes the cart cache. |

Registered in `CartEventServiceProvider::$listen`.

---

## Exceptions

All under `Modules\Cart\Exceptions\`.

### CartException (base)

```php
public static function databaseOperationFailed(
    string $operation,
    string $table,
    ?\Throwable $previous = null,
) : self;
```

### CartNotFoundException

```php
public static function byId(int $cartId) : self;
public static function itemNotFound(int|string $itemId) : self;
public static function bySession(string $sessionId) : self;
public static function byOrder(int $orderId) : self;
```

### InvalidCartItemException

```php
public static function missingRequiredField(string $field) : self;
public static function invalidQuantity(int $qty, string $reason = '') : self;
public static function productNotFound(int $productId) : self;
public static function outOfStock(int $productId, int $requestedQty, int $availableQty) : self;
```

Catch these in HTTP controllers / Livewire components and surface their `getMessage()` to the user.

---

## HTTP endpoints

### Modern RESTful API

`Modules\Cart\Http\Controllers\Api\CartApiController`

| Method | Path | Action | Body shape |
|---|---|---|---|
| GET | `/api/module/cart` | `index` | — |
| POST | `/api/module/cart` | `store` | `{content_id, qty, custom_fields?}` |
| PUT | `/api/module/cart/{id}` | `update` | `{qty}` |
| DELETE | `/api/module/cart/{id}` | `destroy` | — |
| DELETE | `/api/module/cart/empty` | `empty` | — |
| GET | `/api/module/cart/totals` | `totals` | — |
| POST | `/api/module/cart/coupon` | `applyCoupon` | `{code, email?}` |
| DELETE | `/api/module/cart/coupon` | `removeCoupon` | — |

All return `JsonResponse`. Session-scoped — pass the session cookie or a `X-Session-ID` header.

### Legacy POST endpoints

`Modules\Cart\Http\Controllers\CartApiController` (non-`Api\` namespace).

| Method | Path | Action |
|---|---|---|
| POST | `/api/update_cart` | `updateCart` |
| POST | `/api/remove_cart_item` | `removeCartItem` |
| POST | `/api/update_cart_item_qty` | `updateCartItemQty` |
| POST | `/api/cart_sum` | `sumCart` |
| POST | `/api/empty_cart` | `emptyCart` |

Kept for backward compatibility with older themes and JavaScript code. New integrations should use the RESTful API above.

### Storefront page

| Method | Path | Action |
|---|---|---|
| GET | `/cart` | `CartPageController@show` — renders the `modules.cart::page` view (which embeds the Livewire `CartItems` component) |

---

## Livewire / Microweber modules

### CartAddModule

`Modules\Cart\Microweber\CartAddModule` — extends `BaseModule`.

The drag-and-drop "Add to cart" block for Live Edit.

| Property | Value |
|---|---|
| Name | `"Add to cart"` |
| Module ID | `shop/cart_add` |
| Icon | `heroicon-o-shopping-cart` |
| Settings class | `CartAddModuleSettings` |
| Templates namespace | `modules.cart::templates` |

Renders one of `default.blade.php` / `bootstrap.blade.php` / `shop_inner.blade.php` / `mw_default.blade.php` based on the per-block settings.

### CartAddModuleSettings

`Modules\Cart\Filament\CartAddModuleSettings` — extends `LiveEditModuleSettings`. Two form tabs: Settings (button text) + Design (template picker).

---

## Traits

### HasCartItems

`Modules\Cart\Traits\HasCartItems`

Adds a `cart()` HasMany relation to any model. The boot hook cascades cart-item deletion when the parent model is deleted (useful for users — when you hard-delete a user, their abandoned cart rows go too).

```php
use HasCartItems;
```

Then `$user->cart` returns the user's cart rows.

---

## Scopes

### UserCartScope

`Modules\Cart\Scopes\UserCartScope`

Applied globally by the `UserCart` model. Adds `where('session_id', session()->getId())->where('is_completed', 0)` to every query.

---

## Filament resources

The Cart module currently registers **one** Filament page:

| Class | Purpose | Navigation |
|---|---|---|
| `CartAddModuleSettings` | Live Edit settings for the `shop/cart_add` block | (No top-nav entry; reached via Live Edit toolbar) |

There is no Cart-inventory Filament Resource — inspecting active carts in admin is typically done via the Order module's "Abandoned carts" report or via custom SQL.

---

## Configuration

The Cart module has no config file. The few tunables:

- `Cart::MAX_ITEMS_PER_SESSION = 500` — hard cap per session (DoS protection).
- `config('session.lifetime')` — affects guest cart longevity.
- Coupon rate limits — owned by the Coupons module (separate package; docs forthcoming).
- Inventory reservation window — owned by the [Product module's InventoryService](/modules/product/installation.md#reservation-window).
