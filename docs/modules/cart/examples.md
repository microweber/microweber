# Cart Module — Examples

End-to-end recipes. Each one is self-contained and copy-pasteable.

---

## 1. Custom mini-cart widget (blade)

A reactive header widget that updates whenever the cart changes.

```blade
{{-- resources/views/partials/mini-cart.blade.php --}}
<div class="mini-cart" id="mini-cart">
    @php
        $count = cart_get_items_count();
        $total = cart_total();
    @endphp

    <a href="/cart" aria-label="View cart">
        <svg class="cart-icon" aria-hidden="true">…</svg>
        <span class="cart-count" data-count>{{ $count }}</span>
        <span class="cart-total" data-total>{{ currency_format($total) }}</span>
    </a>
</div>

<script>
document.addEventListener('cart-updated', async () => {
    const res = await fetch('/api/module/cart/totals');
    const data = await res.json();
    document.querySelector('[data-count]').textContent = data.cart_items_count;
    document.querySelector('[data-total]').textContent = data.total_formatted;
});
</script>
```

Then in any "Add to cart" handler:

```js
await fetch('/api/module/cart', { method: 'POST', body: JSON.stringify({content_id: 42, qty: 1}) });
document.dispatchEvent(new CustomEvent('cart-updated'));
```

---

## 2. Add-to-cart from a custom product card

```blade
<button
    type="button"
    class="btn-add-to-cart"
    wire:click="addToCart({{ $product->id }})"
    @if (!app('cart_service')->isProductInStock($product->id)) disabled @endif
>
    @if (app('cart_service')->isProductInStock($product->id))
        Add to cart
    @else
        Out of stock
    @endif
</button>
```

```php
// In your Livewire component
public function addToCart(int $productId): void
{
    $result = update_cart([
        'content_id' => $productId,
        'qty'        => 1,
    ]);

    if ($result['success']) {
        $this->dispatch('cart-updated');
        $this->dispatch('show-toast', message: 'Added to cart.');
    } else {
        $this->dispatch('show-toast', message: $result['message'], type: 'error');
    }
}
```

---

## 3. Apply a coupon from a Livewire checkout form

```php
namespace App\Livewire\Checkout;

use Livewire\Component;

class CouponForm extends Component
{
    public string $code = '';
    public ?string $message = null;

    public function apply(): void
    {
        $svc = app(\Modules\Cart\Services\CartCouponService::class);

        $result = $svc->applyCoupon(
            couponCode: trim($this->code),
            customerEmail: auth()->user()?->email,
            customerIp: request()->ip(),
        );

        if ($result['success']) {
            $this->message = 'Applied — you save '.currency_format($result['discount_value']);
            $this->dispatch('cart-updated');   // forces totals refresh
        } else {
            $this->message = $result['message'];
        }
    }

    public function remove(): void
    {
        app(\Modules\Cart\Services\CartCouponService::class)->clearCouponSession();
        $this->message = 'Coupon removed.';
        $this->dispatch('cart-updated');
    }
}
```

---

## 4. Abandoned-cart email (scheduled)

Send a "you forgot something" email 2 hours after the last cart activity.

```php
// app/Console/Commands/SendAbandonedCartEmails.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Cart\Models\Cart;

class SendAbandonedCartEmails extends Command
{
    protected $signature = 'cart:send-abandoned-emails';

    public function handle(): int
    {
        $cutoff = now()->subHours(2);

        $abandonedSessions = Cart::where('order_completed', 0)
            ->where('updated_at', '<', $cutoff)
            ->whereNotNull('user_id')
            ->whereDoesntHave('abandonmentEmail', fn ($q) => $q->where('sent_at', '>', now()->subDays(7)))
            ->distinct('session_id')
            ->pluck('session_id');

        foreach ($abandonedSessions as $sid) {
            $items = Cart::queryCartItems($sid);
            if (empty($items)) continue;

            $user = \App\Models\User::where(
                'id',
                Cart::where('session_id', $sid)->value('user_id')
            )->first();

            if (!$user?->email) continue;

            \Mail::to($user->email)->queue(
                new \App\Mail\AbandonedCart($user, $items, $sid)
            );

            $this->info("Sent abandoned-cart email to {$user->email} ({$sid})");
        }

        return self::SUCCESS;
    }
}
```

Schedule it:

```php
// app/Console/Kernel.php
$schedule->command('cart:send-abandoned-emails')->hourly();
```

