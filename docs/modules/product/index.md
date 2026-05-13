# Product Module

The Product module is the **e-commerce data owner** for Microweber. It defines the canonical schema and behaviour for products, variants, attributes, pricing rules, inventory, and stock movements. Where the [Shop module](/modules/shop/) is the orchestrator that wires products to cart/checkout/order, Product is the module that actually stores the catalog and enforces stock + pricing rules.

> **TL;DR** — Products are `Content` rows (`content_type = 'product'`) with commerce attributes (price, sku, qty) layered on via the `content_data` sidecar. Variants live in their own tables. The Filament admin under "Shop" → "Products" is the canonical authoring surface.

---

## What this module owns

| Concern | Schema | Filament admin |
|---|---|---|
| Catalog records | `content` rows where `content_type='product'` | Shop → Products |
| Per-product commerce data | `content_data` (qty, sku, barcode, weight, dims) | Inline on the product form |
| Variant definitions | `product_variant_attributes` + `product_variant_attribute_values` | Shop Settings → Product Variant Attributes |
| Actual variant SKUs | `product_variants_combinations` + pivot | Inline on the product form's Variants tab |
| Stock movements (audit log) | `product_inventory_movements` | Shop Settings → Inventory Movement |
| Low-stock + OOS alerts | `product_inventory_alerts` | Surfaced via notifications |
| Cart/order stock holds | `product_stock_reservations` | Managed automatically by InventoryService |
| Bulk / tiered / customer-group pricing | `product_pricing_rules` | Shop Settings → Pricing Rules |
| Per-customer prices | `product_customer_pricing` | Inline on the customer or product form |

What this module does **NOT** own:

