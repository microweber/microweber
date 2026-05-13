# Product Module — Troubleshooting

## `Product::all()` returns 0 rows but products exist in the DB

1. **Wrong content_type** — verify with `\DB::table('content')->where('content_type','product')->count()`. If 0, your rows were saved with a different type (often `'page'` after a botched import).
2. **Global scope mismatch** — `ProductScope` filters on `content_type = 'product'`. If you've subclassed Product, the scope still expects that exact string.
3. **Soft-deleted rows** — `Product::withTrashed()->get()` to confirm.

```php
// Quick repair if products were imported with the wrong content_type
\DB::table('content')->whereIn('id', [42, 43, 44])->update(['content_type' => 'product', 'subtype' => 'product']);
```

## Price always shows as 0

The base price is stored as a CustomField row, NOT as a column on `content`. Two common causes:

1. **You wrote to the wrong place** — `$product->price = 29.99; $product->save()` does NOT work; `price` is a read-only accessor. Use `setCustomField()` (see [Usage](./usage.md#creating-a-product)).
2. **`setCustomField` not followed by `save()`** — `setCustomField()` mutates the in-memory custom fields array; the upsert only runs on `save()`.

```php
// Wrong:
$product->price = 29.99;
$product->save();   // does nothing

// Right:
$product->setCustomField(['type' => 'price', 'name' => 'Price', 'value' => [29.99]]);
$product->save();
```

## Special price not displayed on the frontend

1. **Wrong custom-field name** — must be exactly `"Special Price"` (case-sensitive) for the default templates to pick it up.
2. **Both prices identical** — most frontends only show the strikethrough when special < regular. If they're equal, the strikethrough is suppressed.
3. **`is_active=0` on the special-price custom field row** — check `\DB::table('custom_fields')->where('rel_id', $productId)->where('type', 'price')->get()`.

## Variant combinations show empty SKUs

`ProductVariantService::generateVariantCombinations()` creates rows with NULL `sku` and `quantity` by default — it's the caller's job to fill them. See [Examples §3](./examples.md#3-configurable-product-with-full-variants) step 4. Without SKUs, the Cart module can't tell variants apart and treats them as duplicates.

## Variant selector on product page shows duplicate options

You have two `ProductVariantAttribute` rows with the same `key` (likely created via direct SQL bypassing the auto-slug). Dedupe:

```sql
SELECT key, COUNT(*) c FROM product_variant_attributes GROUP BY key HAVING c > 1;
```

Merge values into the older row, then delete the duplicate. The model's `boot()` prevents this from happening via the ORM but raw inserts can slip through.

## "Out of stock" but you can see stock in the admin

This is almost always the reservation system holding stock for abandoned carts. Check:

```php
\Modules\Product\Models\ProductStockReservation::active()
    ->forProduct($productId)
    ->sum('quantity');
```

If that number plus the visible quantity equals your total, expired reservations are still active.

**Fixes:**

1. Run the cleanup command: `php artisan inventory:cleanup-reservations`.
2. Verify the artisan schedule is actually running: `php artisan schedule:list`.
3. If the schedule has never run, see [Installation](./installation.md#schedule-the-inventory-cleanup-job).

## Stock count drifts away from physical inventory

This is what `product_inventory_movements` was built to debug. Reconcile by:

```php
$expectedStock = ProductInventoryMovement::forProduct($productId)
    ->sum('quantity_change');

$actualStock = (int) $product->getContentData('qty');

if ($expectedStock !== $actualStock) {
    \Log::warning("Stock drift: product {$productId} expected={$expectedStock} actual={$actualStock}");
}
```

If you find drift, the most common causes:
1. **Direct `\DB::table('content_data')->update(['field_value' => $qty])`** bypassing `InventoryService::adjust()`. Add the missing audit movement manually.
2. **Stale `ProductInventoryMovement::TYPE_INITIAL` row** from seeding — the seeder ran with a different initial quantity than the products were created with.
3. **Failed transactions** that committed the movement but not the qty (or vice versa) — wrap in `DB::transaction()`.

## Pricing rule doesn't apply

Walk down the gates in order:

1. **Rule active?** `is_active=1` AND `disabled_at IS NULL`.
2. **In date window?** `valid_from <= now() <= valid_to`. NULL on either side means "open-ended".
3. **Usage cap reached?** `usage_count < max_usage_count` (NULL `max_usage_count` = unlimited).
4. **Applies to this product?** Check `product_ids`/`excluded_product_ids` JSON columns. Empty `product_ids` means "all products" — common gotcha.
5. **Applies to this category?** Same check on `category_ids`/`excluded_category_ids`.
6. **Applies to this customer/group?** For `customer_specific` / `customer_group` types.
7. **Quantity tier matched?** `getTierForQuantity()` returns null if no tier matches.

```php
$rule = ProductPricingRule::find($id);

dump([
    'currently_valid'      => $rule->isCurrentlyValid(),
    'applies_to_product'   => $rule->appliesToProduct($productId),
    'applies_to_customer'  => $rule->appliesToCustomer($userId, $groupId),
    'has_reached_limit'    => $rule->hasReachedLimit($userId),
    'tier_for_quantity'    => $rule->getTierForQuantity($qty),
]);
```

## `AdvancedPricingService` returns stale price after I update a rule

Cache TTL is 3600s. Flush:

```bash
php artisan cache:clear
```

Or programmatically:

```php
app(\Modules\Product\Services\AdvancedPricingService::class)->flushCache();
```

If you change rules often, consider lowering the TTL — edit `AdvancedPricingService.php` and reduce the `cacheTtl` property.

## Customer-specific price not applied even though the row exists

`ProductCustomerPricing` precedence checks:

1. `is_active=1`?
2. `valid_from <= now() AND (valid_to IS NULL OR valid_to >= now())`?
3. `minimum_quantity <= $cartQty`?
4. The customer is **authenticated** when the cart is computed? `$customerId` MUST be set when calling `calculatePrice()`. Anonymous cart sessions never see customer-specific pricing.

## Soft-deleted products still appear in search

Microweber's search component may pre-cache or store its own index. After bulk-deleting:

```bash
php artisan cache:clear
# If you use a dedicated search index (Algolia/Meilisearch/etc.):
php artisan scout:flush "Modules\Product\Models\Product"
php artisan scout:import "Modules\Product\Models\Product"
```

## Filament product form: "Save" silently fails

Check the browser console + Filament's exception log:

1. **Validation error on variant combinations** — invalid SKU (duplicate within product), missing required price, etc. Filament Toggle the Variants tab and look for red borders.
2. **`PriceValidator` rejection** — a price field has a negative value.
3. **Wire prop collision** — if you've customised `ProductResource` and added a property that clashes with Filament internals (`$record`, `$form`), Livewire fails silently.

Enable Filament's debug toolbar (`app/Providers/Filament/AdminPanelProvider.php` → `->profile()->debug()`) for a clearer error trail.

## Filament import duplicates products

`ProductImporter` uses the SKU column as the lookup key for `updateOrCreate`. If your CSV has empty SKU cells, every row is treated as a new product.

**Fix:** populate the SKU column for every row, even if it's `AUTO-{slug}`. The model's `boot()` doesn't auto-generate SKUs — that's intentional, but it bites here.

## Variant images don't render on the public-facing product page

The `image` column on `product_variants_combinations` stores a URL or `media` ID — NOT a relative path. Common mistakes:

- Storing `tshirt-red.jpg` instead of `/userfiles/uploads/tshirt-red.jpg`.
- Storing the original image URL when the system was expecting an `images` table ID.

Check what your template expects:

```bash
grep -rn "variants.*image" Templates/
```

If the template renders with `<img src="{{ $variant->image }}">`, full URLs work. If it uses `mw_image($variant->image)`, the URL gets prefixed automatically.

## Low-stock notifications not sending

1. **No admin users have an email address** — `LowStockNotification` defaults to mail channel. `\App\Models\User::where('is_admin', 1)->whereNotNull('email')->count()` should be > 0.
2. **Threshold not configured** — `low_stock_threshold` is NULL on the product. The service falls back to `InventoryService::DEFAULT_LOW_STOCK_THRESHOLD` (10), but if your stock dropped from 50 → 3 in a single sale, the system MAY have missed the threshold-crossing event — check `product_inventory_alerts` to confirm.
3. **Queue not running** — notifications are typically queued. `php artisan queue:work`.

## REST API `/api/product` returns 401

Sanctum + `is_admin` are required for write operations on `/api/product`. The public list endpoint is `/api/module/products`. Use the right one:

- `/api/product` — admin-scoped, requires Sanctum token + `is_admin=1`.
- `/api/module/products` — public, list + show only; write needs token but not admin scope.

## "Class 'Modules\Product\Models\Product' not found" after upgrading

1. `composer dump-autoload` — Microweber modules use PSR-4 and the autoload map can go stale.
2. Verify `composer.json` in `Modules/Product/` is intact and the project's root `composer.json` references the module loader.
3. `php artisan optimize:clear` to flush the bootstrap caches.

## Where to file bugs

Use the same routing matrix as the [Shop module troubleshooting](/modules/shop/troubleshooting.md#where-to-file-bugs). Product-specific cases:

| Symptom | File against |
|---|---|
| Product save/load/scope issues | **Product module** (`Modules/Product/`) |
| Variant attribute/value/combination | **Product module** |
| Pricing rule logic / customer pricing | **Product module** |
| Inventory drift / reservation expiry | **Product module** |
| Cart-side "out of stock" errors | First check Product (reservations); if not stale, file against **Cart module** |
| Order-side stock commit failures | **Product module** (`UpdateInventoryOnOrderPaid` listener) + cross-link Order |
| Frontend product card rendering | **Shop module** + **Template** (theme-level) |
| Image upload failures | **Media module** |
| Tax/Shipping inaccuracy on product | **Tax module** / **Shipping module** |
| Currency display | **Currency module** |
| Category navigation | **Category module** |

For bugs in the foundational Content layer that surface in Product, file against **Content** first — the fix usually belongs there.
