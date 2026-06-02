<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Hash;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use Modules\Content\Models\Content;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Support\LandingTestContentPurger;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Mirrors PostModuleAdminAndPublicRenderTest but for the Product
 * module — task-2026-04-29-c7e4f8 ("dusk tests for the post list and
 * product list module in the live edit").
 *
 * `Modules\Product\Filament\ProductsModuleSettings::getUrl()` returns
 * `/admin/products-module-settings`. live-edit's
 * openModuleSettingsAction renders this URL inside an iframe. The test
 * visits the URL directly (same DOM, no cross-frame scripting).
 *
 * Three test methods:
 *   1. `product_module_settings_page_loads_with_table` — sanity check
 *      that the lazy-loaded ContentTableList renders and the
 *      New-product CreateAction appears as a button with
 *      wire:click=mountAction('create',{},{table:true}).
 *   2. `editing_product_title_via_module_settings_persists` — seeds
 *      a product row, mounts EditAction via
 *      wire.mountTableAction('edit',recordKey), fills new title,
 *      submits, polls DB. Avoids the create flow because Products
 *      need price/category fields (more brittle than the post-module
 *      flow which only requires title).
 *   3. `created_product_renders_in_public_products_module` — seeds a
 *      product via save_content() (skipping the UI-side CreateAction
 *      that we don't drive here) and asserts it renders on a Bootstrap
 *      page that contains a `<module type="products" data-limit="1000" />`
 *      shortcode. This is the public-render half of the round trip,
 *      isolated from the (uncertain) product-create UI flow.
 *
 * Cleanup: only purges `prodmodtest-` slugs the test created.
 */
class ProductModuleAdminAndPublicRenderTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';
    private const SLUG_PREFIX = 'prodmodtest-';

    /** @var int[] */
    private array $createdIds = [];
    private string $smokeRunSlug = '';

    #[Test]
    public function product_module_settings_page_loads_with_table(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $browser->visit('/admin/products-module-settings')
                ->pause(5000);

            $browser->waitUsing(20, 250, function () use ($browser) {
                $found = $browser->script(
                    "
                    var btns = Array.from(document.querySelectorAll('button, a'));
                    return btns.some(b => {
                        var attrs = b.attributes;
                        for (var i = 0; i < attrs.length; i++) {
                            var a = attrs[i];
                            if (!a.name.startsWith('wire:click')) continue;
                            var v = a.value || '';
                            if (v.indexOf(\"mountAction('create'\") !== -1
                                && v.indexOf('table') !== -1) return true;
                        }
                        return false;
                    }) ? 1 : 0;
                "
                );
                return ($found[0] ?? 0) === 1;
            });

            $hasCreate = $browser->script(
                "
                var btns = Array.from(document.querySelectorAll('button, a'));
                var hasCreate = btns.some(b => {
                    var attrs = b.attributes;
                    for (var i = 0; i < attrs.length; i++) {
                        var a = attrs[i];
                        if (!a.name.startsWith('wire:click')) continue;
                        var v = a.value || '';
                        if (v.indexOf(\"mountAction('create'\") !== -1
                            && v.indexOf('table') !== -1) return true;
                    }
                    return false;
                });
                return hasCreate ? 1 : 0;
            "
            );
            $this->assertSame(
                1,
                $hasCreate[0] ?? 0,
                'ProductsModuleSettings page did not render the New-product create-action button. '
                . 'The ContentTableList embed is missing — the inline-edit-products flow is broken.'
            );
        });
    }

    #[Test]
    public function editing_product_title_via_module_settings_persists(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        $this->smokeRunSlug = (string)($this->smokeRunSlug ?: substr(md5(microtime(true) . Str::random(6)), 0, 10));
        $originalTitle = 'Prodmod-edit-orig ' . $this->smokeRunSlug;
        $renamedTitle = 'Prodmod-edit-renamed ' . $this->smokeRunSlug;

        $productId = save_content([
            'content_type' => 'product',
            'subtype' => 'product',
            'title' => $originalTitle,
            'is_active' => 1,
            'content' => 'smoke',
        ]);
        $this->assertIsNumeric($productId, 'save_content returned non-numeric id');
        $this->createdIds[] = (int)$productId;

        $this->browse(function (Browser $browser) use ($productId, $renamedTitle) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/products-module-settings')
                ->pause(5000);

            // Wait for the table to load.
            $browser->waitUsing(20, 250, function () use ($browser) {
                $found = $browser->script(
                    "
                    var btns = Array.from(document.querySelectorAll('button, a'));
                    return btns.some(b => {
                        var attrs = b.attributes;
                        for (var i = 0; i < attrs.length; i++) {
                            var a = attrs[i];
                            if (!a.name.startsWith('wire:click')) continue;
                            var v = a.value || '';
                            if (v.indexOf(\"mountAction('create'\") !== -1
                                && v.indexOf('table') !== -1) return true;
                        }
                        return false;
                    }) ? 1 : 0;
                "
                );
                return ($found[0] ?? 0) === 1;
            });

            // Mount EditAction directly on the ContentTableList wire
            // (avoids pagination problem).
            $mountResult = $browser->script(
                "
                return (async function () {
                    try {
                        var rec = " . json_encode((string)$productId) . ";
                        var roots = Array.from(document.querySelectorAll('[wire\\\\:id]'));
                        var target = null;
                        for (var i = 0; i < roots.length; i++) {
                            var snap = roots[i].getAttribute('wire:snapshot') || '';
                            if (snap.indexOf('contentModel') !== -1
                                && snap.indexOf('tableRecordsPerPage') !== -1) {
                                target = roots[i];
                                break;
                            }
                        }
                        if (!target) return 'NO_TABLE_WIRE';
                        var w = window.Livewire.find(target.getAttribute('wire:id'));
                        if (!w) return 'WIRE_NOT_FOUND';
                        await w.mountTableAction('edit', rec);
                        return 'OK';
                    } catch (e) {
                        return 'EXC:' + (e && e.message ? e.message : JSON.stringify(e));
                    }
                })();
            "
            );
            $browser->pause(3500);
            $this->assertSame(
                'OK',
                (string)($mountResult[0] ?? ''),
                'mountTableAction(edit) on Products ContentTableList wire failed'
            );

            $browser->waitUsing(15, 250, function () use ($browser) {
                $found = $browser->script(
                    "
                    var ok = ['callMountedAction', 'callMountedTableAction'];
                    return Array.from(document.querySelectorAll('form')).some(f => {
                        var v = f.getAttribute('wire:submit.prevent') || f.getAttribute('wire:submit');
                        return ok.indexOf(v) !== -1 && f.getBoundingClientRect().width > 0;
                    }) ? 1 : 0;
                "
                );
                return ($found[0] ?? 0) === 1;
            });

            $filled = $browser->script(
                "
                var title = " . json_encode($renamedTitle) . ";
                var pickForm = function (name) {
                    return Array.from(document.querySelectorAll('form'))
                        .filter(f => f.getBoundingClientRect().width > 0)
                        .find(f => {
                            var v = f.getAttribute('wire:submit.prevent') || f.getAttribute('wire:submit');
                            return v === name;
                        });
                };
                var form = pickForm('callMountedTableAction') || pickForm('callMountedAction');
                if (!form) return 'NO_FORM';

                var setVal = function (input, value) {
                    if (!input) return false;
                    input.focus();
                    input.value = value;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    return true;
                };

                var titleInput = null;
                var fields = form.querySelectorAll('input, textarea');
                for (var i = 0; i < fields.length; i++) {
                    var el = fields[i];
                    var attrs = el.attributes;
                    for (var j = 0; j < attrs.length; j++) {
                        var a = attrs[j];
                        if (!a.name.startsWith('wire:model')) continue;
                        if (!titleInput && /(^|\\.)title\$/.test(a.value || '')) titleInput = el;
                    }
                }
                if (!setVal(titleInput, title)) return 'NO_TITLE_INPUT';
                return 'OK';
            "
            );
            $this->assertSame('OK', (string)($filled[0] ?? ''), 'Could not fill renamed title');
            $browser->pause(900);

            $browser->script(
                "
                var pickForm = function (name) {
                    return Array.from(document.querySelectorAll('form'))
                        .filter(f => f.getBoundingClientRect().width > 0)
                        .find(f => {
                            var v = f.getAttribute('wire:submit.prevent') || f.getAttribute('wire:submit');
                            return v === name;
                        });
                };
                var form = pickForm('callMountedTableAction') || pickForm('callMountedAction');
                if (form && typeof form.requestSubmit === 'function') form.requestSubmit();
            "
            );

            $deadline = microtime(true) + 15.0;
            $found = false;
            do {
                $row = Content::where('id', $productId)->first();
                if ($row && $row->title === $renamedTitle) { $found = true; break; }
                usleep(500_000);
            } while (microtime(true) < $deadline);

            $this->assertTrue(
                $found,
                "Product id={$productId} title was not renamed within 15s. "
                . "Expected '{$renamedTitle}'."
            );
        });
    }

    #[Test]
    public function created_product_renders_in_public_products_module(): void
    {
        $this->ensureAdminUser();
        $this->ensureBootstrapActive();

        $this->smokeRunSlug = (string)($this->smokeRunSlug ?: substr(md5(microtime(true) . Str::random(6)), 0, 10));
        $productTitle = 'Prodmod-public ' . $this->smokeRunSlug;

        // Seed a minimal product. Skips the UI flow because Product
        // creation through the form likely needs price/category.
        // Verifying public render is what the user actually wants.
        // Use the Product model directly so the boot defaults (in
        // Modules/Product/Models/Product.php — content_type=product,
        // subtype=product) plus is_shop=1 fire correctly. is_shop=1
        // is required for the products module's query filter to
        // include the row in render output.
        $product = \Modules\Product\Models\Product::create([
            'title' => $productTitle,
            'is_active' => 1,
            'is_shop' => 1,
            'content' => 'smoke',
        ]);
        $productId = (int)$product->id;
        $this->assertIsNumeric($productId, 'Product seed failed');
        $this->createdIds[] = (int)$productId;

        $hostPageId = $this->createProductsHostPage();

        $publicLink = (string)content_link($hostPageId);
        $this->browse(function (Browser $browser) use ($publicLink, $productTitle) {
            $browser->visit($publicLink)->pause(2500);
            $body = (string)($browser->script("return document.body.innerHTML;")[0] ?? '');
            $this->assertStringContainsString(
                $productTitle,
                $body,
                'Product title missing from public products-module render. '
                . 'Either the products module did not render or data-limit=1000 did not include it.'
            );
        });
    }

    private function createProductsHostPage(): int
    {
        if (!$this->smokeRunSlug) {
            $this->smokeRunSlug = substr(md5(microtime(true) . Str::random(6)), 0, 10);
        }
        $pageSlug = self::SLUG_PREFIX . 'host-' . $this->smokeRunSlug;
        $title = 'Prodmod host ' . $this->smokeRunSlug;
        $moduleId = 'modulemw-prodmod-' . $this->smokeRunSlug;

        // The product module type is `shop/products` — verified by
        // grep against the Big2 ecommerce skins (e.g.
        // `<module type="shop/products" template="skin-6" />`).
        // `products` alone is not a valid module type and renders as
        // empty output, masking what would otherwise be a clean test
        // failure with a confusing "title missing" assertion message.
        $content = '<div class="edit container py-5">'
            . '<module type="shop/products" id="' . htmlspecialchars($moduleId, ENT_QUOTES) . '" '
            . 'data-limit="1000" template="default" />'
            . '</div>';

        $id = save_content([
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => $title,
            'url' => $pageSlug,
            'active_site_template' => 'Bootstrap',
            'is_active' => 1,
            'content' => $content,
        ]);

        if (!$id || !is_numeric($id)) {
            throw new \RuntimeException(
                'createProductsHostPage: save_content returned a non-id value: '
                . var_export($id, true)
            );
        }

        $this->createdIds[] = (int)$id;
        return (int)$id;
    }

    private function ensureAdminUser(): void
    {
        $user = User::where('email', self::ADMIN_EMAIL)->first();
        if (!$user) {
            $user = new User();
            $user->email = self::ADMIN_EMAIL;
            $user->username = 'admin';
            $user->password = Hash::make(self::ADMIN_PASSWORD);
            $user->is_admin = 1;
            $user->is_active = 1;
            $user->is_verified = 1;
            $user->first_name = 'Admin';
            $user->last_name = 'User';
            $user->save();
            return;
        }
        $dirty = false;
        if ((int)$user->is_admin !== 1) { $user->is_admin = 1; $dirty = true; }
        if ((int)$user->is_active !== 1) { $user->is_active = 1; $dirty = true; }
        if ($dirty) { $user->save(); }
    }

    private function ensureBootstrapActive(): void
    {
        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();

        if ($row) {
            if ($row->option_value !== 'Bootstrap') {
                DB::table('options')
                    ->where('id', $row->id)
                    ->update(['option_value' => 'Bootstrap', 'updated_at' => now()]);
            }
            return;
        }

        DB::table('options')->insert([
            'option_key' => 'current_template',
            'option_value' => 'Bootstrap',
            'option_group' => 'template',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function tearDown(): void
    {
        foreach ($this->createdIds as $id) {
            try {
                LandingTestContentPurger::purge($id);
            } catch (\Throwable $e) {}
        }
        $this->createdIds = [];
        parent::tearDown();
    }
}
