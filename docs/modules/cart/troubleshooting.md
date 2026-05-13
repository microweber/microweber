# Cart Module — Troubleshooting

## "Out of stock" but Product shows as in stock

**By far the most common Cart bug — and 90% of the time the fix lives in the [Product module](/modules/product/).**

The Cart asks Product for stock on every add. If Product says "no", Cart refuses. Walk the gates in order:

1. **Active reservations holding stock.** Check `\Modules\Product\Models\ProductStockReservation::active()->forProduct($id)->sum('quantity')`. If that plus on-hand equals total, expired reservations are still active.
2. **Cleanup task not scheduled.** `php artisan inventory:cleanup-reservations` should run every 5 minutes. Check `php artisan schedule:list`. See [Product installation](/modules/product/installation.md#schedule-the-inventory-cleanup-job).
3. **Variant out of stock, not parent.** If you're adding a variant, the parent product's stock is irrelevant — `ProductVariantCombination::quantity` is what matters.
4. **Backorders disabled.** If `allow_backorders=0` and quantity=0, Cart refuses even if Product's `physical_product=0`.

Route this bug to **Product first**. Only file against Cart if you've ruled out reservation expiry + the cleanup schedule.

---

## Cart items disappear after a few minutes (guest)

Session lifetime is too short. Default `config('session.lifetime')` is 120 minutes — fine for desktop but aggressive for mobile where the user's screen sleeps.

```php
// config/session.php
'lifetime' => 480,   // 8 hours
```

Or for "remember me" guest carts, switch the session driver to `database` and tune retention separately.

---

## Cart items disappear after login

The `UserLoginListener` should rewrite the `session_id` from the pre-login session to the post-login session. If items are vanishing on login:

1. **`old_sid` not set.** Microweber expects `session('old_sid')` to be the pre-login session id. If your auth flow uses `session()->regenerate()` without preserving `old_sid`, the listener has nothing to look up.
2. **Listener not registered.** Verify `Modules\Cart\Providers\CartEventServiceProvider::$listen` includes `Login → UserLoginListener::class`.
3. **`session_id` column type mismatch.** On a recent upgrade, the column was widened from VARCHAR(60) to VARCHAR(255). If you skipped a migration, longer session ids get truncated.

Quick repair if a user complains they "lost their cart":

```php
\DB::table('cart')
    ->where('user_id', $userId)
    ->where('order_completed', 0)
    ->update(['session_id' => session()->getId()]);

app('cart_repository')->flushCache();
```

---

## "Quantity adjusted" warning even though stock is plentiful

Two common causes:

1. **`max_quantity_per_order` set on the product.** Read from `content_data`: `$product->getContentData('max_quantity_per_order')`. The Cart caps qty at this value silently with a warning.
2. **`MAX_ITEMS_PER_SESSION` reached.** Hard cap of 500 lines per session. If the user has tried adding 501 different items, the 501st is rejected.

---

## Prices in cart differ from product page

Cart stores the **server-canonical** price resolved at insert time. The product page may have shown an out-of-date price if:

1. **A pricing rule expired between page load and add-to-cart.** Cart resolves the price at insert, so the user sees the post-expiry price.
2. **Cache was stale on the product page.** `AdvancedPricingService` caches resolved prices for 3600s. If a rule was just added/updated, the product page may show the old price.
3. **Customer-group pricing applied at cart but not on the product page.** Make sure your product card calls `AdvancedPricingService::calculatePrice(..., customerGroupId: ...)` with the same customer context Cart uses.

**Cart is always right** in this scenario — the issue is on the product page side. Fix by:

```bash
php artisan cache:clear
# Or programmatically:
app(\Modules\Product\Services\AdvancedPricingService::class)->flushCache();
```

---

## Cart totals don't include tax

`CartTotalsService::totals()` only computes tax when you pass the customer's location:

```php
$totals = app('cart_totals_service')->totals('all', [
    'country' => 'BG',
    'state'   => 'Sofia',
    'zip'     => '1000',
]);
```

If you're calling `cart_total()` (no-arg helper) on a page that doesn't know the customer's location yet (e.g. cart preview before checkout), tax is 0. This is correct behaviour — tax can't be calculated without an address.

To always estimate tax using a default location, configure it in the Tax module's settings and CartTotalsService will pick it up as the fallback.

---

## Coupon "applied" but discount = $0

Walk the coupon validation gates:

1. **Cart contents don't satisfy coupon constraints.** Coupons can require specific products / categories / minimum total — check the coupon's `applicable_product_ids`, `applicable_category_ids`, `min_total`.
2. **`skip_promo_code = "1"`** on one or more cart rows. The Cart respects this flag and excludes those items from coupon discounts. Set by the product's "Exclude from promo codes" option.
3. **Customer email / IP mismatch.** If the coupon has per-email or per-IP usage limits, the applied code may have been "consumed" already.
4. **Customer group not eligible.** Coupons can restrict to specific customer groups.

Inspect:

```php
$svc = app(\Modules\Cart\Services\CartCouponService::class);
$ctx = $svc->buildCouponContext();
$result = $svc->applyCoupon('CODE', resolveCustomerEmail(), request()->ip(), $ctx);
dump($result);   // 'message' will tell you why
```

---

## Coupon rate-limited (429-ish error)

`CartCouponService::applyCoupon()` rate-limits per IP + per email to prevent coupon brute-forcing. If a legitimate user hits the limit (typically by entering the wrong code 5+ times):

- Clear the rate limit cache: `php artisan cache:clear`.
- Or wait — defaults are conservative (5/minute typically).
- Or tune in the Coupons module's config.

---

## Cart double-charges after add-to-cart click

Most likely a UX bug — multiple AJAX calls firing for one click. Debounce the button:

```js
button.addEventListener('click', async (e) => {
    if (button.dataset.adding === '1') return;
    button.dataset.adding = '1';
    try {
        await fetch('/api/module/cart', {...});
    } finally {
        delete button.dataset.adding;
    }
});
```

If it's truly a server-side bug (two `cart` rows for the same content + same custom_fields), check that the upsert key in `CartService::updateCart()` covers your case. The dedupe key is `(session_id, rel_id, custom_fields_data)` — if two seemingly-identical adds produce different `custom_fields_data` (e.g. due to PHP serialisation key-ordering), they're treated as different items.

---

## `update_cart()` returns `success=false` with no message

Catch the underlying exception:

```php
try {
    $result = app('cart_service')->updateCart(['content_id' => $id, 'qty' => 1]);
} catch (\Modules\Cart\Exceptions\InvalidCartItemException $e) {
    \Log::error('Invalid cart item: '.$e->getMessage(), ['data' => $data]);
} catch (\Modules\Cart\Exceptions\CartException $e) {
    \Log::error('Cart DB failure: '.$e->getMessage());
}
```

The most common silent failures:

- `productNotFound` — `content_id` doesn't exist (or was soft-deleted).
- `invalidQuantity` — qty <= 0 (use `removeItem` instead).
- `outOfStock` — even after reservation cleanup.

---

## Cart cache returns stale data

Anything that bypasses `CartService` (raw `\DB::table('cart')->update(...)`, an admin SQL fix, etc.) requires manual cache invalidation:

```php
app('cart_repository')->flushCache();
\Cache::tags(['cart'])->flush();
```

In tests with `RefreshDatabase`, the cache survives across tests unless explicitly flushed in `setUp()`.

---

## "Class 'Modules\Cart\Models\Cart' not found"

`composer dump-autoload`. Microweber modules use PSR-4 and the autoload map can drift after a `git pull` that adds new files without re-running composer.

---

## REST API returns 419 (CSRF)

The `/api/module/cart/*` endpoints are session-scoped — they require either:

- A valid CSRF token (`X-CSRF-TOKEN` header), OR
- Bearer-token auth via Sanctum.

If your headless client uses a different cookie or no cookie at all, send the CSRF token from `csrf_token()` in your blade page and pass it on every fetch.

---

## Cart count in the header is wrong after AJAX add

You added an item via `fetch('/api/module/cart', ...)` but the header mini-cart still shows the old count. Two fixes:

1. **Dispatch a frontend event** in your add handler — `document.dispatchEvent(new CustomEvent('cart-updated'))` — and have the mini-cart listen for it (see [Examples §1](./examples.md#1-custom-mini-cart-widget-blade)).
2. **Don't compute the count server-rendered at page load**. Compute it client-side after every cart op.

---

## Empty `custom_fields_data` for items with variants

`update_cart()` accepts `custom_fields` as either:

- An array (preferred): `['size' => 'M', 'color' => 'Red']`
- A serialised string (legacy)
- A JSON string (also accepted)

If your custom_fields_data column is empty after an add-with-variant, you probably passed `custom_fields_data` (already-serialised) instead of `custom_fields` (the user-friendly array). Use the array form.

---

## REST POST returns 200 but cart is unchanged

Check the response body, not just the status code. `update_cart()` returns `success=false` for invalid input but the controller still returns 200 with the error message in the JSON body:

```json
{
  "success": false,
  "message": "Product is out of stock",
  "warnings": [...]
}
```

Always inspect `success` + `message` client-side.

---

## Where to file bugs

Use the [Shop module's "Where to file bugs" matrix](/modules/shop/troubleshooting.md#where-to-file-bugs) as the canonical reference. Cart-specific routing:

| Symptom | File against |
|---|---|
| "Out of stock" errors when stock exists | **Product module** (reservation expiry) first; **Cart** only if Product checks out clean |
| Variant matrix not resolving on add | **Product module** (`ProductVariantCombination::findByAttributes`) |
| Wrong price stored in cart | **Product module** (`AdvancedPricingService`) — Cart is just the messenger |
| Tax wrong on cart total | **Tax module** |
| Shipping cost wrong on cart total | **Shipping module** |
| Coupon validation logic / discount math | **Coupons module** |
| Coupon rate limit too aggressive / not aggressive enough | **Coupons module** |
| Cart items vanishing on login | **Cart module** (`UserLoginListener`) |
| Cart items vanishing for guests | session config in `config/session.php`, not a Cart bug |
| Cart not converting to order at checkout | **Checkout** or **Order module** |
| Order-history "buy again" not restoring | **Cart module** (`recoverCart`) |
| Mini-cart count wrong after add | Theme JS — the cart-updated event isn't being dispatched/listened to |
| 419 on `/api/module/cart` | CSRF token missing in your headless client |
| Cart Filament block not rendering | **Cart module** (`CartAddModule` / `CartAddModuleSettings`) |

For data-layer questions (cache, repository, model scopes), file against **Cart**. For frontend rendering of the cart page itself, the issue is usually theme-side (`Templates/<active>/cart.blade.php`) — check the template before filing against Cart.
