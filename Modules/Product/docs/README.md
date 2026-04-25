# `Product` module

> **Slug:** `product`
> **Tier:** 1
>
> Tier-1 module — owns its own data + exposes a public API.
>
> *(Auto-generated from filesystem survey on 2026-04-25;
> hand-edit to add operator-side context. The canonical
> shape lives in [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md);
> use `Modules/Settings/docs/README.md` as the
> hand-curated example.)*

## Domain

*Hand-edit this section to describe what the module does
operationally and which sibling modules it interacts
with.*

## Data model

Migrations under `Modules/Product/database/migrations/`:

  - `database/migrations/2024_00_00_000000_create_product_meta_data_table.php`
  - `database/migrations/2026_03_21_000001_add_indexes_to_product_meta_data.php`
  - `database/migrations/2026_03_22_000001_create_product_inventory_tables.php`
  - `database/migrations/2026_03_22_000001_create_product_variant_attributes_table.php`
  - `database/migrations/2026_03_22_000002_create_product_pricing_rules_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Product\Models\ModelFilters\ProductFilter` | `Models/ModelFilters/ProductFilter.php` |
| `Modules\Product\Models\Product` | `Models/Product.php` |
| `Modules\Product\Models\ProductCustomerPricing` | `Models/ProductCustomerPricing.php` |
| `Modules\Product\Models\ProductInventoryAlert` | `Models/ProductInventoryAlert.php` |
| `Modules\Product\Models\ProductInventoryMovement` | `Models/ProductInventoryMovement.php` |
| `Modules\Product\Models\ProductMetaData` | `Models/ProductMetaData.php` |
| `Modules\Product\Models\ProductPrice` | `Models/ProductPrice.php` |
| `Modules\Product\Models\ProductPricingRule` | `Models/ProductPricingRule.php` |
| `Modules\Product\Models\ProductSpecialPrice` | `Models/ProductSpecialPrice.php` |
| `Modules\Product\Models\ProductStockReservation` | `Models/ProductStockReservation.php` |
| `Modules\Product\Models\ProductVariant` | `Models/ProductVariant.php` |
| `Modules\Product\Models\ProductVariantAttribute` | `Models/ProductVariantAttribute.php` |
| `Modules\Product\Models\ProductVariantAttributeValue` | `Models/ProductVariantAttributeValue.php` |
| `Modules\Product\Models\ProductVariantCombination` | `Models/ProductVariantCombination.php` |

## API endpoints

Route files:

  - `routes/admin.php`
  - `routes/api.php`
  - `routes/web.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Product\Http\Controllers\Admin\ProductExportController`
  - `Modules\Product\Http\Controllers\Api\ProductApiController`
  - `Modules\Product\Http\Controllers\Api\ProductPublicApiController`
  - `Modules\Product\Http\Controllers\Api\ProductVariantApiController`
  - `Modules\Product\Http\Controllers\Api\ProductsApiController`
  - `Modules\Product\Http\Controllers\ProductQuickViewController`

## Service classes

  - `Modules\Product\Services\AdvancedPricingService`
  - `Modules\Product\Services\InventoryService`
  - `Modules\Product\Services\ProductVariantService`

## Events

  - `Modules\Product\Events\ProductIsCreating`
  - `Modules\Product\Events\ProductIsUpdating`
  - `Modules\Product\Events\ProductWasCreated`
  - `Modules\Product\Events\ProductWasDeleted`
  - `Modules\Product\Events\ProductWasDestroyed`
  - `Modules\Product\Events\ProductWasUpdated`
  - `Modules\Product\Listeners\UpdateInventoryOnOrderPaid`

## Filament admin

  - `Modules\Product\Filament\Admin\ProductVariantManager`
  - `Modules\Product\Filament\Admin\Resources\ProductInventoryResource`
  - `Modules\Product\Filament\Admin\Resources\ProductInventoryResource\Pages\CreateProductInventory`
  - `Modules\Product\Filament\Admin\Resources\ProductInventoryResource\Pages\EditProductInventory`
  - `Modules\Product\Filament\Admin\Resources\ProductInventoryResource\Pages\ListProductInventory`
  - `Modules\Product\Filament\Admin\Resources\ProductPricingRuleResource`
  - `Modules\Product\Filament\Admin\Resources\ProductPricingRuleResource\Pages\CreateProductPricingRule`
  - `Modules\Product\Filament\Admin\Resources\ProductPricingRuleResource\Pages\EditProductPricingRule`
  - `Modules\Product\Filament\Admin\Resources\ProductPricingRuleResource\Pages\ListProductPricingRules`
  - `Modules\Product\Filament\Admin\Resources\ProductResource`
  - `Modules\Product\Filament\Admin\Resources\ProductResource\Pages\CreateProduct`
  - `Modules\Product\Filament\Admin\Resources\ProductResource\Pages\EditProduct`
  - `Modules\Product\Filament\Admin\Resources\ProductResource\Pages\ListProducts`
  - `Modules\Product\Filament\Admin\Resources\ProductResource\RelationManagers\CustomFieldsRelationManager`
  - `Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource`
  - `Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource\Pages\CreateProductVariantAttribute`
  - `Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource\Pages\EditProductVariantAttribute`
  - `Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource\Pages\ListProductVariantAttributes`
  - `Modules\Product\Filament\Exports\ProductExporter`
  - `Modules\Product\Filament\Imports\ProductImporter`
  - `Modules\Product\Filament\ProductsModuleSettings`

## Tests

Run: `php vendor/bin/phpunit Modules/Product/Tests`

Test files:

  - `Tests/Filament/ProductResourceTest.php`
  - `Tests/Unit/AdvancedPricingServiceTest.php`
  - `Tests/Unit/CartesianProductGeneratorTest.php`
  - `Tests/Unit/CountableIterator.php`
  - `Tests/Unit/Filament/ProductResourceTest.php`
  - `Tests/Unit/InventoryManagementTest.php`
  - `Tests/Unit/ProductApiControllerTest.php`
  - `Tests/Unit/ProductCustomerPricingTest.php`
  - `Tests/Unit/ProductFilterTest.php`
  - `Tests/Unit/ProductImportExportTest.php`
  - `Tests/Unit/ProductPricingRuleTest.php`
  - `Tests/Unit/ProductVariantApiControllerTest.php`
  - `Tests/Unit/ProductVariantSystemTest.php`

## Service providers

  - `Modules\Product\Providers\ProductServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
