# `Product` module

> **Slug:** `product`
> **Tier:** 1
>
> *Auto-generated from filesystem survey on 2026-04-25 with
> column / route / method extraction. Domain section is
> the only hand-edit needed; the rest of this file is
> regenerable from source.*

## Domain

*Hand-edit this section: describe what the module does
operationally, who consumes it, and which sibling modules
it interacts with.*

## Data model

### `product_meta_data` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `(unnamed)` | `dropIndex` | — |

### `product_inventory_movements` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `product_id` | `foreignId` | foreign-key |
  | `variant_id` | `unsignedBigInteger` | nullable |
  | `quantity_change` | `integer` | — |
  | `quantity_before` | `integer` | — |
  | `quantity_after` | `integer` | — |
  | `type` | `string` | — |
  | `reference_type` | `string` | nullable |
  | `reference_id` | `unsignedBigInteger` | nullable |
  | `notes` | `text` | nullable |
  | `user_id` | `unsignedInteger` | nullable |
  | `metadata` | `json` | nullable |
  | `timestamps` | `timestamps` | — |

### `product_inventory_alerts` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `product_id` | `foreignId` | foreign-key |
  | `variant_id` | `unsignedBigInteger` | nullable |
  | `alert_type` | `string` | — |
  | `current_quantity` | `integer` | — |
  | `threshold_quantity` | `integer` | — |
  | `is_resolved` | `boolean` | has-default |
  | `resolved_at` | `timestamp` | nullable |
  | `resolved_by` | `unsignedInteger` | nullable |
  | `resolution_notes` | `text` | nullable |
  | `notification_sent_to` | `json` | nullable |
  | `timestamps` | `timestamps` | — |

### `product_stock_reservations` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `product_id` | `foreignId` | foreign-key |
  | `variant_id` | `unsignedBigInteger` | nullable |
  | `quantity` | `integer` | — |
  | `reservation_type` | `string` | — |
  | `session_id` | `string` | nullable |
  | `user_id` | `unsignedInteger` | nullable |
  | `order_id` | `unsignedInteger` | nullable |
  | `expires_at` | `timestamp` | — |
  | `is_active` | `boolean` | has-default |
  | `metadata` | `json` | nullable |
  | `timestamps` | `timestamps` | — |

### `product_variants_combinations` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `low_stock_threshold` | `integer` | has-default |
  | `reorder_point` | `integer` | has-default |
  | `reorder_quantity` | `integer` | has-default |
  | `last_stock_check` | `timestamp` | nullable |
  | `last_stock_check` | `dropColumn` | — |
  | `reorder_quantity` | `dropColumn` | — |
  | `reorder_point` | `dropColumn` | — |
  | `low_stock_threshold` | `dropColumn` | — |
  | `id` | `id` | — |
  | `product_id` | `foreignId` | foreign-key |
  | `sku` | `string` | nullable, unique |
  | `barcode` | `string` | nullable, unique |
  | `price` | `decimal` | nullable |
  | `compare_price` | `decimal` | nullable |
  | `cost_price` | `decimal` | nullable |
  | `quantity` | `integer` | has-default |
  | `quantity_type` | `string` | has-default |
  | `track_quantity` | `boolean` | has-default |
  | `allow_backorders` | `boolean` | has-default |
  | `weight` | `decimal` | nullable |
  | `image` | `string` | nullable |
  | `is_default` | `boolean` | has-default |
  | `is_active` | `boolean` | has-default |
  | `metadata` | `json` | nullable |
  | `timestamps` | `timestamps` | — |
  | `sku` | `index` | — |
  | `barcode` | `index` | — |

### `content` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `low_stock_threshold` | `integer` | nullable, has-default |
  | `reorder_point` | `integer` | nullable, has-default |
  | `reorder_quantity` | `integer` | nullable, has-default |
  | `reorder_quantity` | `dropColumn` | — |
  | `reorder_point` | `dropColumn` | — |
  | `low_stock_threshold` | `dropColumn` | — |

