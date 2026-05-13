# Product Module — Installation

The Product module ships with Microweber. On a fresh install it's already registered, migrated, and active. This page covers verification, upgrade migrations, scheduled commands, and configuration knobs.

---

## Prerequisites

- Microweber 2.x running on Laravel 11.
- The [Content module](/modules/content/) **must** be enabled — Product extends Content; without it the model can't boot.
- MySQL/MariaDB or PostgreSQL. SQLite works in development but the variant-combination pivot queries are noticeably slower.
- PHP 8.2+ (the casts use modern union types).

---

## Verify the module is registered

```bash
php artisan module:list | grep -i product
```

Expected output:

```
Product   |   Enabled   |   Order 0   |   modules/product
```

If it's missing or disabled:

```bash
php artisan module:enable Product
```

`module.json` is the manifest. The `providers` array points at `Modules\Product\Providers\ProductServiceProvider` — that's where the routes, Filament resources, observers, and command schedule are bound.

---

## Run migrations

On a fresh install:

```bash
php artisan migrate
```

Product ships with migrations for these tables:

- `product_meta_data` *(legacy, kept for backwards-compat)*
- `product_variant_attributes`
- `product_variant_attribute_values`
- `product_variants_combinations`
- `product_variant_combination_attributes` *(pivot)*
- `product_inventory_movements`
- `product_inventory_alerts`
- `product_stock_reservations`
- `product_pricing_rules`
- `product_customer_pricing`
- Column additions on `content`: `low_stock_threshold`, `reorder_point`, `reorder_quantity`
- Column additions on `product_variants_combinations`: `low_stock_threshold`, `reorder_point`, `reorder_quantity`, `last_stock_check`

If a previous install was on a pre-variant Microweber version, run with `--force` to apply only the new migrations:

```bash
php artisan migrate --force --path=Modules/Product/database/migrations
```

---

## Schedule the inventory cleanup job

The `inventory:cleanup-reservations` command releases expired stock holds (abandoned carts older than the reservation window). Wire it into your scheduler — typically `app/Console/Kernel.php`:

```php
protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule): void
{
    $schedule->command('inventory:cleanup-reservations')->everyFiveMinutes();
}
```

Then make sure the scheduler itself is running (cron or systemd):

```cron
* * * * * cd /var/www/microweber && php artisan schedule:run >> /dev/null 2>&1
```

Without this, abandoned carts will hold stock indefinitely and you'll see "out of stock" errors even when physical inventory exists.

---

## Configuration

### Reservation window

How long a cart-added item holds stock before auto-release. Defaults to 30 minutes; tune via the InventoryService constants or by editing `Modules/Product/Services/InventoryService.php`:

```php
class InventoryService implements InventoryServiceContract
{
    public const DEFAULT_RESERVATION_MINUTES = 30;
    public const DEFAULT_LOW_STOCK_THRESHOLD = 10;
    // ...
}
```

If you want to make this runtime-configurable, override the value in your own service provider:

```php
$this->app->extend(\Modules\Product\Services\InventoryService::class, function ($service) {
    $service->reservationMinutes = (int) get_option('cart_reservation_minutes', 'shop') ?: 30;
    return $service;
});
```

### Low-stock threshold

Per-product threshold lives in `content.low_stock_threshold`; the system falls back to `InventoryService::DEFAULT_LOW_STOCK_THRESHOLD` (10) when null. Set per-product via:

```php
\DB::table('content')->where('id', $productId)->update(['low_stock_threshold' => 5]);
```

Or via the Filament product form's "Inventory" tab.

### Advanced pricing cache

`AdvancedPricingService` caches resolved prices for 3600 seconds (1 hour) to avoid hitting `product_pricing_rules` on every page render. After bulk-updating rules, flush:

```bash
php artisan cache:clear
```

Or programmatically:

```php
\Cache::tags(['product_pricing'])->flush();
```

---

## Filament admin pages

Once the module is enabled, three Filament Resources show up under the **Shop** navigation group:

- **Products** — full CRUD over `content`-rows-where-`content_type='product'`. Inherits from `ContentResource` so it has the same form layout as Pages/Posts plus product-specific tabs (Inventory, Variants, SEO).
- **Product Variant Attributes** *(under "Shop Settings")* — manage the project-wide attributes + values (Size, Color, …).
- **Inventory Movement** *(under "Shop Settings")* — read-only-friendly audit log. Manual adjustments can be created here.
- **Pricing Rules** *(under "Shop Settings")* — create bulk/tiered/customer-group rules.

If a resource doesn't appear, check `app/Providers/Filament/AdminPanelProvider.php` — the panel must auto-discover Resources under `Modules\Product\Filament\Resources` (it does by default).

---

## Optional: seed example data

For development, the module ships a factory + seeder pair:

```bash
php artisan db:seed --class="Modules\Product\Database\Seeders\ProductDatabaseSeeder"
```

This creates ~20 demo products with categories, variants, and randomised stock — useful for screenshots and theme testing.

---

## Verify the install

After migrating and (optionally) seeding, run the module's test suite:

```bash
./vendor/bin/phpunit --testsuite=Modules/Product
```

Or just the core resource test:

```bash
./vendor/bin/phpunit Modules/Product/Tests/ProductResourceTest.php
```

Green test suite = module is wired correctly.

---

## Where to next

- [Usage](./usage.md) — query, create, and update products programmatically.
- [API Reference](./api.md) — every service, model, and event.
- [Shop module](/modules/shop/) — orchestrator that exposes products to the public site.