The recovery link in the email should call `mw_shop_recover_shopping_cart(...)` on click — but you need an order_id for that. Alternative: just point the user back to `/cart` with a magic-login token.

---

## 5. Cross-sells in the cart page

Show "frequently bought together" suggestions based on what's in the cart.

```php
// In the CartItems Livewire component
public function getCrossSellsProperty(): \Illuminate\Database\Eloquent\Collection
{
    $items = get_cart();
    if (empty($items)) return collect();

    $productIds = array_column($items, 'rel_id');

    // Find categories of the current cart items
    $categoryIds = \DB::table('categories_items')
        ->whereIn('rel_id', $productIds)
        ->where('rel_type', 'content')
        ->pluck('parent_id')
        ->unique();

    // Get other products in the same categories
    return \Modules\Product\Models\Product::whereHas('categories', fn ($q) => $q->whereIn('parent_id', $categoryIds))
        ->whereNotIn('id', $productIds)
        ->where('is_active', 1)
        ->inRandomOrder()
        ->limit(4)
        ->get();
}
```

```blade
@if (count($this->crossSells))
    <section class="cross-sells">
        <h3>You might also like</h3>
        <div class="grid">
            @foreach ($this->crossSells as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>
@endif
```

---

## 6. Headless cart for a React/Vue storefront

```javascript
// composables/useCart.js
import { ref } from 'vue';

const items = ref([]);
const totals = ref({});

async function refresh() {
    const res = await fetch('/api/module/cart');
    const data = await res.json();
    items.value = data.items;
    totals.value = data.totals;
}

async function add(contentId, qty = 1, customFields = {}) {
    const res = await fetch('/api/module/cart', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
        body: JSON.stringify({ content_id: contentId, qty, custom_fields: customFields }),
    });
    const data = await res.json();
    if (!data.success) throw new Error(data.message);
    await refresh();
    return data;
}

async function updateQty(itemId, qty) {
    if (qty === 0) return remove(itemId);
    const res = await fetch(`/api/module/cart/${itemId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
        body: JSON.stringify({ qty }),
    });
    await refresh();
    return res.json();
}

