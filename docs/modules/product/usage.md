# Product Module — Usage

Day-to-day patterns for working with products programmatically. For deep API signatures see [API Reference](./api.md); for full recipes see [Examples](./examples.md).

---

## Querying products

### Get all active products

```php
use Modules\Product\Models\Product;

$products = Product::where('is_active', 1)->orderByDesc('created_at')->get();
```

The global `ProductScope` automatically adds `where('content_type', 'product')` — you never need to add that yourself.

### One product by URL slug

```php
$product = Product::where('url', 'classic-tshirt')->first();
```

### Eager-load variants

```php
$product = Product::with(['variants', 'variants.attributeValues'])
    ->where('id', $id)
    ->first();
```

Where `variants` is the HasMany to `ProductVariantCombination` (defined via `product_id`).

### Filter via the ModelFilter

`ProductFilter` powers the public-site filter UI. To use it programmatically:

```php
$products = Product::filter([
    'category_id' => 12,
    'price_min'   => 10,
    'price_max'   => 50,
    'in_stock'    => true,
])->get();
```

The available filter keys live in `Modules/Product/Models/ModelFilters/ProductFilter.php`.

---

## Creating a product

```php
use Modules\Product\Models\Product;

$product = Product::create([
    'title'        => 'Wool Beanie',
    'url'          => 'wool-beanie',
    'description'  => 'Warm, soft, sustainable.',
    'is_active'    => 1,
    'parent'       => $shopPageId,    // optional: place under a shop page
]);

// Base price
$product->setCustomField([
    'type'  => 'price',
    'name'  => 'Price',
    'value' => [19.99],
]);

// Inventory
$product->setContentData([
    'qty'              => 50,
    'sku'              => 'BEANIE-001',
    'track_quantity'   => 1,
    'physical_product' => 1,
    'weight'           => 0.15,
    'weight_type'      => 'kg',
]);

$product->save();
```

Things to note:

- The `url` field must be unique under the parent. Microweber will auto-append `-1`, `-2`, … if you collide.
- `setCustomField()` and `setContentData()` are inherited from `Content` — they upsert into the `custom_fields` and `content_data` tables respectively.
- Lifecycle order on create: `ContentIsCreating` → `ProductIsCreating` → save → `ContentWasCreated` → `ProductWasCreated`.

### Setting a sale price

```php
$product->setCustomField([
    'type'  => 'price',
    'name'  => 'Special Price',
    'value' => [14.99],
]);
$product->save();
```

The Shop frontend reads both and displays the strike-through automatically.

---

## Updating

```php
$product = Product::find($id);
$product->title = 'Wool Beanie (Updated)';
$product->save();

$product->setContentData(['qty' => 30]);
$product->save();
```

Always re-`save()` after `setContentData()` / `setCustomField()` — they don't persist on their own.

To check what changed, hook the events:

```php
\Event::listen(\Modules\Product\Events\ProductWasUpdated::class, function ($event) {
    \Log::info('Product updated', [
        'id'      => $event->product->id,
        'changes' => $event->product->getChanges(),
    ]);
});
```

---

## Soft delete vs hard delete

```php
$product->delete();        // soft delete → fires ProductWasDeleted
$product->forceDelete();   // hard delete → fires ProductWasDestroyed
```

Soft-deleted products are excluded by the default scope but accessible via `Product::withTrashed()`.

---

## Working with variants

### Define attributes once, reuse across products

```php
use Modules\Product\Services\ProductVariantService;

$svc = app(ProductVariantService::class);

$size = ProductVariantAttribute::firstOrCreate(
    ['key' => 'size'],
    ['name' => 'Size']
);

$svc->syncAttributeValues($size, [
    ['value' => 'S'],
    ['value' => 'M'],
    ['value' => 'L'],
    ['value' => 'XL'],
]);
```

Attributes are global — every product can pick from the same Size pool.

### Generate combinations

```php
$svc->generateVariantCombinations($product, [$sizeAttribute->id, $colorAttribute->id]);
```

This creates one `ProductVariantCombination` row per Cartesian-product entry (Size × Color). Each row starts with NULL price + quantity, inheriting the product's defaults.

### Set per-variant prices + stock

```php
use Modules\Product\Models\ProductVariantCombination;

ProductVariantCombination::forProduct($product->id)
    ->each(function ($combo) {
        $combo->update([
            'sku'      => 'BEANIE-'.$combo->id,
            'quantity' => 25,
            'price'    => 19.99,
            'is_active'=> true,
        ]);
    });
```

### Mark a default variant

The default is what the frontend selects on page load:

```php
$default = ProductVariantCombination::forProduct($product->id)->first();
$default->update(['is_default' => true]);
```

`ProductVariantCombination::booted()` enforces "only one default per product" — setting `is_default=true` on a different row will automatically clear the previous default.

### Find a combination by attribute values

```php
$combo = ProductVariantCombination::findByAttributes($product->id, [
    'size'  => 'M',
    'color' => 'Red',
]);
```

Returns `null` if no combination matches the requested attribute pair.

---

## Pricing rules

### Create a bulk-discount rule

