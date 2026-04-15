# Product

Product management for e-commerce. Supports pricing rules, variant attributes, inventory tracking with stock alerts, customer-specific pricing, and special/sale prices.

## Creating Products

```php
use Modules\Product\Models\Product;

$product = Product::create([
    'title'       => 'Classic T-Shirt',
    'url'         => 'classic-tshirt',
    'description' => 'Premium cotton t-shirt.',
    'is_active'   => 1,
]);

// Set price via custom field
$product->setCustomField(['type' => 'price', 'name' => 'Price', 'value' => [29.99]]);
$product->save();

// Set inventory data
$product->setContentData(['qty' => 100, 'sku' => 'TSH-001', 'track_quantity' => 1]);
$product->save();
```

## Working with Variants

```php
use Modules\Product\Services\ProductVariantService;

$variantService = app(ProductVariantService::class);

// Create attributes (Size, Color)
$size  = $variantService->createAttribute([
    'name' => 'Size', 'key' => 'size', 'type' => 'select',
    'values' => [
        ['value' => 'S', 'key' => 's'],
        ['value' => 'M', 'key' => 'm'],
        ['value' => 'L', 'key' => 'l'],
    ],
]);

// Generate all combinations for a product
$combinations = $variantService->generateVariantCombinations($product, [$size->id]);

// Update a specific variant's price and stock
$variantService->updateCombination($combinations->first()->id, [
    'sku' => 'TSH-001-S', 'price' => 27.99, 'quantity' => 50,
]);

// Find variant by attribute keys
$variant = $variantService->findCombinationByAttributeKeys($product->id, ['size' => 'm']);

// Get frontend-ready options (for dropdowns / swatches)
$options = $variantService->getVariantOptions($product->id);
```

## Inventory Management

```php
$inventory = app(\Modules\Product\Services\InventoryService::class);

$inventory->getStock($productId);                // current quantity
$inventory->hasStock($productId, quantity: 3);   // boolean check
$inventory->restock($productId, quantity: 200, notes: 'Shipment #456');
$inventory->deductStock($productId, quantity: 1, referenceType: 'order', referenceId: $orderId);

// Reserve stock for a cart (auto-expires in 30 min)
$res = $inventory->reserveStock($productId, 2, 'cart', sessionId: session()->getId());
$inventory->releaseReservation($res->id);

// Adjust and summarize
$inventory->adjustStock($productId, newQuantity: 80, reason: 'Damage write-off');
$inventory->getInventorySummary($productId);
// => ['stock_quantity' => 80, 'reserved_quantity' => 2, 'available_quantity' => 78, ...]
```

## Advanced Pricing

```php
$pricing = app(\Modules\Product\Services\AdvancedPricingService::class);
$result = $pricing->calculatePrice($product->id, quantity: 10, basePrice: 29.99, customerId: $user->id);
// => ['base_price' => 29.99, 'final_price' => 24.99, 'discount' => 5.00, 'rules_applied' => [...]]
```

## Product API

```bash
# List products (admin auth required)
curl https://yoursite.com/api/product -H "Authorization: Bearer $TOKEN"
# Get variant options / all variants for a product
curl https://yoursite.com/api/product_variant/parent/42/options -H "Authorization: Bearer $TOKEN"
curl https://yoursite.com/api/product_variant/parent/42 -H "Authorization: Bearer $TOKEN"
# Save variant options (auto-generates combinations)
curl -X POST https://yoursite.com/api/product_variant_save -H "Authorization: Bearer $TOKEN" \
  -d '{"product_id":42,"options":[{"option_name":"Size","option_values":["S","M","L"]}]}'
```

## Events

`ProductIsCreating`, `ProductIsUpdating`, `ProductWasCreated`, `ProductWasUpdated`, `ProductWasDeleted`, `ProductWasDestroyed`.

## Key Classes

| Class | Purpose |
|---|---|
| `Models\Product` | Eloquent model (extends Content, scoped to products) |
| `Services\InventoryService` | Stock, reservations, alerts, movement log |
| `Services\ProductVariantService` | Variant attributes, combinations, options |
| `Services\AdvancedPricingService` | Tiered / customer-specific pricing |

## Admin Panel (Filament)

**ProductResource** (CRUD), **ProductVariantAttributeResource** (variant attrs), **ProductInventoryResource** (stock), **ProductsModuleSettings**, **ProductVariantManager** (Livewire). Listens to `OrderWasPaid` to auto-deduct inventory.
