# Product Module — Examples

End-to-end recipes for common product scenarios. Each one is self-contained and copy-pasteable.

---

## 1. Simple physical product

A T-shirt, no variants, with a base price and a sale price.

```php
use Modules\Product\Models\Product;

$product = Product::create([
    'title'       => 'Logo T-Shirt',
    'url'         => 'logo-tshirt',
    'description' => 'Pre-shrunk cotton with our logo.',
    'is_active'   => 1,
]);

$product->setCustomField(['type' => 'price', 'name' => 'Price',         'value' => [29.99]]);
$product->setCustomField(['type' => 'price', 'name' => 'Special Price', 'value' => [19.99]]);

$product->setContentData([
    'qty'               => 100,
    'sku'               => 'TSHIRT-LOGO',
    'track_quantity'    => 1,
    'physical_product'  => 1,
    'weight'            => 0.2,
    'weight_type'       => 'kg',
    'width'             => 30,
    'height'            => 40,
    'depth'             => 1,
]);

$product->save();

// Attach to a category
$product->categories()->attach($apparelCategoryId);
```

---

## 2. Digital product

A downloadable PDF. No shipping, no physical dimensions.

```php
$ebook = Product::create([
    'title'       => 'Microweber Developer Handbook',
    'url'         => 'mw-dev-handbook',
    'description' => '180-page PDF guide.',
    'is_active'   => 1,
]);

$ebook->setCustomField(['type' => 'price', 'name' => 'Price', 'value' => [49.00]]);

$ebook->setContentData([
    'qty'              => 9999,
    'sku'              => 'EBOOK-MW-DEV',
    'track_quantity'   => 0,            // unlimited downloads
    'physical_product' => 0,
    'free_shipping'    => 1,
    'digital_file_url' => '/userfiles/downloads/mw-handbook.pdf',
]);

$ebook->save();
```

Hook the order-paid event to email the download link:

```php
\Event::listen(\Modules\Order\Events\OrderPaid::class, function ($event) {
    foreach ($event->order->items as $item) {
        $product = \Modules\Product\Models\Product::find($item->product_id);
        if ($product->getContentData('physical_product') == 0) {
            \Mail::to($event->order->email)->send(
                new \App\Mail\DigitalDownload($product, $event->order)
            );
        }
    }
});
```

---

## 3. Configurable product with full variants

T-shirts in 4 sizes × 3 colors = 12 SKUs, each with its own stock count.

```php
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductVariantAttribute;
use Modules\Product\Models\ProductVariantCombination;
use Modules\Product\Services\ProductVariantService;

$svc = app(ProductVariantService::class);

// 1. The parent product
$product = Product::create([
    'title' => 'Premium Cotton T-Shirt',
    'url'   => 'premium-cotton-tshirt',
    'is_active' => 1,
]);
$product->setCustomField(['type' => 'price', 'name' => 'Price', 'value' => [39.99]]);
$product->save();

// 2. Attributes (reuse if they already exist)
$size = ProductVariantAttribute::firstOrCreate(['key' => 'size'], ['name' => 'Size']);
$svc->syncAttributeValues($size, [
    ['value' => 'S'], ['value' => 'M'], ['value' => 'L'], ['value' => 'XL'],
]);

$color = ProductVariantAttribute::firstOrCreate(['key' => 'color'], ['name' => 'Color', 'type' => 'color']);
$svc->syncAttributeValues($color, [
    ['value' => 'Black', 'color_code' => '#000000'],
    ['value' => 'White', 'color_code' => '#FFFFFF'],
    ['value' => 'Navy',  'color_code' => '#001f3f'],
]);

// 3. Generate the 12 combinations
$svc->generateVariantCombinations($product, [$size->id, $color->id]);

// 4. Set per-variant stock + SKU
ProductVariantCombination::forProduct($product->id)->each(function ($combo, $i) {
    $attrs = $combo->getAttributesArray();
    $combo->update([
        'sku'       => sprintf('TSHIRT-%s-%s', $attrs['size'], strtoupper(substr($attrs['color'], 0, 3))),
        'quantity'  => 50,
        'is_active' => true,
    ]);
});

// 5. Mark a default for the frontend pre-selection
ProductVariantCombination::forProduct($product->id)
    ->where('sku', 'TSHIRT-M-BLA')
    ->update(['is_default' => true]);
```

