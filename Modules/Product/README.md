# Product

Product management for e-commerce. Supports pricing rules, variant attributes, inventory tracking with stock alerts, customer-specific pricing, and special/sale prices.

## Key Features

- Product catalog with metadata, images, and descriptions
- Variant system with configurable attributes and combinations
- Inventory tracking with stock movements, alerts, and reservations
- Pricing rules: base price, special prices, customer-specific pricing
- Automatic inventory adjustment on order payment
- Custom price validator for form inputs

## Key Classes

| Class | Purpose |
|---|---|
| `Services\InventoryService` | Inventory operations (stock, alerts, reservations) |
| `Models\Product` | Core product model |
| `Models\ProductVariant` | Product variant with its own pricing/stock |
| `Models\ProductVariantAttribute` | Attribute definitions (size, color, etc.) |
| `Models\ProductPrice` / `ProductSpecialPrice` | Pricing models |
| `Models\ProductInventoryMovement` | Stock movement audit trail |
| `Models\ProductInventoryAlert` | Low-stock alert thresholds |
| `Models\ProductCustomerPricing` | Per-customer price overrides |
| `Listeners\UpdateInventoryOnOrderPaid` | Reduces stock when order is paid |

## Events

- `ProductIsCreating` / `ProductIsUpdating` -- before save
- `ProductWasCreated` / `ProductWasUpdated` -- after save
- `ProductWasDeleted` / `ProductWasDestroyed` -- deletion lifecycle

Listens to: `OrderWasPaid` (from Order module) to update inventory.

## Database Tables

- `product_meta_data` -- product metadata and attributes
- `product_variant_attributes` / `product_variant_attribute_values` / `product_variant_combinations` -- variant system
- `product_inventory_movements` / `product_inventory_alerts` / `product_stock_reservations` -- inventory
- `product_pricing_rules` -- advanced pricing rules

## Admin Panel (Filament)

- **ProductResource** -- full product CRUD
- **ProductVariantAttributeResource** -- manage variant attributes
- **ProductInventoryResource** -- inventory management
- **ProductsModuleSettings** -- product module settings
- **ProductVariantManager** -- Livewire variant combination builder

## Routes

Web, API, and admin routes defined in `routes/web.php`, `routes/api.php`, `routes/admin.php`.

## Usage

```php
$product = \Modules\Product\Models\Product::find(1);
$variants = $product->variants;
$inventoryService = app(\Modules\Product\Services\InventoryService::class);
```