### `product_variant_attributes` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | — |
  | `key` | `string` | unique |
  | `description` | `text` | nullable |
  | `type` | `string` | has-default |
  | `position` | `integer` | has-default |
  | `is_active` | `boolean` | has-default |
  | `settings` | `json` | nullable |
  | `created_by` | `unsignedInteger` | nullable |
  | `edited_by` | `unsignedInteger` | nullable |
  | `timestamps` | `timestamps` | — |
  | `position` | `index` | — |

### `product_variant_attribute_values` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `attribute_id` | `foreignId` | foreign-key |
  | `value` | `string` | — |
  | `key` | `string` | — |
  | `description` | `text` | nullable |
  | `color_code` | `string` | nullable |
  | `image` | `string` | nullable |
  | `position` | `integer` | has-default |
  | `is_active` | `boolean` | has-default |
  | `metadata` | `json` | nullable |
  | `created_by` | `unsignedInteger` | nullable |
  | `edited_by` | `unsignedInteger` | nullable |
  | `timestamps` | `timestamps` | — |
  | `is_active` | `index` | — |

### `product_variant_combination_attributes` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `combination_id` | `foreignId` | foreign-key |
  | `attribute_value_id` | `foreignId` | foreign-key |
  | `attribute_value_id` | `foreign` | — |
  | `timestamps` | `timestamps` | — |
  | `attribute_value_id` | `index` | — |

### `product_pricing_rules` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | — |
  | `slug` | `string` | unique, indexed |
  | `description` | `text` | nullable |
  | `product_ids` | `json` | nullable |
  | `excluded_product_ids` | `json` | nullable |
  | `category_ids` | `json` | nullable |
  | `excluded_category_ids` | `json` | nullable |
  | `customer_group_ids` | `json` | nullable |
  | `customer_ids` | `json` | nullable |
  | `is_public` | `boolean` | has-default |
  | `rule_type` | `enum` | has-default |
  | `tiers` | `json` | nullable |
  | `price_type` | `enum` | has-default |
  | `priority` | `integer` | has-default |
  | `is_stackable` | `boolean` | has-default |
  | `cannot_stack_with` | `json` | nullable |
  | `valid_from` | `dateTime` | nullable |
  | `valid_to` | `dateTime` | nullable |
  | `max_usage_count` | `integer` | nullable |
  | `usage_count` | `integer` | has-default |
  | `max_usage_per_customer` | `integer` | nullable |
  | `is_active` | `boolean` | has-default |
  | `disabled_at` | `timestamp` | nullable |
  | `metadata` | `json` | nullable |
  | `timestamps` | `timestamps` | — |