---

## 4. Wholesale customer-group pricing

Customers in the "wholesale" group see all products at 25% off, with no expiry.

```php
use Modules\Product\Models\ProductPricingRule;

ProductPricingRule::create([
    'name'              => 'Wholesale - 25% off',
    'slug'              => 'wholesale-25',
    'rule_type'         => ProductPricingRule::RULE_TYPE_CUSTOMER_GROUP,
    'price_type'        => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
    'customer_group_ids'=> [$wholesaleGroupId],
    'tiers'             => [['min_qty' => 1, 'discount' => 25]],
    'priority'          => 200,
    'is_active'         => 1,
    'is_stackable'      => false,
    'is_public'         => 0,            // don't show to the public
]);
```

Then in the storefront, the rule applies automatically — `AdvancedPricingService::calculatePrice($product->id, $qty, customerId: auth()->id(), customerGroupId: $user->customer_group_id)` returns the wholesale price.

---

## 5. Bulk-quantity tiered discount

Buy more, save more — a classic tiered offer.

```php
ProductPricingRule::create([
    'name'         => 'Buy 10+ Save 15%',
    'slug'         => 'bulk-tier-15',
    'rule_type'    => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
    'price_type'   => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
    'product_ids'  => [$tshirtId],
    'tiers'        => [
        ['min_qty' => 5,  'max_qty' => 9,    'discount' => 5],
        ['min_qty' => 10, 'max_qty' => 49,   'discount' => 15],
        ['min_qty' => 50, 'max_qty' => null, 'discount' => 25],
    ],
    'priority'     => 100,
    'is_active'    => 1,
    'is_public'    => 1,
    'is_stackable' => true,
]);
```

The product page can show this as a "savings table" using:

```php
$rule = ProductPricingRule::where('slug', 'bulk-tier-15')->first();
$tiers = collect($rule->tiers);
// Render in blade — strikethrough next-tier prices to nudge upsell
```

---

## 6. One specific customer gets a hand-coded price

You negotiated a special deal with Acme Corp. They pay $12 for the $20 product. Everyone else pays $20.

```php
use Modules\Product\Models\ProductCustomerPricing;

ProductCustomerPricing::create([
    'product_id'       => $product->id,
    'user_id'          => $acmeUserId,
    'price'            => 12.00,
    'compare_price'    => 20.00,         // for "you save $8" display
    'minimum_quantity' => 1,
    'valid_from'       => now(),
    'valid_to'         => now()->addYear(),
    'is_active'        => 1,
]);
```

`AdvancedPricingService` evaluates this BEFORE pricing rules, so it always wins.

---

## 7. Restock notification flow

Admin needs to know when a product hits zero so they can reorder.

`InventoryService` already creates the alerts; just configure the channel:

```php
// Send to Slack instead of email
\Modules\Product\Notifications\ProductOutOfStockNotification::macro('toSlack', function ($notifiable) {
    return (new \Illuminate\Notifications\Messages\SlackMessage)
        ->error()
        ->content("🚨 OUT OF STOCK: {$this->product->title} (SKU: {$this->product->sku})")
        ->attachment(function ($attachment) {
            $attachment->title('View product', url("/admin/products/{$this->product->id}/edit"))
                ->fields([
                    'Last sale' => $this->product->latestMovement?->created_at?->diffForHumans(),
                    'Reorder qty' => $this->product->reorder_quantity,
                ]);
        });
});
```

Wire a Slack channel onto every admin user:

```php
\App\Models\User::where('is_admin', 1)->each(function ($admin) {
    $admin->routeNotificationForSlack = config('services.slack.inventory_webhook');
});
```

---

## 8. Adjust stock from an import job

You have a CSV of new arrivals. Walk it through `InventoryService::adjust()` to keep the audit log clean.

