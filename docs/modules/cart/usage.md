# Cart Module — Usage

Day-to-day patterns for working with the cart programmatically. For full method signatures see [API Reference](./api.md); for full recipes see [Examples](./examples.md).

---

## Adding items

The canonical way:

```php
$response = update_cart([
    'content_id' => 42,
    'qty'        => 2,
]);
```

`$response` is an array containing:

- `success` — bool
- `cart_items_count` — total qty in cart after the operation
- `cart_amount` — formatted subtotal
- `warnings` — any non-fatal issues (e.g. "requested qty exceeds stock, clamped to N")
- `cart_item_id` — id of the inserted/updated row

### With a variant

```php
update_cart([
    'content_id'    => 42,
    'qty'           => 1,
    'custom_fields' => [
        'size'  => 'M',
        'color' => 'Red',
    ],
]);
```

The variant must exist as a `ProductVariantCombination` row in the [Product module](/modules/product/api.md#productvariantcombination), otherwise `update_cart()` returns an error.

### With a custom option (e.g. engraving text)

```php
update_cart([
    'content_id'    => 42,
    'qty'           => 1,
    'custom_fields' => [
        'engraving' => 'Happy Birthday, Alex!',
    ],
]);
```

Same content, different custom_fields → a separate cart row. Don't expect `update_cart` to merge two engravings into qty=2.

---

## Reading the cart

### All items for the current session

```php
$items = get_cart();
```

Returns an array of associative arrays, one per line:

```php
[
    [
        'id'         => 1234,
        'rel_id'     => 42,
        'rel_type'   => 'Modules\\Content\\Models\\Content',
        'title'      => 'Premium Cotton T-Shirt',
        'qty'        => 2,
        'price'      => 19.99,
        'currency'   => 'USD',
        'session_id' => 'abc123',
        // ... plus custom_fields_data / custom_fields_json
    ],
    // ...
]
```

### Filtered

```php
$items = get_cart(['rel_id' => 42]);                  // only product 42
$items = get_cart(['session_id' => $somebodyElseSid]); // someone else's cart
```

### Counts + totals

```php
$count    = cart_get_items_count();   // SUM(qty)
$subtotal = cart_sum(true);           // SUM(qty * price), un-rounded
$total    = cart_total();             // subtotal + tax + shipping − discount
```

### Full breakdown

```php
$totals = cart_totals('all');

// $totals = [
//   'sub_total'        => 49.98,
//   'tax_amount'       => 5.00,
//   'shipping_cost'    => 4.99,
//   'discount_amount'  => 5.00,
//   'total'            => 54.97,
//   'currency'         => 'USD',
//   ...
// ]
```

For tax to compute correctly, pass the customer's location:

```php
$totals = app('cart_totals_service')->totals('all', [
    'country' => 'BG',
    'state'   => 'Sofia',
    'zip'     => '1000',
]);
```

Location data typically comes from the Checkout module's address form.

---

## Updating quantity

```php
update_cart_item_qty([
    'id'  => $cartItemId,
    'qty' => 5,
]);
```

Setting `qty` to 0 is the same as `remove_cart_item(['id' => $cartItemId])`.

If the requested quantity exceeds stock, the service clamps to available and surfaces a warning:

```php
$result = update_cart_item_qty(['id' => $cartItemId, 'qty' => 100]);
// $result['warnings'][] = "Only 12 units available; quantity adjusted."
```

---

## Removing items

```php
remove_cart_item(['id' => $cartItemId]);
```

Fires the `RemoveFromCartEvent`. Subscribers can hook this for "you forgot to checkout!" abandonment flows.

To empty the entire cart:

```php
empty_cart();
```

This DELETEs all rows where `session_id = current_session() AND order_completed = 0`. Past orders (with `order_completed=1`) are preserved.

---

## Coupons

### Apply

```php
$result = app('cart_coupon_service')->applyCoupon(
    couponCode: 'SUMMER25',
    customerEmail: auth()->user()?->email,
    customerIp: request()->ip(),
);

// $result = [
//   'success' => true,
//   'message' => 'Coupon applied',
//   'discount_value' => 12.50,
//   'discount_type'  => 'percentage',
// ]
```

The service:

1. Looks up the coupon in the Coupons module.
2. Validates against the current cart (cart items, product IDs, category IDs, user, customer group).
3. Enforces rate limits (per-IP + per-email).
4. Stores the validated coupon in the session.

Subsequent `cart_total()` calls automatically subtract the discount.

### Inspect the active coupon

```php
$coupon = app('cart_coupon_service')->getCouponDataFromSession();

if ($coupon) {
    echo $coupon['code'] . ': ' . $coupon['discount_value'];
}

// Or via helpers
$discount = cart_get_discount();         // float or false
$type     = app('cart_coupon_service')->getDiscountType();  // 'percentage' | 'fixed_amount'
$label    = cart_get_discount_text();    // formatted for display
```

### Check validity at checkout

```php
$valid = app('cart_coupon_service')->isCouponValid('SUMMER25');
```

### Remove

```php
app('cart_coupon_service')->clearCouponSession();
```

### Consume (called by Checkout, not you)

Coupon usage is decremented only when the cart converts to an order. Checkout calls:

```php
app('cart_coupon_service')->consumeCoupon($code, $email, $ip);
```

You shouldn't call this from your own code — let the Order/Checkout flow handle it.

---

## Persistence + guest → user cart merge

When a guest with items in their cart logs in, the `UserLoginListener` automatically:

1. Grabs the old guest session id from `session('old_sid')` (set before the auth flip).
2. Rewrites every cart row with that `session_id` to the new authenticated session id.
3. Flushes the cart cache.

Result: the user logs in and sees their pre-login selection waiting for them. No code change required on your side.

If you want to **manually** merge an authenticated user's older cart into the current one (e.g. after a session reset):

```php
\DB::table('cart')
    ->where('user_id', auth()->id())
    ->where('order_completed', 0)
    ->update(['session_id' => session()->getId()]);

app('cart_repository')->flushCache();
```

---

## Stock awareness

Cart inserts ask Product for stock first. The check is:

```php
$inStock = app('cart_service')->isProductInStock($contentId);

if (!$inStock) {
    // surface to UI
}
```

`isProductInStock()` delegates to Product's `InventoryService::hasStock($productId, $requestedQty, $variantId)`. The cart will refuse to insert qty above available stock unless the product's `allow_backorders=1` is set.

If stock has changed since the user's last interaction (someone else bought the last unit), the next `cart_totals()` call will surface the issue:

```php
$totals = cart_totals('all');

foreach ($totals['stock_warnings'] ?? [] as $warning) {
    // "Product 'Foo' is now out of stock and was removed from your cart."
}
```

---

## Recovering a converted (or abandoned) cart

Need to re-load a customer's past order into a new shopping session ("buy this again")?

```php
mw_shop_recover_shopping_cart($orderId);
```

This iterates the line items from `cart WHERE order_id = $orderId` and re-inserts them as fresh `order_completed=0` rows under the current session. Stock + price are re-resolved at the moment of recovery, so the customer pays today's price, not the historical one.

---

## Cart events

| Event | Fires when | Payload |
|---|---|---|
| `Modules\Cart\Events\AddToCartEvent` | A line is inserted or quantity-incremented | `$cartData` (full response array) |
| `Modules\Cart\Events\RemoveFromCartEvent` | A line is removed | `$product` (the removed row) |

Subscribe pattern:

```php
\Event::listen(\Modules\Cart\Events\AddToCartEvent::class, function ($event) {
    // ping a marketing pixel
    \Http::async()->post(config('services.pixel.url'), [
        'event'       => 'add_to_cart',
        'product_id'  => $event->cartData['rel_id'],
        'quantity'    => $event->cartData['qty'],
        'cart_amount' => $event->cartData['cart_amount'],
    ]);
});
```

For the abandoned-cart "you forgot!" flow, hook `RemoveFromCartEvent` plus a scheduled query that looks for `order_completed=0 AND updated_at < now() - INTERVAL 2 HOUR`.

---

## Mini-cart widget

The mini-cart that lives in your header is typically rendered from `get_cart()` + `cart_totals()`. Minimal blade:

```blade
<div class="mini-cart">
    @php
        $items = get_cart();
        $count = cart_get_items_count();
        $total = cart_total();
    @endphp
    <a href="/cart">
        <span class="cart-icon"></span>
        <span class="cart-count">{{ $count }}</span>
        <span class="cart-total">{{ currency_format($total) }}</span>
    </a>
</div>
```

For a Livewire-reactive version, wire it via the global `cart-updated` event:

```js
Livewire.on('cart-updated', () => {
    Livewire.dispatch('refresh-mini-cart');
});
```

The `cart-updated` event is broadcast by every Cart write path.

---

## REST API for headless storefronts

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/module/cart` | List items + totals |
| POST | `/api/module/cart` | Add an item (`{content_id, qty, custom_fields?}`) |
| PUT | `/api/module/cart/{id}` | Update qty (`{qty}`) |
| DELETE | `/api/module/cart/{id}` | Remove an item |
| DELETE | `/api/module/cart/empty` | Empty the cart |
| GET | `/api/module/cart/totals` | Totals only |
| POST | `/api/module/cart/coupon` | Apply coupon (`{code}`) |
| DELETE | `/api/module/cart/coupon` | Remove coupon |

All endpoints are session-scoped — pass the session cookie or a `X-Session-ID` header for headless clients.

See [API Reference](./api.md#http-endpoints) for full request/response shapes.

---

## Common patterns checklist

- ✅ Always use `update_cart()` / `update_cart_item_qty()` — never `\DB::insert/update` directly. The helpers do price-resolution, stock-reservation, cache-flush, and event-dispatch.
- ✅ Use `cart_totals('all')` once per request and pass the result around — don't call it multiple times per page (each call re-runs tax + coupon math).
- ✅ Pass the customer's location to `cart_totals()` for accurate tax.
- ✅ Treat `cart_total()` as authoritative for "what the customer pays" — don't manually re-add tax + shipping.
- ✅ Hook `AddToCartEvent` / `RemoveFromCartEvent` for analytics — don't poll the cart table.
- ❌ Don't trust the client's `price` field — Cart ignores it and resolves canonically. If you see "wrong price in cart", look at the [Product module's pricing rules](/modules/product/usage.md#pricing-rules), not at Cart.
- ❌ Don't bulk-`UPDATE cart` from raw SQL without flushing `cart_repository`'s cache.
- ❌ Don't treat the `Cart` model as a cart container — it's a line item. To clear the cart, call `empty_cart()`, not `Cart::delete()`.