### `product_customer_pricing` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `product_id` | `unsignedBigInteger` | nullable |
  | `user_id` | `unsignedInteger` | nullable |
  | `price` | `decimal` | nullable |
  | `compare_price` | `decimal` | nullable |
  | `minimum_quantity` | `decimal` | has-default |
  | `valid_from` | `dateTime` | nullable |
  | `valid_to` | `dateTime` | nullable |
  | `is_active` | `boolean` | has-default |
  | `metadata` | `json` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Product\Models\ModelFilters\ProductFilter`

Source: `Models/ModelFilters/ProductFilter.php`. 

### `Modules\Product\Models\Product`

Source: `Models/Product.php`. Table: `content`. 

### `Modules\Product\Models\ProductCustomerPricing`

Source: `Models/ProductCustomerPricing.php`. Table: `product_customer_pricing`. 

**Fillable:** `product_id`, `user_id`, `price`, `compare_price`, `minimum_quantity`, `valid_from`, `valid_to`, `is_active`, `metadata`

**Casts:**

  - `price` → `decimal:2`
  - `compare_price` → `decimal:2`
  - `minimum_quantity` → `decimal:2`
  - `valid_from` → `datetime`
  - `valid_to` → `datetime`
  - `is_active` → `boolean`
  - `metadata` → `array`

### `Modules\Product\Models\ProductInventoryAlert`

Source: `Models/ProductInventoryAlert.php`. Table: `product_inventory_alerts`. 

**Fillable:** `product_id`, `variant_id`, `alert_type`, `current_quantity`, `threshold_quantity`, `is_resolved`, `resolved_at`, `resolved_by`, `resolution_notes`, `notification_sent_to`

**Casts:**

  - `product_id` → `integer`
  - `variant_id` → `integer`
  - `current_quantity` → `integer`
  - `threshold_quantity` → `integer`
  - `is_resolved` → `boolean`
  - `resolved_at` → `datetime`
  - `resolved_by` → `integer`
  - `notification_sent_to` → `json`

### `Modules\Product\Models\ProductInventoryMovement`

Source: `Models/ProductInventoryMovement.php`. Table: `product_inventory_movements`. 

**Fillable:** `product_id`, `variant_id`, `quantity_change`, `quantity_before`, `quantity_after`, `type`, `reference_type`, `reference_id`, `notes`, `user_id`, `metadata`

**Casts:**

  - `quantity_change` → `integer`
  - `quantity_before` → `integer`
  - `quantity_after` → `integer`
  - `reference_id` → `integer`
  - `user_id` → `integer`
  - `variant_id` → `integer`
  - `metadata` → `json`

### `Modules\Product\Models\ProductMetaData`

Source: `Models/ProductMetaData.php`. 

**Fillable:** `qty`, `sku`, `barcode`, `track_quantity`, `max_quantity_per_order`, `sell_oos`, `physical_product`, `free_shipping`, `shipping_fixed_cost`, `weight_type`, `params_in_checkout`, `has_special_price`, `weight`, `width`, `height`, `depth`

### `Modules\Product\Models\ProductPrice`

Source: `Models/ProductPrice.php`. 

### `Modules\Product\Models\ProductPricingRule`

Source: `Models/ProductPricingRule.php`. Table: `product_pricing_rules`. 

**Fillable:** `name`, `slug`, `description`, `product_ids`, `excluded_product_ids`, `category_ids`, `excluded_category_ids`, `customer_group_ids`, `customer_ids`, `is_public`, `rule_type`, `tiers`, `price_type`, `priority`, `is_stackable`, `cannot_stack_with`, `valid_from`, `valid_to`, `max_usage_count`, `usage_count`, `max_usage_per_customer`, `is_active`, `disabled_at`, `metadata`

**Casts:**

  - `product_ids` → `array`
  - `excluded_product_ids` → `array`
  - `category_ids` → `array`
  - `excluded_category_ids` → `array`
  - `customer_group_ids` → `array`
  - `customer_ids` → `array`
  - `is_public` → `boolean`
  - `tiers` → `array`
  - `priority` → `integer`
  - `is_stackable` → `boolean`
  - `cannot_stack_with` → `array`
  - `valid_from` → `datetime`
  - `valid_to` → `datetime`
  - `max_usage_count` → `integer`
  - `usage_count` → `integer`
  - `max_usage_per_customer` → `integer`
  - `is_active` → `boolean`
  - `disabled_at` → `datetime`
  - `metadata` → `array`

### `Modules\Product\Models\ProductSpecialPrice`

Source: `Models/ProductSpecialPrice.php`. 

### `Modules\Product\Models\ProductStockReservation`

Source: `Models/ProductStockReservation.php`. Table: `product_stock_reservations`. 

**Fillable:** `product_id`, `variant_id`, `quantity`, `reservation_type`, `session_id`, `user_id`, `order_id`, `expires_at`, `is_active`, `metadata`

**Casts:**

  - `product_id` → `integer`
  - `variant_id` → `integer`
  - `quantity` → `integer`
  - `user_id` → `integer`
  - `order_id` → `integer`
  - `expires_at` → `datetime`
  - `is_active` → `boolean`
  - `metadata` → `json`

### `Modules\Product\Models\ProductVariant`

Source: `Models/ProductVariant.php`. 

### `Modules\Product\Models\ProductVariantAttribute`

Source: `Models/ProductVariantAttribute.php`. Table: `product_variant_attributes`. 

**Fillable:** `name`, `key`, `description`, `type`, `position`, `is_active`, `settings`

**Casts:**

  - `is_active` → `boolean`
  - `position` → `integer`
  - `settings` → `json`

### `Modules\Product\Models\ProductVariantAttributeValue`

Source: `Models/ProductVariantAttributeValue.php`. Table: `product_variant_attribute_values`. 

**Fillable:** `attribute_id`, `value`, `key`, `description`, `color_code`, `image`, `position`, `is_active`, `metadata`

**Casts:**

  - `is_active` → `boolean`
  - `position` → `integer`
  - `metadata` → `json`

### `Modules\Product\Models\ProductVariantCombination`

Source: `Models/ProductVariantCombination.php`. Table: `product_variants_combinations`. 

**Fillable:** `product_id`, `sku`, `barcode`, `price`, `compare_price`, `cost_price`, `quantity`, `quantity_type`, `track_quantity`, `allow_backorders`, `weight`, `image`, `is_default`, `is_active`, `metadata`

**Casts:**

  - `price` → `decimal:2`
  - `compare_price` → `decimal:2`
  - `cost_price` → `decimal:2`
  - `weight` → `decimal:2`
  - `quantity` → `integer`
  - `track_quantity` → `boolean`
  - `allow_backorders` → `boolean`
  - `is_default` → `boolean`
  - `is_active` → `boolean`
  - `metadata` → `json`

## API endpoints

### `routes/admin.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/export` | `ProductExportController::export` |

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `ProductsApiController::index` |
  | `GET` | `/{product}` | `ProductsApiController::show` |
  | `POST` | `/` | `ProductsApiController::store` |
  | `PUT` | `/{product}` | `ProductsApiController::update` |
  | `PATCH` | `/{product}` | `ProductsApiController::update` |
  | `DELETE` | `/{product}` | `ProductsApiController::destroy` |

## Controllers

### `Modules\Product\Http\Controllers\Admin\ProductExportController`

Source: `Http/Controllers/Admin/ProductExportController.php`.

  - `export(Request $request): StreamedResponse`

### `Modules\Product\Http\Controllers\Api\ProductApiController`

Source: `Http/Controllers/Api/ProductApiController.php`.

  - `index(Request $request)`
  - `store(ProductRequest $request)`
  - `show($id)`
  - `update(ProductUpdateRequest $request, $id)`
  - `destroy($id)`

### `Modules\Product\Http\Controllers\Api\ProductPublicApiController`

Source: `Http/Controllers/Api/ProductPublicApiController.php`.

  - `index(Request $request): JsonResponse`
  - `show(int $id): JsonResponse`
  - `bySlug(string $slug): JsonResponse`
  - `featured(Request $request): JsonResponse`
  - `byCategory(Request $request, string $categorySlug): JsonResponse`

### `Modules\Product\Http\Controllers\Api\ProductVariantApiController`

Source: `Http/Controllers/Api/ProductVariantApiController.php`.

  - `index(Request $request)`
  - `store(Request $request)`
  - `show($id)`
  - `update(Request $request, $productVariant)`
  - `destroy($id)`

### `Modules\Product\Http\Controllers\Api\ProductsApiController`

Source: `Http/Controllers/Api/ProductsApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(Request $request, int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

### `Modules\Product\Http\Controllers\ProductQuickViewController`

Source: `Http/Controllers/ProductQuickViewController.php`.

  - `view(Request $request)`

## Service classes

### `Modules\Product\Services\AdvancedPricingService`

Source: `Services/AdvancedPricingService.php`.

  - `calculatePrice(int $productId, int $quantity = 1, float $basePrice = 0, ?int $customerId = null, ?int $customerGroupId = null): array`
  - `getBasePrice(int $productId): float`
  - `getCustomerPricing(int $productId, ?int $customerId = null): ?ProductCustomerPricing`
  - `createBulkPricingRule(array $data): ProductPricingRule`
  - `createCustomerPricing(int $productId, int $customerId, float $price, array $data = []): ProductCustomerPricing`
  - `clearPricingCache(?int $customerId = null): void`
  - `getPricingTiers(int $productId): array`
  - `validateTiers(array $tiers): bool`
  - `applyPricingToCart(array $items, ?int $customerId = null, ?int $customerGroupId = null): array`
  - `hasActivePricingRules(int $productId, ?int $customerId = null): bool`

### `Modules\Product\Services\InventoryService`

Source: `Services/InventoryService.php`.

  - `getStock(int $productId, ?int $variantId = null): int`
  - `getAvailableQuantity(int $productId, ?int $variantId = null): int`
  - `getReservedQuantity(int $productId, ?int $variantId = null): int`
  - `hasStock(int $productId, int $quantity, ?int $variantId = null): bool`
  - `reserveStock(int $productId, int $quantity, string $reservationType, ?int $variantId = null, ?string $sessionId = null, ?int $userId = null, ?int $orderId = null, ?int $minutes = null): ProductStockReservation`
  - `releaseReservation(int $reservationId, ?string $reason = null): bool`
  - `restock(int $productId, int $quantity, ?int $variantId = null, ?string $notes = null, ?int $userId = null, ?string $referenceType = null, ?int $referenceId = null): bool`
  - `deductStock(int $productId, int $quantity, ?int $variantId = null, ?string $notes = null, ?int $userId = null, ?string $referenceType = null, ?int $referenceId = null): bool`
  - `adjustStock(int $productId, int $newQuantity, ?int $variantId = null, ?string $reason = null, ?int $userId = null): bool`
  - `processReturn(int $productId, int $quantity, ?int $variantId = null, ?int $orderId = null, ?string $notes = null, ?int $userId = null): bool`
  - `checkStockLevels(int $productId, ?int $variantId = null): void`
  - `resolveAlerts(int $productId, ?int $variantId = null, ?int $userId = null): void`
  - `getMovementHistory(int $productId, ?int $variantId = null, int $limit = 50): array`
  - `getLowStockAlerts(int $limit = 50): array`
  - `cleanupExpiredReservations(): int`
  - `getInventorySummary(int $productId, ?int $variantId = null): array`
  - `bulkUpdateStock(array $updates, ?int $userId = null, ?string $reason = null): array`
  - `transferStock(int $productId, int $quantity, string $fromLocation, string $toLocation, ?int $variantId = null, ?int $userId = null): bool`

### `Modules\Product\Services\ProductVariantService`

Source: `Services/ProductVariantService.php`.

  - `createAttribute(array $data): ProductVariantAttribute`
  - `updateAttribute(int $attributeId, array $data): ProductVariantAttribute`
  - `createAttributeValue(int $attributeId, array $data): ProductVariantAttributeValue`
  - `syncAttributeValues(ProductVariantAttribute $attribute, array $values): void`
  - `generateVariantCombinations(Content $product, array $attributeIds): Collection`
  - `findOrCreateCombination(int $productId, array $attributeValueIds): ProductVariantCombination`
  - `findCombinationByValues(int $productId, array $valueIds): ?ProductVariantCombination`
  - `findCombinationByAttributeKeys(int $productId, array $attributes): ?ProductVariantCombination`
  - `updateCombination(int $combinationId, array $data): ProductVariantCombination`
  - `setDefaultVariant(int $productId, int $combinationId): bool`
  - `getProductVariants(int $productId): Collection`
  - `deleteCombination(int $combinationId): bool`
  - `updateInventory(int $combinationId, int $quantity, ?bool $trackQuantity = null): bool`
  - `getVariantOptions(int $productId): array`
  - `getActiveAttributes(): Collection`
  - `importVariants(int $productId, array $variantsData): Collection`

## Events

  - `Modules\Product\Events\ProductIsCreating`
  - `Modules\Product\Events\ProductIsUpdating`
  - `Modules\Product\Events\ProductWasCreated`
  - `Modules\Product\Events\ProductWasDeleted`
  - `Modules\Product\Events\ProductWasDestroyed`
  - `Modules\Product\Events\ProductWasUpdated`
  - `Modules\Product\Listeners\UpdateInventoryOnOrderPaid`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Product\Filament\Admin\ProductVariantManager` | — | — |
  | `Modules\Product\Filament\Admin\Resources\ProductInventoryResource` | Shop Settings | Inventory |
  | `Modules\Product\Filament\Admin\Resources\ProductInventoryResource\Pages\CreateProductInventory` | — | — |
  | `Modules\Product\Filament\Admin\Resources\ProductInventoryResource\Pages\EditProductInventory` | — | — |
  | `Modules\Product\Filament\Admin\Resources\ProductInventoryResource\Pages\ListProductInventory` | — | — |
  | `Modules\Product\Filament\Admin\Resources\ProductPricingRuleResource` | Shop Settings | — |
  | `Modules\Product\Filament\Admin\Resources\ProductPricingRuleResource\Pages\CreateProductPricingRule` | — | — |
  | `Modules\Product\Filament\Admin\Resources\ProductPricingRuleResource\Pages\EditProductPricingRule` | — | — |
  | `Modules\Product\Filament\Admin\Resources\ProductPricingRuleResource\Pages\ListProductPricingRules` | — | — |
  | `Modules\Product\Filament\Admin\Resources\ProductResource` | Shop | — |
  | `Modules\Product\Filament\Admin\Resources\ProductResource\Pages\CreateProduct` | — | — |
  | `Modules\Product\Filament\Admin\Resources\ProductResource\Pages\EditProduct` | — | — |
  | `Modules\Product\Filament\Admin\Resources\ProductResource\Pages\ListProducts` | — | — |
  | `Modules\Product\Filament\Admin\Resources\ProductResource\RelationManagers\CustomFieldsRelationManager` | — | — |
  | `Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource` | Shop Settings | — |
  | `Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource\Pages\CreateProductVariantAttribute` | — | — |
  | `Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource\Pages\EditProductVariantAttribute` | — | — |
  | `Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource\Pages\ListProductVariantAttributes` | — | — |
  | `Modules\Product\Filament\Exports\ProductExporter` | — | — |
  | `Modules\Product\Filament\Imports\ProductImporter` | — | — |
  | `Modules\Product\Filament\ProductsModuleSettings` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Product/Tests`

### `Tests/Filament/ProductResourceTest.php`

  - `it_can_render_inventory_list_page`
  - `it_can_render_variant_attributes_list_page`

### `Tests/Unit/AdvancedPricingServiceTest.php`

  - `it_returns_zero_for_nonexistent_product`

### `Tests/Unit/Filament/ProductResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_can_set_product_inventory_fields`

### `Tests/Unit/InventoryManagementTest.php`

  - `it_throws_exception_for_negative_adjustment`

### `Tests/Unit/ProductImportExportTest.php`

  - `it_can_get_importer_columns`
  - `it_can_import_products_with_valid_data`
  - `it_importer_provides_notification_body`
  - `it_importer_has_options_form_components`
  - `it_importer_has_model_attribute`
  - `it_validates_required_fields_in_import`
  - `it_can_filter_products_before_export`
  - `it_provides_import_summary_notification`

### `Tests/Unit/ProductVariantSystemTest.php`

  - `test_can_create_variant_attribute`
  - `test_can_create_attribute_with_values`
  - `test_can_generate_variant_combinations`
  - `test_can_find_variant_by_attributes`
  - `test_can_update_variant_combination`
  - `test_can_track_variant_stock`
  - `test_can_set_default_variant`
  - `test_can_get_variant_options_for_frontend`
  - `test_can_import_variants_from_array`
  - `test_auto_generates_key_from_name`
  - …2 more.

## Service providers

  - `Modules\Product\Providers\ProductServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