- Cart line items → [Cart module](/modules/shop/) (orchestrated by Shop)
- Order line items → Order module
- Tax + shipping calculation → Tax + Shipping modules
- Currency formatting → Currency module
- Product image upload — those go through the [Media module](/modules/media/) (this module just stores the resulting `media` IDs)
- Public-facing search / filtering UI → `ShopComponent` in the [Shop module](/modules/shop/api.md#shopcomponent)

---

## Architectural fact: Product extends Content

```php
namespace Modules\Product\Models;

class Product extends \Modules\Content\Models\Content
{
    // ...
}
```

This is the central design choice. It means:

- The `content` table holds **all** products. There is no separate `products` table.
- Products inherit every [Content module](/modules/content/) feature: categories, tags, custom fields, multi-language slugs, soft deletes, lifecycle events.
- Querying `Content::where('content_type', 'product')` returns the same rows as `Product::all()` (the global `ProductScope` adds that `where` automatically).
- A product's URL, layout, SEO metadata, and parent-page relationship all use the same fields a CMS page does.
- Lifecycle hooks fire **both** `ContentWas*` events (from Content) **and** `ProductWas*` events (from this module). When ordering subscribers, treat the Content events as the foundational hook and Product events as the commerce-specific hook.

If you're new to this pattern, read [the Content module docs](/modules/content/) first — Product makes a lot more sense once Content is internalised.

---

## Variants — the three-table system

The variant system uses three database tables that work together. Understanding the split is critical when you're querying or seeding variants programmatically.

```
ProductVariantAttribute  ──┐   "Size"   "Color"   "Material"
                            ▼
ProductVariantAttributeValue   "S" "M" "L"  "Red" "Blue"  "Cotton" "Wool"
                            ▼
ProductVariantCombination      one row per actual SKU you sell
                            ▲
                         (product_variants_combinations.product_id → content.id)
```

- **`ProductVariantAttribute`** — abstract attribute names ("Size", "Color"). Project-wide; reusable across products.
- **`ProductVariantAttributeValue`** — the values that belong to an attribute ("S", "M", "L" under Size). Also project-wide.
- **`ProductVariantCombination`** — the **actual purchasable SKU** for a specific product. Pivot table `product_variant_combination_attributes` joins this to the attribute values that define it ("T-Shirt #42 in Size:M + Color:Red"). This row carries the SKU, the price override, the per-variant stock count, and the optional image.

The Filament product form auto-generates combinations from the attribute matrix (Size × Color → S/Red, S/Blue, M/Red, M/Blue, L/Red, L/Blue) and lets the admin overwrite per-cell pricing and stock.

See [`ProductVariantService::generateVariantCombinations()`](./api.md#productvariantservice) for the programmatic equivalent.

---

## Pricing — three tiers

Products have **three layers of pricing**, evaluated in order:

1. **Base price** — stored as a CustomField row on the product (`type='price'`). Set via `setCustomField()` on the product, read via the `price` accessor.
2. **Variant price** — `product_variants_combinations.price`. If set, overrides the base price for that specific SKU.
3. **Pricing rules** — `product_pricing_rules` apply on top. They can do bulk discounts ("buy 5+ get 10% off"), customer-group pricing ("Wholesale group sees 20% off"), customer-specific overrides (the `product_customer_pricing` table), and stackable percentage/fixed discounts.

The canonical resolver is [`AdvancedPricingService::calculatePrice()`](./api.md#advancedpricingservice). It returns `{ base_price, final_price, discount, discount_percentage, rules_applied }` so you can show the customer both the strike-through and the final price.

> **Why three layers?** Migrations from older Microweber versions kept the CustomField-based base price for backwards compatibility. New installs should prefer setting prices directly on combinations + pricing rules; the base price stays as the fallback for non-variant products.

---

## Inventory — atomic, auditable

The inventory subsystem is built around three principles:

1. **Atomicity** — every stock change goes through `InventoryService` so the `quantity_before` / `quantity_after` columns on `product_inventory_movements` are accurate.
2. **Auditability** — every change appends a row to `product_inventory_movements` with a type tag (`sale`, `restock`, `adjustment`, `reservation`, `return`, `damaged`, `lost`, `transfer_in/out`, `initial`).
3. **Reservation, then commit** — when a customer adds to cart, `InventoryService` creates a `product_stock_reservations` row holding the qty for 30 minutes (configurable). On checkout it converts to an order reservation; on order paid it commits to a `sale` movement. Abandoned carts auto-expire.

Low-stock alerts fire automatically when a movement brings stock below the product's `low_stock_threshold` (column on `content` and `product_variants_combinations`). The `LowStockNotification` and `ProductOutOfStockNotification` channels both go to admin users by default.

The scheduled command `inventory:cleanup-reservations` runs every few minutes to release expired holds.

---

## Quick start

### Create a simple product

```php
use Modules\Product\Models\Product;

$product = Product::create([
    'title'       => 'Classic T-Shirt',
    'url'         => 'classic-tshirt',
    'description' => 'Premium cotton t-shirt.',
    'is_active'   => 1,
]);

// Base price (uses CustomField under the hood)
$product->setCustomField(['type' => 'price', 'name' => 'Price', 'value' => [29.99]]);

// Inventory + SKU
$product->setContentData([
    'qty'             => 100,
    'sku'             => 'TSH-001',
    'track_quantity'  => 1,
    'physical_product'=> 1,
    'weight'          => 0.25,
]);

$product->save();
```

### Add a sale (special) price

```php
$product->setCustomField(['type' => 'price', 'name' => 'Special Price', 'value' => [24.99]]);
$product->save();
```

The Shop frontend will automatically show both prices (strike-through + sale).

### Generate variants from attributes

```php
use Modules\Product\Services\ProductVariantService;

$svc = app(ProductVariantService::class);

$size  = $svc->createAttribute(['name' => 'Size']);
$color = $svc->createAttribute(['name' => 'Color']);

$svc->syncAttributeValues($size,  [['value' => 'S'], ['value' => 'M'], ['value' => 'L']]);
$svc->syncAttributeValues($color, [['value' => 'Red'], ['value' => 'Blue']]);

// Cartesian product → 6 combinations
$svc->generateVariantCombinations($product, [$size->id, $color->id]);
```

Each generated combination needs its own `sku` + `quantity` set before going live. Edit them via the Filament product form or update in bulk via `ProductVariantCombination::where('product_id', $product->id)->update([...])`.

### Read the customer-facing price (with rules applied)

```php
use Modules\Product\Services\AdvancedPricingService;

$pricing = app(AdvancedPricingService::class);

$result = $pricing->calculatePrice(
    productId: $product->id,
    quantity: 5,
    basePrice: null,           // resolves from product if null
    customerId: auth()->id(),
    customerGroupId: null,
);

// $result['final_price']    → 24.99 (or after-rule price)
// $result['discount']       → 5.00
// $result['rules_applied']  → ['Bulk 5+ Discount', ...]
```

---

## Where this module fits in the e-commerce cluster

The e-commerce cluster has one orchestrator and ten satellites. Product is the foundational data owner — most of the other satellites read from or write to its tables.

```
                    Shop  (orchestrator)
                      │
       ┌──────────────┼──────────────┐
       │              │              │
   Product       Cart, Checkout, Order, Payment,
   (you are     Invoice, Shipping, Coupons, Currency, Tax
    here)
```

Cross-module reading patterns:

- **Cart** reads `ProductVariantCombination` to resolve variant prices + stock; calls `InventoryService::reserve()` to hold stock.
- **Order** stores a snapshot of price + sku at order-creation time, so future price changes don't rewrite history.
- **Checkout** asks `InventoryService::confirmReservation()` to convert cart holds to order holds.
- **Payment** triggers `OrderPaid` → `UpdateInventoryOnOrderPaid` listener (in this module) → committed `sale` movement.
- **Coupons + Tax + Currency** consume the resolved final price from `AdvancedPricingService` and apply their own adjustments.

For bug routing across these modules, see the [Shop "Where to file bugs" matrix](/modules/shop/troubleshooting.md#where-to-file-bugs).

---

## Files in this section

- [Installation](./installation.md) — composer/module manifest, migrations, scheduled commands, env keys.
- [Usage](./usage.md) — day-to-day patterns: querying, creating, updating, variants, pricing rules, inventory.
- [API Reference](./api.md) — every public class + method.
- [Examples](./examples.md) — full recipes (digital products, configurable bundles, wholesale pricing, dropshipping).
- [Troubleshooting](./troubleshooting.md) — common issues + their fixes.
