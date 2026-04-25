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
 * Phase-2 landing-page coverage: Pricing / skin-2 hosting-plans grid
 * (added in 7d388b9).
 *
 * The skin renders four plan cards — Start, Plus, Turbo, Business.
 * The plan's regression invariant is: editing the "Plus" plan price
 * from "$5.99" to "$6.99" survives save_edit and appears in the
 * persisted HTML (the task-level phrasing is "saved `content_body`",
 * which in practice resolves to any of content.content,
 * content.content_body, or content_fields — see the sibling tests
 * for why the pipeline splits edits across columns).
 *
 * Flow:
 *   1. Seed a blank Bootstrap page via {@see LandingPageFactory}.
 *   2. Open in live-edit; prime the layout handle on the page's
 *      `.section.edit.main-content`.
 *   3. Insert Pricing / skin-2 via the Insert Layout picker.
 *   4. Locate the Plus card (`<h4>Plus</h4>` neighbour) and rewrite
 *      its price `<h1 class="pricing-card-title">`: swap the leading
 *      text node "$5.99" to "$6.99" while preserving the trailing
 *      `<small>/mo</small>`.
 *   5. Mark `.edit.changed` on the section + parent main-content.
 *   6. Drive save via `mw.app.canvas.getWindow().mw.drag.save()`.
 *   7. Assert canvas DOM + persisted HTML carry "$6.99" and still
 *      carry "Plus" (so we're pinning the price to the correct card,
 *      not just "some card in the grid").
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditPricingSkin2Test extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinBladeExists;
    use AssertsSkinConsoleClean;
    use AssertsSkinPublicSignatureRendered;
    use AssertsSkinTagPersisted;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const ORIGINAL_PLUS_PRICE = '$5.99';
    private const NEW_PLUS_PRICE = '$6.99';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function pricing_skin_2_plus_plan_price_edit_persists(): void
    {
        $this->assertSkinBladeExists('pricing/skin-2');

        $landing = LandingPageFactory::make('Pricing hosting grid');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            // Plan B.3 fourth-bullet — install the in-page error guard
            // before any insert work fires JS.
            $this->installInPageErrorGuard($browser);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, 'Pricing', 'pricing/skin-2');

            $field = $this->waitForPricingSection($browser);
            $this->assertNotSame('', $field,
                'Pricing section should expose a field="layout-pricing-skin-2-…" attribute after insertion');

            $this->editPlusPlanPrice($browser, self::NEW_PLUS_PRICE);
            $this->markEditFieldsChanged($browser, $field);

            $this->saveLiveEdit($browser);

            $this->assertSkinTagPersisted($landing->pageId, 'pricing/skin-2');

            $this->assertCanvasReflectsEdits($browser, $field, self::NEW_PLUS_PRICE);
            $this->assertSavedContentBodyContains($landing->pageId, self::NEW_PLUS_PRICE);

            // Plan B.3 fourth-bullet — read insert-phase console state
            // BEFORE navigating away.
            $this->assertNoConsoleErrors($browser, 'insert phase (canvas + save)');
            $this->drainBrowserLog($browser);

            // Public-render gate runs LAST — it navigates away from the
            // canvas, so any canvas-touching call after this would crash.
            $this->assertSkinPublicSignatureRendered(
                $browser,
                $landing->slug,
                ['field="layout-pricing-skin-2-', 'pricing-skin-2'],
            );

            // Re-install the guard on the public window after the
            // signature visit + settle pause, then read both channels.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'public render');
        });
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

    private function waitForPricingSection(Browser $browser): string
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sec = doc.querySelector('section.section.edit[field^=\"layout-pricing-skin-2-\"]');
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
        $browser->screenshot('fail-pricing-section-missing');
        throw new \RuntimeException('Pricing section never appeared in the canvas within 15s');
    }

    /**
     * Find the "Plus" plan card by its `<h4>` heading, then walk up
     * to the card root, and rewrite the price `<h1>`'s leading text
     * node. Leaves the trailing `<small>/mo</small>` in place — the
     * edit is surgical so we can't regress the original_price-before-
     * decimal invariant.
     */
    private function editPlusPlanPrice(Browser $browser, string $newPrice): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var h4s = doc.querySelectorAll(
                'section[field^=\"layout-pricing-skin-2-\"] .card h4'
            );
            var plusCard = null;
            for (var i = 0; i < h4s.length; i++) {
                if ((h4s[i].textContent || '').trim() === 'Plus') {
                    plusCard = h4s[i].closest('.card');
                    break;
                }
            }
            if (!plusCard) return 'NO_PLUS_CARD';
            var price = plusCard.querySelector('h1.pricing-card-title');
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
            'Must be able to rewrite the Plus plan price text node');
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
            var plusCard = null;
            var h4s = sec.querySelectorAll('.card h4');
            for (var i = 0; i < h4s.length; i++) {
                if ((h4s[i].textContent || '').trim() === 'Plus') {
                    plusCard = h4s[i].closest('.card');
                    break;
                }
            }
            if (!plusCard) return { present: true, plusCard: false };
            var price = plusCard.querySelector('h1.pricing-card-title');
            return {
                present: true,
                plusCard: true,
                priceText: price ? (price.innerText || '') : ''
            };
        ");
        $state = $state[0] ?? ['present' => false];
        $this->assertTrue(!empty($state['present']), 'Pricing section must still render after save');
        $this->assertTrue(!empty($state['plusCard']), 'Plus plan card must still render after save');
        $this->assertStringContainsString(
            $expectedPrice,
            (string)$state['priceText'],
            'Canvas Plus-plan price should contain the edited value'
        );
    }

    /**
     * Assert that the Plus-plan price round-tripped through
     * /api/content/save_edit into the database. See the jumbotron /
     * titles / content siblings for why we search across
     * content.content, content.content_body, and content_fields.
     *
     * We also assert the original "$5.99" is gone AND that "Plus"
     * is still present — so we're confirming the edit landed on the
     * right card, not just anywhere in the grid.
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
            'Persisted content must carry the new Plus plan price'
        );
        $this->assertStringContainsString(
            'Plus',
            $haystack,
            'Persisted content must still carry the "Plus" plan label'
        );
        $this->assertStringNotContainsString(
            self::ORIGINAL_PLUS_PRICE,
            $haystack,
            'Persisted content must not still carry the pre-edit Plus price'
        );
        // The outer `section[field="layout-pricing-skin-2-…"]` is
        // replaced by a `<module>` shortcode during save, but the
        // shortcode keeps `template="pricing/skin-2"` — that's the
        // stable marker for "this was pricing skin-2" in the DB.
        $this->assertStringContainsString(
            'pricing/skin-2',
            $haystack,
            'Persisted content must carry the pricing/skin-2 template marker'
        );
    }
}
