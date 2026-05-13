# Shop Module — Examples

## Recipe 1: Add a product to the cart programmatically (e.g. from a "Buy now" button handler)

```php
$shop = app('shop_manager');

$result = $shop->add_to_cart([
    'content_id' => $productId,
    'qty' => 1,
    'options' => [
        'size' => 'M',
        'color' => 'red',
    ],
]);

if ($result === false) {
    return back()->withErrors(['cart' => 'Could not add product — out of stock or variant unavailable']);
}

return redirect('/cart');
```

## Recipe 2: Listen for `order.created` and send a Slack alert

```php
// In a ServiceProvider boot()
\Event::listen('order.created', function ($event) {
    $order = $event->order ?? $event[0] ?? null;
    if (! $order) return;

    \Illuminate\Support\Facades\Http::post(config('services.slack.webhook'), [
        'text' => sprintf(
            "💰 New order #%s — %s",
            $order->order_reference_id,
            app('shop_manager')->currency_format($order->amount)
        ),
    ]);
});
```

## Recipe 3: Multi-currency display via geo-detection

```php
// In a middleware
public function handle($request, $next)
{
    if (! session('shop_currency')) {
        $country = $request->header('Cf-Ipcountry')  // Cloudflare geo header
                ?? $request->header('X-Country-Code');

        $session_currency = match($country) {
            'GB' => 'GBP',
            'EU', 'DE', 'FR', 'IT', 'ES' => 'EUR',
            'JP' => 'JPY',
            'CA' => 'CAD',
            default => null,
        };

        if ($session_currency) {
            session(['shop_currency' => $session_currency]);
        }
    }

    return $next($request);
}
```

The Shop manager's `get_default_currency()` reads from session first, falling back to the configured default.

## Recipe 4: Custom storefront filter (e.g. "On sale this week")

```php
namespace App\Livewire;

use Modules\Shop\Livewire\ShopComponent;

class CustomShopComponent extends ShopComponent
{
    public bool $weeklySaleOnly = false;

    public function updatedWeeklySaleOnly(): void
    {
        $this->resetPage();
    }

    public function getProductsQueryProperty()
    {
        $query = parent::getProductsQueryProperty();

        if ($this->weeklySaleOnly) {
            $query->whereHas('contentData', function ($q) {
                $q->where('field_name', 'special_price_start')
                  ->where('field_value', '>=', now()->subWeek());
            });
        }

        return $query;
    }
}
```

Register the custom component in your AppServiceProvider's `Livewire::component('module-shop', CustomShopComponent::class)`.

## Recipe 5: Display the cart's current total in the header

```html
<!-- In a Blade template (e.g. resources/views/partials/header.blade.php) -->
<a href="/cart" class="cart-link">
    Cart
    <span class="badge">{{ app('cart_manager')->item_count() }}</span>
    <span class="total">{!! app('shop_manager')->cart_sum(false) !!}</span>
</a>
```

The `cart_sum(false)` returns a formatted string; pair with `cart_manager->item_count()` for the badge.

## Recipe 6: Force a specific shipping method on a backend-only order

```php
$orderId = $shop->checkout([
    'email' => 'auto-import@example.com',
    'shipping_method' => 'free-pickup',  // bypass user-selected method
    'payment_method' => 'invoice-net30',
    'billing_address' => [...],
    'shipping_address' => [...],
    'items' => [
        ['content_id' => 42, 'qty' => 1],
        ['content_id' => 43, 'qty' => 2],
    ],
    'is_backend_order' => 1,  // marker so other modules can skip user-facing events
]);
```

The Shipping module's "free-pickup" method must exist; if not, `checkout()` returns false.

## Recipe 7: Bulk price update (e.g. global +5%)

```php
use Modules\Product\Models\Product;

Product::active()->chunkById(50, function ($products) {
    foreach ($products as $product) {
        $oldPrice = (float) $product->getContentDataByFieldName('price');
        if ($oldPrice <= 0) continue;

        $newPrice = round($oldPrice * 1.05, 2);
        $product->setContentDataByFieldName('price', (string) $newPrice);
    }
});

// Flush the price cache
\Cache::tags(['shop', 'products'])->flush();
```

Run in a queued job during off-peak hours for large catalogs.

## Recipe 8: Custom event-driven invoice numbering

```php
\Event::listen('order.paid', function ($event) {
    $order = $event->order ?? $event[0];

    $invoice = \Modules\Invoice\Models\Invoice::create([
        'order_id' => $order->id,
        'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
        'amount' => $order->amount,
        'issued_at' => now(),
    ]);
});
```

The Invoice module ships its own auto-numbering; this recipe is for projects that need a custom format.

## Recipe 9: Out-of-stock notification subscriber

```php
// Frontend Blade — let users subscribe to "notify when back in stock"
<form wire:submit="subscribe">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="email" name="email" required>
    <button>Notify me when back in stock</button>
</form>

// Backend handler (custom controller method)
public function subscribe(Request $request)
{
    \DB::table('out_of_stock_subscribers')->updateOrInsert(
        ['product_id' => $request->product_id, 'email' => $request->email],
        ['created_at' => now()]
    );
}

// Listener: fire emails when stock returns
\Event::listen('product.stock_replenished', function ($event) {
    $subscribers = \DB::table('out_of_stock_subscribers')
        ->where('product_id', $event->productId)
        ->get();

    foreach ($subscribers as $sub) {
        \Mail::to($sub->email)->queue(new \App\Mail\BackInStock($event->product));
    }

    \DB::table('out_of_stock_subscribers')->where('product_id', $event->productId)->delete();
});
```

`product.stock_replenished` is a custom event — fire it from your stock-adjustment workflow.

## Recipe 10: Disable shop on a specific page

If you want a specific Page row to NOT render the shop's category-filter context (e.g. a Landing page promoting one product):

```php
\Modules\Page\Models\Page::find($pageId)->update(['is_shop' => 0]);

// In the landing page's content_body, render a single product card directly
<module type="product" id="42" />
```

The Page-level `is_shop=1` flag controls whether `ShopComponent::getMainPageId()` resolves to this page. Setting it to 0 means the storefront grid won't render here.