async function remove(itemId) {
    await fetch(`/api/module/cart/${itemId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken() } });
    await refresh();
}

async function applyCoupon(code) {
    const res = await fetch('/api/module/cart/coupon', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
        body: JSON.stringify({ code }),
    });
    await refresh();
    return res.json();
}

export function useCart() {
    return { items, totals, refresh, add, updateQty, remove, applyCoupon };
}
```

---

## 7. Force-merge a user's cart across devices

Sometimes a user has items on their phone, logs in on desktop, and wants to see those items. The default `UserLoginListener` only merges the current device's pre-login session — for cross-device merging:

```php
\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
    $userId = $event->user->id;
    $currentSid = session()->getId();

    // Find all active cart rows belonging to this user from any device/session
    \DB::table('cart')
        ->where('user_id', $userId)
        ->where('order_completed', 0)
        ->where('session_id', '!=', $currentSid)
        ->update(['session_id' => $currentSid]);

    app('cart_repository')->flushCache();
});
```

Caveat: this assumes you've been writing `user_id` on cart rows when a user is authenticated. The default Cart flow does this for authenticated adds; guests don't have a `user_id` set.

---

## 8. Track add-to-cart in your analytics pixel

```php
\Event::listen(\Modules\Cart\Events\AddToCartEvent::class, function ($event) {
    $data = $event->cartData;

    // Google Analytics 4 server-side event
    \Http::async()->post('https://www.google-analytics.com/mp/collect', [
        'measurement_id'  => config('services.ga4.measurement_id'),
        'api_secret'      => config('services.ga4.api_secret'),
    ], [
        'client_id' => session()->getId(),
        'events' => [[
            'name' => 'add_to_cart',
            'params' => [
                'currency'  => $data['currency'] ?? 'USD',
                'value'     => $data['cart_amount'],
                'items'     => [[
                    'item_id'   => $data['rel_id'],
                    'item_name' => $data['title'],
                    'quantity'  => $data['qty'],
                    'price'     => $data['price'],
                ]],
            ],
        ]],
    ]);
});
```

For Facebook Pixel / Meta Conversions API, swap the URL + payload shape; the trigger is identical.

---

## 9. Cap the cart at a max total value (B2B / wholesale)

Some installs need a max-total cap (e.g. small business customers can't exceed $5,000 without phone approval).

```php
\Event::listen(\Modules\Cart\Events\AddToCartEvent::class, function ($event) {
    $maxTotal = (float) get_option('max_cart_total', 'shop') ?: 0;
    if ($maxTotal <= 0) return;

    $currentTotal = cart_total();
    if ($currentTotal <= $maxTotal) return;

    // Remove the just-added item
    $sessionId = session()->getId();
    \DB::table('cart')
        ->where('session_id', $sessionId)
        ->where('order_completed', 0)
        ->orderByDesc('id')
        ->limit(1)
        ->delete();

    app('cart_repository')->flushCache();

    session()->flash('cart_error', "Cart total cannot exceed ".currency_format($maxTotal).". The last item was removed. Please contact sales for larger orders.");
});
```

Pair with a UI affordance on the cart page that reads the flash message.

---

## 10. Restore a converted order into a new cart ("buy this again")

Add a button to the order-history page:

```blade
<a href="{{ route('cart.recover', $order->id) }}" class="btn">Buy these again</a>
```

```php
// routes/web.php
Route::get('/cart/recover/{order}', function (\Modules\Order\Models\Order $order) {
    abort_if($order->user_id !== auth()->id(), 403);
    mw_shop_recover_shopping_cart($order->id);
    return redirect('/cart')->with('flash', 'Items added to your cart.');
})->middleware('auth')->name('cart.recover');
```

`mw_shop_recover_shopping_cart()` re-resolves prices at today's value and re-checks stock, so out-of-stock items are silently skipped.

---

## 11. Custom price-validation rule (B2B minimum-order)

```php
\Event::listen(\Modules\Cart\Events\AddToCartEvent::class, function ($event) {
    // For a specific product category (e.g. wholesale-only), enforce minimum qty
    $wholesaleCategoryId = (int) get_option('wholesale_category_id', 'shop');
    if (!$wholesaleCategoryId) return;

    $cartItemId = $event->cartData['cart_item_id'];
    $cartRow = \Modules\Cart\Models\Cart::find($cartItemId);
    if (!$cartRow) return;

    $product = \Modules\Product\Models\Product::find($cartRow->rel_id);
    $isWholesale = $product->categories()->where('parent_id', $wholesaleCategoryId)->exists();
    if (!$isWholesale) return;

    $minimumQty = 12;   // case-pack minimum
    if ($cartRow->qty < $minimumQty) {
        update_cart_item_qty(['id' => $cartItemId, 'qty' => $minimumQty]);
        session()->flash('cart_notice', "Wholesale products require a minimum of $minimumQty units. Quantity adjusted.");
    }
});
```

---

## 12. Webhook on cart abandonment (send to CRM)

```php
\Event::listen(\Modules\Cart\Events\AddToCartEvent::class, function ($event) {
    $sessionId = session()->getId();

    // Debounce: only send if no AddToCart for this session has fired in 30 min
    $cacheKey = "abandonment_check:{$sessionId}";
    if (\Cache::has($cacheKey)) return;
    \Cache::put($cacheKey, true, now()->addMinutes(30));

    // Schedule the abandonment check for 2 hours from now
    \App\Jobs\CheckCartAbandonment::dispatch($sessionId)->delay(now()->addHours(2));
});
```

```php
// app/Jobs/CheckCartAbandonment.php
class CheckCartAbandonment implements ShouldQueue
{
    public function __construct(public string $sessionId) {}

    public function handle(): void
    {
        $items = \Modules\Cart\Models\Cart::queryCartItems($this->sessionId);
        if (empty($items)) return;          // they checked out
        $first = $items[0];
        if ($first['order_completed'] == 1) return;

        $userId = $first['user_id'];
        if (!$userId) return;

        $user = \App\Models\User::find($userId);
        if (!$user?->email) return;

        \Http::withToken(config('services.crm.token'))
            ->post(config('services.crm.url').'/abandonment', [
                'email'        => $user->email,
                'cart_amount'  => \Modules\Cart\Models\Cart::queryCartAmountForSession($this->sessionId),
                'items'        => $items,
                'cart_url'     => url('/cart'),
            ]);
    }
}
```

---

## Where to go next

- [Troubleshooting](./troubleshooting.md) — known issues + canonical routing.
- [Product module](/modules/product/) — variants, pricing, inventory.
- [Shop module](/modules/shop/) — orchestrator that puts cart on the page.
