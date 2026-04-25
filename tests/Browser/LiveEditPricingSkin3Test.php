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
 * Phase-2 landing-page coverage: Pricing / skin-3 compare matrix
 * (added in 7d388b9).
 *
 * The skin renders a full plan-compare table with the Start / Plus /
 * Turbo / Business columns. The plan calls this "a secondary plan
 * teaser" — the acceptance invariant is "assert `pricing-skin-3`
 * marker". The marker is a class on the OUTER `<section>` tag; the
 * save pipeline replaces the section with a `<module>` shortcode so
 * the marker only re-emerges once the module re-renders, i.e. on the
 * public page. See the features-skin-2 sibling test for the same
 * pattern.
 *
 * Flow:
 *   1. Seed a blank Bootstrap page via {@see LandingPageFactory}.
 *   2. Open in live-edit; prime the layout handle on the page's
 *      `.section.edit.main-content`.
 *   3. Insert Pricing / skin-3 via the Insert Layout picker.
 *   4. Mark `.edit.changed` on the section + parent so the save
 *      pipeline picks the insert up.
 *   5. Drive save via `mw.app.canvas.getWindow().mw.drag.save()`.
 *   6. Assert canvas DOM carries the marker, persisted HTML carries
 *      `template="pricing/skin-3"` (the DB-level marker), and the
 *      public page render carries `pricing-skin-3` (the CSS marker).
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditPricingSkin3Test extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinBladeExists;
    use AssertsSkinConsoleClean;
    use AssertsSkinPublicSignatureRendered;
    use AssertsSkinTagPersisted;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const MARKER_CLASS = 'pricing-skin-3';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function pricing_skin_3_inserts_and_keeps_marker(): void
    {
        $this->assertSkinBladeExists('pricing/skin-3');

        $landing = LandingPageFactory::make('Pricing compare grid');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            // Plan B.3 fourth-bullet — install the in-page error guard
            // before any insert work fires JS.
            $this->installInPageErrorGuard($browser);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, 'Pricing', 'pricing/skin-3');

            $field = $this->waitForPricingSkin3Section($browser);
            $this->assertNotSame('', $field,
                'Pricing skin-3 section should expose a field="layout-pricing-skin-3-…" attribute after insertion');

            $this->markEditFieldsChanged($browser, $field);
            $this->saveLiveEdit($browser);

            $this->assertSkinTagPersisted($landing->pageId, 'pricing/skin-3');

            $this->assertCanvasCarriesMarker($browser, $field);
            $this->assertSavedContentBodyCarriesSkinMarker($landing->pageId);

            // Plan B.3 fourth-bullet — read insert-phase console state
            // BEFORE navigating away.
            $this->assertNoConsoleErrors($browser, 'insert phase (canvas + save)');
            $this->drainBrowserLog($browser);

            // Public-render gate runs LAST — it navigates away from the
            // canvas, so any canvas-touching call after this would crash.
            $this->assertSkinPublicSignatureRendered(
                $browser,
                $landing->slug,
                ['field="layout-pricing-skin-3-', self::MARKER_CLASS],
            );
            $this->assertPublicPageCarriesMarker($browser, $landing->slug);

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

    private function waitForPricingSkin3Section(Browser $browser): string
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sec = doc.querySelector('section.section.edit[field^=\"layout-pricing-skin-3-\"]');
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
        $browser->screenshot('fail-pricing-skin-3-section-missing');
        throw new \RuntimeException('Pricing skin-3 section never appeared in the canvas within 15s');
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

    private function assertCanvasCarriesMarker(Browser $browser, string $field): void
    {
        $state = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sec = doc.querySelector('section[field=' + JSON.stringify(" . json_encode($field) . ") + ']');
            if (!sec) return { present: false };
            return {
                present: true,
                className: sec.className,
                hasCompareTable: !!sec.querySelector('.pricing-compare-table')
            };
        ");
        $state = $state[0] ?? ['present' => false];
        $this->assertTrue(!empty($state['present']), 'Pricing skin-3 section must still render after save');
        $this->assertStringContainsString(
            self::MARKER_CLASS,
            (string)($state['className'] ?? ''),
            'Canvas section must carry the pricing-skin-3 marker class'
        );
        $this->assertTrue(
            !empty($state['hasCompareTable']),
            'Pricing skin-3 compare table must still render'
        );
    }

    /**
     * The outer `section[field="layout-pricing-skin-3-…"]` is replaced
     * by a `<module>` shortcode during save, but the shortcode keeps
     * `template="pricing/skin-3"` — that's the stable DB-level marker
     * for "this was pricing skin-3". The CSS `pricing-skin-3` class
     * marker is asserted on the public page render instead, where the
     * module has re-rendered its outer section.
     */
    private function assertSavedContentBodyCarriesSkinMarker(int $pageId): void
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
            'pricing/skin-3',
            $haystack,
            'Persisted content must carry the pricing/skin-3 template marker'
        );
    }

    /**
     * Visit the public page and assert the `pricing-skin-3` CSS
     * marker re-emerges once the module re-renders. This is the
     * right layer for the "CSS marker" invariant because
     * `Parser::make_tags` strips the outer section during save.
     */
    private function assertPublicPageCarriesMarker(Browser $browser, string $slug): void
    {
        $browser->visit('/' . ltrim($slug, '/'))->pause(2500);
        $source = $browser->driver->getPageSource();

        $this->assertStringContainsString(
            self::MARKER_CLASS,
            $source,
            "Public /{$slug} must render the pricing-skin-3 marker after save"
        );
        $this->assertStringContainsString(
            'pricing-compare-table',
            $source,
            "Public /{$slug} must render the compare-matrix table after save"
        );
    }
}