```php
use Modules\Product\Models\ProductPricingRule;

ProductPricingRule::create([
    'name'        => 'Buy 5+ get 10% off',
    'slug'        => 'bulk-tshirt-discount',
    'rule_type'   => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
    'price_type'  => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
    'product_ids' => [$product->id],
    'tiers'       => [
        ['min_qty' => 5,  'max_qty' => 9,  'discount' => 10],
        ['min_qty' => 10, 'max_qty' => 49, 'discount' => 15],
        ['min_qty' => 50, 'max_qty' => null, 'discount' => 25],
    ],
    'priority'    => 100,
    'is_active'   => 1,
    'is_public'   => 1,
    'valid_from'  => now(),
    'valid_to'    => now()->addMonths(3),
]);
```

### Customer-group rule (Wholesale)

```php
ProductPricingRule::create([
    'name'              => 'Wholesale - 20% off everything',
    'slug'              => 'wholesale-discount',
    'rule_type'         => ProductPricingRule::RULE_TYPE_CUSTOMER_GROUP,
    'price_type'        => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
    'customer_group_ids'=> [$wholesaleGroupId],
    'tiers'             => [['min_qty' => 1, 'discount' => 20]],
    'priority'          => 200,
    'is_active'         => 1,
    'is_stackable'      => false,
]);
```

### Per-customer override

For one specific customer (not a group):

```php
use Modules\Product\Models\ProductCustomerPricing;

ProductCustomerPricing::create([
    'product_id'        => $product->id,
    'user_id'           => $customer->id,
    'price'             => 12.00,            // hard-coded price
    'minimum_quantity'  => 1,
    'valid_from'        => now(),
    'is_active'         => 1,
]);
```

### Resolve final price

Always use the service — never compute by hand:

```php
$result = app(\Modules\Product\Services\AdvancedPricingService::class)
    ->calculatePrice(
        productId:       $product->id,
        quantity:        $qty,
        basePrice:       null,
        customerId:      auth()->id(),
        customerGroupId: $user?->customer_group_id,
    );

// $result keys: base_price, final_price, discount, discount_percentage, rules_applied
```

---

## Inventory operations

### Read stock

```php
$svc = app(\Modules\Product\Services\InventoryService::class);

$onHand     = $svc->getStock($product->id);                       // raw qty
$reserved   = $svc->getReservedQuantity($product->id);            // held by carts/orders
$available  = $svc->getAvailableQuantity($product->id);           // onHand - reserved
$hasFive    = $svc->hasStock($product->id, 5);                    // bool
```

For a variant, pass the `ProductVariantCombination` id as the second argument: `$svc->getStock($product->id, $variantId)`.

### Adjust stock (admin restock)

```php
$svc->adjust(
    productId: $product->id,
    quantityChange: +50,
    type: \Modules\Product\Models\ProductInventoryMovement::TYPE_RESTOCK,
    notes: 'Supplier shipment #SH-2026-08-22',
    userId: auth()->id(),
);
```

This appends a row to `product_inventory_movements` AND updates the product's stock counter atomically.

### Reserve for cart

Typically called by the Cart module — not directly:

```php
$svc->reserve(
    productId: $product->id,
    quantity: 2,
    sessionId: session()->getId(),
    expiresInMinutes: 30,
);
```

### Cleanup expired

Schedule the artisan command (see [Installation](./installation.md#schedule-the-inventory-cleanup-job)) — that's the canonical path. Manually:

```bash
php artisan inventory:cleanup-reservations
```

---

## Frontend filtering

The public Shop component uses `ShopFilter` (in `Modules/Product/FrontendFilter/`). To extend with a custom filter:

```php
class MyShopFilter extends \Modules\Product\FrontendFilter\ShopFilter
{
    public function filterByBrand($query, $brand)
    {
        return $query->whereHas('contentData', function ($q) use ($brand) {
            $q->where('field_name', 'brand')->where('field_value', $brand);
        });
    }
}
```

Bind it in a service provider:

```php
$this->app->bind(\Modules\Product\FrontendFilter\ShopFilter::class, \App\MyShopFilter::class);
```

---

## Listening to lifecycle events

```php
\Event::listen(\Modules\Product\Events\ProductWasCreated::class, function ($event) {
    // ping a marketing webhook
    \Http::post(config('services.zapier.product_webhook'), [
        'product_id' => $event->product->id,
        'title'      => $event->product->title,
    ]);
});

\Event::listen(\Modules\Product\Events\ProductWasUpdated::class, function ($event) {
    // re-index in search
    app(\App\Services\SearchIndex::class)->reindex($event->product);
});
```

Available events (full list in [API Reference](./api.md#events)):

- `ProductIsCreating`, `ProductWasCreated`
- `ProductIsUpdating`, `ProductWasUpdated`
- `ProductWasDeleted` (soft), `ProductWasDestroyed` (hard)

For cross-module flow (e.g. an order being paid triggers an inventory commit), see the **`UpdateInventoryOnOrderPaid`** listener in `Modules/Product/Listeners/`.

---

## Common patterns checklist

- ✅ Always re-`save()` after `setContentData()` / `setCustomField()`.
- ✅ Use `AdvancedPricingService::calculatePrice()` — never compute prices yourself.
- ✅ Use `InventoryService` for every stock change — never `update(['qty' => …])` directly on `content_data`.
- ✅ Set `is_default = true` on the variant the frontend should pre-select.
- ✅ Schedule `inventory:cleanup-reservations` in production.
- ❌ Don't query the `content` table directly when you want products — use `Product::query()` so the global scope filters correctly.
- ❌ Don't bulk-update `product_pricing_rules` from raw SQL without flushing the cache afterwards.
