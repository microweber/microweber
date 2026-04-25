<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinBladeExists;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\Browser\Traits\AssertsSkinPublicSignatureRendered;
use Tests\Browser\Traits\AssertsSkinTagPersisted;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Phase-2 landing-page coverage: Ecommerce / skin-1 products grid.
 *
 * Unlike the sibling skins, `Templates/Bootstrap/.../ecommerce/skin-1`
 * wraps `<module type="shop/products">` in a PLAIN `<section>` that
 * has no `edit`, no `rel="module"`, and no
 * `field="layout-ecommerce-skin-1-…"` attribute. So the usual
 * `section.section.edit[field^="layout-…"]` detection pattern does
 * not apply here. Instead we:
 *
 *   - Detect insertion by a selector that keys on the layout's unique
 *     sidebar widget + `<module type="shop/products">` children that
 *     only this layout introduces inside `.section.edit.main-content`.
 *   - Mark the `.main-content` parent (which does have `.edit`) as
 *     `.changed` so the save pipeline picks the insertion up under the
 *     outer `field="content"` key.
 *
 * Acceptance invariant ("verify it lists real products from the DB"):
 *   1. Seed an active product via `save_content(content_type=product)`.
 *   2. After the shop/products module re-renders in the canvas, its
 *      `.mw-products-title` must carry the seeded title.
 *   3. The public page render must carry the seeded product title.
 *
 * The seeded product is deleted in a finally block even if an
 * assertion fails — the landing page itself is cascade-purged by the
 * CleansLandingTestPages trait.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditEcommerceSkin1Test extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinBladeExists;
    use AssertsSkinConsoleClean;
    use AssertsSkinPublicSignatureRendered;
    use AssertsSkinTagPersisted;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function ecommerce_skin_1_lists_real_products_from_db(): void
    {
        $this->assertSkinBladeExists('ecommerce/skin-1');

        $landing = LandingPageFactory::make('Ecommerce products grid');

        $suffix = uniqid();
        $productTitle = 'Landing Shop Product ' . $suffix;
        $productDescription = 'Seeded product body visible in the shop grid ' . $suffix;

        $productId = $this->seedActiveProduct($productTitle, $productDescription);

        try {
            $this->browse(function (Browser $browser) use ($landing, $productTitle, $productDescription) {
                $this->loginAsAdmin($browser);
                $this->openInLiveEdit($browser, $landing->pageId);

                // Plan B.3 fourth-bullet — install the in-page error guard
                // before any insert work fires JS.
                $this->installInPageErrorGuard($browser);

                $this->primeLayoutHandleOnMainContent($browser);
                $this->insertLayoutByCategory($browser, 'Ecommerce', 'ecommerce/skin-1');

                $this->waitForEcommerceSection($browser);

                $this->markMainContentChanged($browser);
                $this->saveLiveEdit($browser);

                $this->assertSkinTagPersisted($landing->pageId, 'ecommerce/skin-1');

                $this->assertCanvasLoadedProducts($browser, $productTitle);

                // Plan B.3 fourth-bullet — read insert-phase console state
                // BEFORE navigating away.
                $this->assertNoConsoleErrors($browser, 'insert phase (canvas + save)');
                $this->drainBrowserLog($browser);

                $this->assertPublicPageCarriesProduct($browser, $landing->slug, $productTitle);

                // Public-render gate runs LAST. ecommerce/skin-1 has no
                // field="layout-…" attribute on its outer section; the
                // structural fingerprint is the sidebar widget wrapper
                // that hosts the categories module — unique to this skin
                // among the Bootstrap shop layouts.
                $this->assertSkinPublicSignatureRendered(
                    $browser,
                    $landing->slug,
                    ['sidebar__widget'],
                );

                // Re-install the guard on the public window after the
                // signature visit + settle pause, then read both channels.
                $this->installInPageErrorGuard($browser);
                $browser->pause(1500);
                $this->assertNoConsoleErrors($browser, 'public render');
            });
        } finally {
            // Remove the seed product even if an assertion above failed;
            // the landing page itself is purged by the trait.
            DB::table('content')->where('id', $productId)->delete();
            DB::table('content_data')
                ->where('rel_type', 'content')
                ->where('rel_id', $productId)
                ->delete();
            DB::table('content_fields')
                ->where('rel_type', 'content')
                ->where('rel_id', $productId)
                ->delete();
        }
    }

    /**
     * Create an active, published product via `save_content()` so the
     * shop/products module will pick it up. Matches ProductFactory's
     * required column set (content_type + subtype = product).
     */
    private function seedActiveProduct(string $title, string $description): int
    {
        $id = save_content([
            'content_type' => 'product',
            'subtype' => 'product',
            'title' => $title,
            'url' => 'landing-test-product-' . substr(md5($title . microtime(true)), 0, 10),
            'description' => $description,
            'content' => '<p>' . $description . '</p>',
            'is_active' => 1,
            'is_deleted' => 0,
            'is_shop' => 0,
        ]);

        if (!$id || !is_numeric($id)) {
            throw new \RuntimeException(
                'seedActiveProduct: save_content returned a non-id value: ' . var_export($id, true)
            );
        }

        return (int)$id;
    }

    /**
     * Set mw.app.liveEdit.handles.get('layout') target to the page's
     * `.section.edit.main-content` so the Insert Layout picker's
     * fallback target resolves.
     */
    private function primeLayoutHandleOnMainContent(Browser $browser): void
    {
        $primed = $browser->script("
            if (!(window.mw && mw.app && mw.app.canvas
                && typeof mw.app.canvas.getDocument === 'function')) {
                return 'NO_CANVAS';
            }
            var doc = mw.app.canvas.getDocument();
            var target = doc.querySelector('.section.edit.main-content')
                || doc.querySelector('.section.edit[field=\"content\"]')
                || doc.querySelector('[data-layout-container]');
            if (!target) return 'NO_MAIN_CONTENT';
            if (mw.app.liveEdit && mw.app.liveEdit.handles) {
                var h = mw.app.liveEdit.handles.get('layout');
                if (h && typeof h.set === 'function') {
                    h.set(target);
                }
            }
            return 'OK';
        ");

        $this->assertSame('OK', $primed[0] ?? 'UNKNOWN',
            'Layout handle must resolve to the clean-layout main-content section');
    }

    /**
     * The ecommerce/skin-1 outer section has no stable field attribute,
     * so detect insertion by the unique `.sidebar__widget` node the
     * layout introduces (no other skin in Bootstrap ships this).
     */
    private function waitForEcommerceSection(Browser $browser): void
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sidebar = doc.querySelector('.section.edit.main-content .sidebar__widget');
                if (sidebar) return 'OK';
                return '';
            ");
            if (($res[0] ?? '') === 'OK') {
                $browser->pause(700);
                return;
            }
            $browser->pause(500);
        }
        $browser->screenshot('fail-ecommerce-section-missing');
        throw new \RuntimeException('Ecommerce section never appeared in the canvas within 15s');
    }

    /**
     * Tag the main-content parent with `.changed` — save's
     * collectData() only picks up `.edit.changed` nodes, and the
     * ecommerce layout's own outer section is not `.edit`.
     */
    private function markMainContentChanged(Browser $browser): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var mc = doc.querySelector('.section.edit.main-content');
            if (!mc) return 'NO_MAIN_CONTENT';
            mc.classList.add('changed');
            return 'OK';
        ");
        $this->assertSame('OK', $res[0] ?? 'UNKNOWN',
            'Must be able to tag .main-content as .edit.changed for save');
    }

    /**
     * Wait for the shop/products module to re-render (it loads via
     * AJAX after `mw.load_module`) and carry our seeded product title.
     */
    private function assertCanvasLoadedProducts(
        Browser $browser,
        string $expectedTitle
    ): void {
        for ($i = 0; $i < 30; $i++) {
            $state = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var mc = doc.querySelector('.section.edit.main-content');
                if (!mc) return { present: false };
                var html = mc.innerHTML || '';
                return {
                    present: true,
                    hasTitle: html.indexOf(" . json_encode($expectedTitle) . ") !== -1,
                    hasProductsModule: !!mc.querySelector(
                        '.module-shop-products, [data-type=\"shop-products\"], .mw-products-title'
                    )
                };
            ");
            $state = $state[0] ?? ['present' => false];
            if (!empty($state['hasTitle'])) {
                return;
            }
            $browser->pause(500);
        }

        $browser->screenshot('fail-ecommerce-canvas-missing-product');
        $this->fail('Canvas shop/products module never rendered the seeded product title within 15s');
    }

    /**
     * Visit the public page and assert the seeded product title is in
     * the rendered HTML. This is the authentic "site visitor view"
     * check — the shop/products grid re-renders on the public page
     * after the module shortcode is expanded server-side.
     */
    private function assertPublicPageCarriesProduct(
        Browser $browser,
        string $slug,
        string $expectedTitle
    ): void {
        $browser->visit('/' . ltrim($slug, '/'))->pause(3000);
        $source = $browser->driver->getPageSource();

        $this->assertStringContainsString(
            $expectedTitle,
            $source,
            "Public /{$slug} must render the seeded product title in the shop grid"
        );
    }
}
