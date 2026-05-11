<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Shop demo seeder contract.
 *
 * The `mw:shop-demo-seed` command (`Modules\Backup\Console\Commands\
 * ShopDemoSeedCommand`) creates a populated demo shop (Demo Shop
 * category + N products with picsum.photos placeholder images +
 * realistic titles/prices/descriptions + an upserted /shop page) so
 * mobile shop layouts can be verified at 390x844 without hand-clicking
 * through the admin to add products.
 *
 * Pins the load-bearing pieces of the command so future refactors
 * that drop the SUBTYPE_VALUE_MARKER (used by idempotent re-runs),
 * the catalog table, the picsum image URL pattern, the price
 * setCustomField call, the category attachment, the /shop page
 * upsert, or the service-provider registration get caught.
 */
class ShopDemoSeedCommandContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function command_file_exists_and_carries_anchor(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/ShopDemoSeedCommand.php');
        $this->assertStringContainsString('Cycle-159', $src,
            'ShopDemoSeedCommand.php MUST carry its source anchor.');
        $this->assertStringContainsString('mw:shop-demo-seed', $src,
            'ShopDemoSeedCommand.php MUST declare the canonical command '
            . 'signature `mw:shop-demo-seed`.');
    }

    #[Test]
    public function command_signature_pins_count_slug_category_no_images(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/ShopDemoSeedCommand.php');
        $this->assertMatchesRegularExpression('/--count=/', $src,
            'Command MUST expose --count option (default 6).');
        $this->assertMatchesRegularExpression('/--slug=/', $src,
            'Command MUST expose --slug option (default shop).');
        $this->assertMatchesRegularExpression('/--category=/', $src,
            'Command MUST expose --category option (default demo-shop).');
        $this->assertMatchesRegularExpression('/--no-images/', $src,
            'Command MUST expose --no-images option to skip picsum.photos '
            . 'image attach (e.g. for offline/CI runs).');
    }

    #[Test]
    public function subtype_value_marker_pins_idempotency(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/ShopDemoSeedCommand.php');
        // SUBTYPE_VALUE_MARKER is the anchor that lets re-runs find +
        // delete prior demo products instead of duplicating the catalog.
        // Dropping or renaming this marker breaks idempotency.
        $this->assertMatchesRegularExpression(
            '/SUBTYPE_VALUE_MARKER\s*=\s*[\'"]demo-shop-seed[\'"]/m',
            $src,
            'ShopDemoSeedCommand MUST declare SUBTYPE_VALUE_MARKER = '
            . '"demo-shop-seed" so re-runs are idempotent.'
        );
        // The cleanup MUST query by this marker.
        $this->assertMatchesRegularExpression(
            '/where\([\'"]subtype_value[\'"]\s*,\s*self::SUBTYPE_VALUE_MARKER\)/m',
            $src,
            'Cleanup loop MUST query by `subtype_value = SUBTYPE_VALUE_MARKER` '
            . 'so re-runs delete the prior demo products before re-seeding.'
        );
    }

    #[Test]
    public function catalog_has_at_least_six_entries(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/ShopDemoSeedCommand.php');
        // Ship 6 minimum so the default --count=6 doesn't fall back.
        $titleCount = preg_match_all('/[\'"]title[\'"]\s*=>\s*[\'"][^\'"]{6,}[\'"]/', $src, $m);
        $this->assertGreaterThanOrEqual(6, $titleCount,
            "ShopDemoSeedCommand CATALOG MUST contain at least 6 entries "
            . "(found {$titleCount}); default --count=6 depends on this.");
    }

    #[Test]
    public function price_uses_set_custom_field_with_price_type(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/ShopDemoSeedCommand.php');
        // The price MUST be set via setCustomField with type=price so
        // it lands on the custom_fields table + cascades to
        // custom_fields_values via the CustomField::save() override.
        // Bypassing this (e.g. with a direct DB::table('custom_fields')
        // insert) skips the fieldValue sync and the shop module
        // renders $0.00.
        $this->assertMatchesRegularExpression(
            '/setCustomField\(\s*\[[\s\S]{0,200}[\'"]type[\'"]\s*=>\s*[\'"]price[\'"]/m',
            $src,
            'Command MUST call setCustomField([type=>"price",...]) so '
            . 'the CustomField::save() override syncs to '
            . 'custom_fields_values. Direct DB inserts skip the sync '
            . 'and the shop module reads $0.00.'
        );
    }

    #[Test]
    public function picsum_image_url_uses_per_product_seed(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/ShopDemoSeedCommand.php');
        // Per-product seed in the picsum URL keeps each image stable
        // across re-runs but distinct between products. Without it
        // every product gets the SAME picsum image and the demo grid
        // looks broken.
        $this->assertMatchesRegularExpression(
            '/picsum\.photos\/seed\/mw-shop-demo-/m',
            $src,
            'Command MUST use picsum.photos/seed/mw-shop-demo-{id}/... '
            . 'so each product gets a stable but distinct placeholder '
            . 'image across re-runs.'
        );
    }

    #[Test]
    public function shop_page_upsert_sets_is_shop(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/ShopDemoSeedCommand.php');
        // The /shop Page upsert MUST set is_shop=1 + content body
        // containing the `<module type="shop"/>` markup so the shop
        // module renders the product grid.
        $this->assertMatchesRegularExpression(
            '/is_shop\s*=\s*1/m',
            $src,
            'Command MUST set $shop->is_shop = 1 on the /shop page.'
        );
        $this->assertMatchesRegularExpression(
            '/<module type=\\\\?"shop\\\\?"/m',
            $src,
            'Command MUST embed `<module type="shop"/>` in the page '
            . 'content so the shop module renders the product grid.'
        );
    }

    #[Test]
    public function category_attached_via_category_ids_attribute(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/ShopDemoSeedCommand.php');
        // Products MUST be attached to the category via the magic
        // category_ids setter (which fires _saveCategoriesToModel on
        // model save), not via direct CategoryItem inserts (would
        // skip cache invalidation).
        $this->assertMatchesRegularExpression(
            '/category_ids\s*=\s*\[\$category->id\]/m',
            $src,
            'Command MUST attach products to the category via the '
            . 'magic category_ids setter (CategoryTrait fires '
            . '_saveCategoriesToModel on saved()).'
        );
    }

    #[Test]
    public function command_is_registered_in_service_provider(): void
    {
        $provider = $this->read('Modules/Backup/Providers/BackupServiceProvider.php');
        $this->assertStringContainsString('ShopDemoSeedCommand::class', $provider,
            'BackupServiceProvider MUST register ShopDemoSeedCommand '
            . 'via the $this->commands([...]) array, otherwise '
            . '`php artisan list` will not surface the command.');
        $this->assertStringContainsString('use Modules\Backup\Console\Commands\ShopDemoSeedCommand;', $provider,
            'BackupServiceProvider MUST import the ShopDemoSeedCommand '
            . 'class.');
    }

    #[Test]
    public function cycle_165_products_upsert_by_slug_for_stable_ids(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/ShopDemoSeedCommand.php');

        // Idempotency hardening: build a slug → id map up front and
        // upsert in place by slug so re-runs preserve auto-increment
        // ids (a delete-then-create path drifts ids across runs and
        // breaks any persisted cart with the old ids).
        $this->assertMatchesRegularExpression('/[Cc]ycle-165/', $src,
            'ShopDemoSeedCommand.php MUST carry the idempotency anchor.');
        $this->assertMatchesRegularExpression(
            '/\$existingBySlug\s*=\s*Content::query\(\)[\s\S]{0,500}->pluck\([\'"]id[\'"]\s*,\s*[\'"]url[\'"]\)/',
            $src,
            'ShopDemoSeedCommand MUST build a `$existingBySlug` map via '
            . '`Content::query()->...->pluck(\'id\', \'url\')` so the '
            . 'foreach can upsert in place by slug.'
        );
        $this->assertMatchesRegularExpression(
            '/Product::query\(\)->find\(\$existingId\)/',
            $src,
            'ShopDemoSeedCommand foreach MUST `Product::query()->find('
            . '$existingId)` to fetch the existing row when the slug '
            . 'is already in the map (preserves the auto-increment id).'
        );
        // The delete-then-create path MUST be gone.
        $this->assertDoesNotMatchRegularExpression(
            '/Removing \{[\s\S]{0,80}prior demo product/',
            $src,
            'ShopDemoSeedCommand MUST NOT carry the '
            . '"Removing N prior demo products" delete-then-create path '
            . '— that broke id stability across re-runs.'
        );
    }

    #[Test]
    public function cycle_165_picsum_image_uses_first_or_create(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/ShopDemoSeedCommand.php');

        // Media::query()->create() would accumulate duplicate Media
        // rows across re-runs. firstOrCreate keys on
        // (rel_type, rel_id, filename) so re-runs are no-op when the
        // image is already attached.
        $this->assertMatchesRegularExpression(
            '/Media::query\(\)->firstOrCreate\(/',
            $src,
            'ShopDemoSeedCommand MUST use `Media::query()->firstOrCreate('
            . '...)` for the picsum image attach so re-runs do not '
            . 'duplicate Media rows.'
        );
    }

    #[Test]
    public function cycle_165_uses_distinct_product_slug_var(): void
    {
        $src = $this->read('Modules/Backup/Console/Commands/ShopDemoSeedCommand.php');

        // A `$slug` inner-loop variable would shadow the outer `$slug`
        // (= --slug option / shop page slug), causing the post-loop
        // `Content::query()->where('url', $slug)` lookup to fail and
        // create a new /shop page with the last product's slug +
        // timestamp on every run. Use `$productSlug` as a regression
        // guard.
        $this->assertStringContainsString('$productSlug', $src,
            'ShopDemoSeedCommand MUST use `$productSlug` for the inner-'
            . 'loop product slug — `$slug` is the OUTER variable for '
            . 'the shop page slug; shadowing it broke /shop upsert.');
        // The post-loop /shop lookup MUST use the outer $slug
        $strippedComments = preg_replace('#/\*[\s\S]*?\*/#', '', $src);
        $this->assertMatchesRegularExpression(
            '/Content::query\(\)->where\([\'"]url[\'"]\s*,\s*\$slug\)->where\([\'"]content_type[\'"]\s*,\s*[\'"]page[\'"]\)/',
            (string) $strippedComments,
            'The /shop upsert lookup MUST use the OUTER $slug variable '
            . '(the --slug option / shop page slug), not the inner-loop '
            . 'product slug.'
        );
    }
}
