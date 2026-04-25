<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinBladeExists;
use Tests\Browser\Traits\AssertsSkinTagPersisted;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Plan B.2 — Pricing / skin-1 per-skin Dusk coverage.
 *
 * Mirrors {@see LiveEditPricingSkin2Test} for the sibling
 * `pricing/skin-1` skin. The two tests together prove both
 * Pricing skins survive the Phase-2 live-edit pipeline (insert
 * → edit → save → render).
 *
 * Skin-1 vs skin-2 differences encoded here:
 *   - 3 plans (Free / Pro / Enterprise) vs skin-2's 4
 *     (Start / Plus / Turbo / Business).
 *   - Mid-tier upgrade target is "Pro" priced "$15" (vs Plus / $5.99).
 *   - h1 price element is `<h1 class="card-title pricing-card-title">`
 *     (skin-2 adds `mb-0`, but `h1.pricing-card-title` still matches both).
 *   - field attribute: `layout-pricing-skin-1-{id}` (vs `…-skin-2-…`).
 *
 * Shared with skin-2:
 *   - `<h4>` per-plan label inside `.card`, used as the card-locator anchor.
 *   - Price string layout `"$N"` followed by `<small>/mo</small>`,
 *     so we can edit the leading text node without disturbing /mo.
 *
 * Plan B.3 contract:
 *   - Skin blade file existence checked before insert (fails early
 *     if Templates/Bootstrap/.../pricing/skin-1.blade.php is gone).
 *   - Insert persists `<module type="layouts" template="pricing/skin-1">`
 *     to content.content.
 *   - Public render carries the section with `field=layout-pricing-skin-1-*`.
 *   - No JS console error fires during insert OR public render.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditPricingSkin1Test extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinBladeExists;
    use AssertsSkinTagPersisted;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const SKIN_TAG = 'pricing/skin-1';
    private const FIELD_PREFIX = 'layout-pricing-skin-1-';
    private const TARGET_PLAN = 'Pro';
    private const ORIGINAL_PRO_PRICE = '$15';
    private const NEW_PRO_PRICE = '$16';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    #[Test]
    public function pricing_skin_1_pro_plan_price_edit_persists(): void
    {
        $this->assertSkinBladeExists(self::SKIN_TAG);

        $landing = LandingPageFactory::make('Pricing plans skin 1');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, 'Pricing', self::SKIN_TAG);

            $field = $this->waitForPricingSection($browser);
            $this->assertNotSame('', $field, sprintf(
                'Pricing section should expose a field="%s…" attribute after insertion',
                self::FIELD_PREFIX,
            ));

            $this->editProPlanPrice($browser, self::NEW_PRO_PRICE);
            $this->markEditFieldsChanged($browser, $field);

            $this->saveLiveEdit($browser);

            $this->assertSkinTagPersisted($landing->pageId, self::SKIN_TAG);

            $this->assertCanvasReflectsEdits($browser, $field, self::NEW_PRO_PRICE);
            $this->assertSavedContentBodyContains($landing->pageId, self::NEW_PRO_PRICE);
            $this->assertPublicPageCarriesMarker($browser, $landing->slug, self::NEW_PRO_PRICE);
        });
    }

    /**
     * Set mw.app.liveEdit.handles.get('layout') target to the page's
     * `.section.edit.main-content` so the Insert Layout picker's
     * fallback target resolves. Same shape as the skin-2 helper —
     * the priming logic is layout-agnostic.
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

    private function waitForPricingSection(Browser $browser): string
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sec = doc.querySelector('section.section.edit[field^=\"" . self::FIELD_PREFIX . "\"]');
                if (!sec) return '';
                return sec.getAttribute('field') || '';
            ");
            $fieldAttr = (string)($res[0] ?? '');
            if ($fieldAttr !== '') {
                $browser->pause(700);
                return $fieldAttr;
            }
            $browser->pause(500);
        }
        $browser->screenshot('fail-pricing-skin-1-section-missing');
        throw new \RuntimeException('Pricing skin-1 section never appeared in the canvas within 15s');
    }

    /**
     * Find the "Pro" plan card by its `<h4>` heading, then walk up
     * to the card root, and rewrite the price `<h1>`'s leading text
     * node. Leaves the trailing `<small>/mo</small>` in place — the
     * edit is surgical so we can't regress the price-before-/mo
     * invariant.
     */
    private function editProPlanPrice(Browser $browser, string $newPrice): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var h4s = doc.querySelectorAll(
                'section[field^=\"" . self::FIELD_PREFIX . "\"] .card h4'
            );
            var planName = " . json_encode(self::TARGET_PLAN) . ";
            var targetCard = null;
            for (var i = 0; i < h4s.length; i++) {
                if ((h4s[i].textContent || '').trim() === planName) {
                    targetCard = h4s[i].closest('.card');
                    break;
                }
            }
            if (!targetCard) return 'NO_TARGET_CARD';
            var price = targetCard.querySelector('h1.pricing-card-title');
            if (!price) return 'NO_PRICE';

            var replaced = false;
            for (var j = 0; j < price.childNodes.length; j++) {
                var node = price.childNodes[j];
                if (node.nodeType === 3 && (node.textContent || '').trim() !== '') {
                    node.textContent = " . json_encode($newPrice) . ";
                    replaced = true;
                    break;
                }
            }
            if (!replaced) return 'NO_TEXT_NODE';

            price.dispatchEvent(new Event('input', { bubbles: true }));
            price.dispatchEvent(new Event('change', { bubbles: true }));
            return 'OK';
        ");
        $this->assertSame('OK', $res[0] ?? 'UNKNOWN',
            'Must be able to rewrite the Pro plan price text node');
    }

    /**
     * Tag the pricing section and its main-content parent with
     * `.changed` — the save pipeline's collectData() only picks up
     * `.edit.changed` nodes.
     */
    private function markEditFieldsChanged(Browser $browser, string $field): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sec = doc.querySelector('section[field=' + JSON.stringify(" . json_encode($field) . ") + ']');
            if (!sec) return 'NO_SEC';
            sec.classList.add('changed');
            var p = sec.parentElement;
            while (p) {
                if (p.classList && p.classList.contains('edit')) {
                    p.classList.add('changed');
                    break;
                }
                p = p.parentElement;
            }
            return 'OK';
        ");
        $this->assertSame('OK', $res[0] ?? 'UNKNOWN', 'Must be able to tag .edit.changed for save');
    }

    private function assertCanvasReflectsEdits(
        Browser $browser,
        string $field,
        string $expectedPrice
    ): void {
        $state = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sec = doc.querySelector('section[field=' + JSON.stringify(" . json_encode($field) . ") + ']');
            if (!sec) return { present: false };
            var planName = " . json_encode(self::TARGET_PLAN) . ";
            var targetCard = null;
            var h4s = sec.querySelectorAll('.card h4');
            for (var i = 0; i < h4s.length; i++) {
                if ((h4s[i].textContent || '').trim() === planName) {
                    targetCard = h4s[i].closest('.card');
                    break;
                }
            }
            if (!targetCard) return { present: true, targetCard: false };
            var price = targetCard.querySelector('h1.pricing-card-title');
            return {
                present: true,
                targetCard: true,
                priceText: price ? (price.innerText || '') : ''
            };
        ");
        $state = $state[0] ?? ['present' => false];
        $this->assertTrue(!empty($state['present']), 'Pricing section must still render after save');
        $this->assertTrue(!empty($state['targetCard']), 'Pro plan card must still render after save');
        $this->assertStringContainsString(
            $expectedPrice,
            (string)$state['priceText'],
            'Canvas Pro-plan price should contain the edited value'
        );
    }

    /**
     * Assert that the Pro-plan price round-tripped through
     * /api/content/save_edit into the database. Same shape +
     * caveat as skin-2's assertion: the outer section is replaced
     * by a `<module>` shortcode at save time, so we look across
     * content + content_fields for the persisted copy. We also
     * assert "Pro" is still present and the original "$15" is gone
     * — so we're confirming the edit landed on the right card,
     * not just anywhere in the grid.
     */
    private function assertSavedContentBodyContains(int $pageId, string $expectedPrice): void
    {
        $content = DB::table('content')->where('id', $pageId)->first();
        $this->assertNotNull(
            $content,
            "content row for page {$pageId} must still exist after save"
        );

        $moduleFields = DB::table('content_fields')
            ->where('rel_type', 'module')
            ->pluck('value')
            ->all();
        $pageFields = DB::table('content_fields')
            ->where('rel_type', 'content')
            ->where('rel_id', $pageId)
            ->pluck('value')
            ->all();

        $haystack = implode("\n", array_filter([
            (string)($content->content ?? ''),
            (string)($content->content_body ?? ''),
            implode("\n", array_map(fn ($v) => (string)$v, $moduleFields)),
            implode("\n", array_map(fn ($v) => (string)$v, $pageFields)),
        ]));

        $this->assertNotSame(
            '',
            $haystack,
            "expected some persisted copy for page {$pageId} after save"
        );
        $this->assertStringContainsString(
            $expectedPrice,
            $haystack,
            'Persisted content must carry the new Pro plan price'
        );
        $this->assertStringContainsString(
            self::TARGET_PLAN,
            $haystack,
            'Persisted content must still carry the "Pro" plan label'
        );
        // Belt-and-braces: pricing/skin-1 also has "$15 GB" in the
        // wider page text on some skins, but in skin-1 the literal
        // "$15" only appears as the Pro plan price — so dropping it
        // from the persisted copy is a clean signal the edit landed.
        $this->assertStringNotContainsString(
            self::ORIGINAL_PRO_PRICE,
            $haystack,
            'Persisted content must not still carry the pre-edit Pro price'
        );
        // The outer `section[field="layout-pricing-skin-1-…"]` is
        // replaced by a `<module>` shortcode during save, but the
        // shortcode keeps `template="pricing/skin-1"` — that's the
        // stable marker for "this was pricing skin-1" in the DB.
        $this->assertStringContainsString(
            self::SKIN_TAG,
            $haystack,
            'Persisted content must carry the pricing/skin-1 template marker'
        );
    }

    /**
     * Visit the public page URL and assert the rendered HTML
     * carries the skin's signature: the `field=layout-pricing-skin-1-*`
     * attribute the blade emits on the outer <section>, and the
     * edited Pro price.
     */
    private function assertPublicPageCarriesMarker(
        Browser $browser,
        string $slug,
        string $expectedPrice
    ): void {
        $browser->visit('/' . ltrim($slug, '/'))->pause(2500);
        $source = $browser->driver->getPageSource();

        $this->assertStringContainsString(
            self::FIELD_PREFIX,
            $source,
            "Public /{$slug} must render the pricing/skin-1 field signature after save"
        );
        $this->assertStringContainsString(
            $expectedPrice,
            $source,
            "Public /{$slug} must render the edited Pro price after save"
        );
        $this->assertStringContainsString(
            self::TARGET_PLAN,
            $source,
            "Public /{$slug} must still render the Pro plan label after save"
        );
    }
}