```php
use Modules\Product\Services\InventoryService;
use Modules\Product\Models\ProductInventoryMovement;
use Modules\Product\Models\Product;

$inv = app(InventoryService::class);

$rows = \League\Csv\Reader::createFromPath(storage_path('imports/restock-2026-08-22.csv'))
    ->setHeaderOffset(0);

foreach ($rows as $row) {
    $product = Product::where('sku', $row['sku'])->first();
    if (!$product) {
        logger()->warning("Restock: unknown SKU {$row['sku']}");
        continue;
    }

    $inv->adjust(
        productId: $product->id,
        quantityChange: (int) $row['qty'],
        type: ProductInventoryMovement::TYPE_RESTOCK,
        notes: "Restock import: {$row['supplier']} / PO {$row['po_number']}",
        userId: auth()->id(),
    );
}
```

Each row produces one movement, the product's quantity is updated atomically, and low-stock alerts auto-resolve if the new total crosses back above threshold.

---

## 9. Headless product list for a React/Vue storefront

```javascript
// Frontend
const res = await fetch('/api/module/products?category_id=12&in_stock=true&limit=24');
const data = await res.json();

data.data.forEach(product => {
  console.log(product.title, product.price, product.thumbnail);
});
```

The `ProductsApiController@index` endpoint returns:

```json
{
  "data": [
    {
      "id": 42,
      "title": "Logo T-Shirt",
      "url": "logo-tshirt",
      "price": 19.99,
      "compare_price": 29.99,
      "in_stock": true,
      "thumbnail": "https://your-site/userfiles/...",
      "variants": [...]
    }
  ],
  "meta": { "current_page": 1, "total": 87 }
}
```

For variant resolution on the client:

```javascript
// User picks Size=M, Color=Red
const variant = product.variants.find(v =>
  v.attributes.size === 'M' && v.attributes.color === 'Red'
);

// Reserve it before checkout
await fetch('/api/cart/add', {
  method: 'POST',
  body: JSON.stringify({ product_id: product.id, variant_id: variant.id, quantity: 1 }),
});
```

---

## 10. Custom frontend filter (filter by brand)

Microweber's frontend filter UI hands input to `ShopFilter`. Adding a new filter dimension means subclassing it.

```php
// app/MyShopFilter.php
namespace App;

class MyShopFilter extends \Modules\Product\FrontendFilter\ShopFilter
{
    public function filterByBrand($query, $brand)
    {
        return $query->whereHas('contentData', function ($q) use ($brand) {
            $q->where('field_name', 'brand')
              ->where('field_value', $brand);
        });
    }
}
```

```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    $this->app->bind(\Modules\Product\FrontendFilter\ShopFilter::class, \App\MyShopFilter::class);
}
```

Now `?brand=Adidas` on any shop page narrows the listing automatically.

---

## 11. Listen for product creation → push to external PIM

Some teams keep a separate Product Information Management system (Akeneo, Pimcore) and need Microweber to broadcast every new product.

```php
\Event::listen(\Modules\Product\Events\ProductWasCreated::class, function ($event) {
    \Http::withToken(config('services.akeneo.token'))
        ->post(config('services.akeneo.url').'/api/rest/v1/products', [
            'identifier' => $event->product->sku,
            'enabled'    => (bool) $event->product->is_active,
            'family'     => 'default',
            'values'     => [
                'name'  => [['locale' => null, 'scope' => null, 'data' => $event->product->title]],
                'price' => [['locale' => null, 'scope' => null, 'data' => [['amount' => $event->product->price, 'currency' => 'USD']]]],
            ],
        ]);
});
```

For failure-tolerance, queue the listener:

```php
class SyncProductToAkeneo implements \Illuminate\Contracts\Queue\ShouldQueue
{
    public function handle(\Modules\Product\Events\ProductWasCreated $event) { /* … */ }
}
```

Register in `ProductServiceProvider::boot()` or `EventServiceProvider`.

---

## 12. Force one-by-one purchase (no bulk add to cart)

For high-end products where you only want quantity=1 per cart.

```php
$product->setContentData([
    'qty'                       => 1,
    'max_quantity_per_order'    => 1,
    'sku'                       => 'LIMITED-EDITION-001',
]);
$product->save();
```

The Cart module reads `max_quantity_per_order` from `content_data` and caps the quantity input automatically.

---

## Where to go next

- [Troubleshooting](./troubleshooting.md) — known issues, quirks, and their fixes.
- [Shop module](/modules/shop/) — frontend orchestration, filter UI, checkout integration.
- [Content module](/modules/content/) — the foundational class Product extends.
