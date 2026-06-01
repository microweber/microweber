<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Phase-2 landing-page coverage: reload invariant for the full 9-section
 * build.
 *
 * The sibling per-skin tests each cover a single insertion in isolation.
 * This one is the integration guard: after inserting every section
 * Phase-2 names and saving, reloading the live-edit admin view must
 * show the same 9 sections in the same order. If the save pipeline
 * drops, reorders, or merges sections during the round-trip, this test
 * is the backstop.
 *
 * "9 sections" mirrors Phase-2's list (in order):
 *     1. Jumbotron / skin-1
 *     2. Titles / skin-1
 *     3. Features / skin-2
 *     4. Content / skin-1
 *     5. Pricing / skin-2
 *     6. Pricing / skin-3
 *     7. Blog / skin-1
 *     8. E-commerce / skin-1
 *     9. Footers / skin-1
 *
 * After save, each `section.edit.safe-mode[field="layout-…-<id>"]` is
 * rewritten by Parser::make_tags into a `<module template="…/skin-N" …>`
 * shortcode. On reload, the live-edit view re-renders the page, each
 * shortcode expands back into its layout blade, and the full section
 * (including its CSS class markers) re-emerges in the canvas DOM. The
 * test keys off that: each skin has a stable marker selector (the
 * `field=` prefix for skins whose blade exposes it, the re-emerging
 * class for skins whose class marker is unique, and `.sidebar__widget`
 * for ecommerce/skin-1 — which has no `.edit` outer section or field
 * attribute at all).
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditFullLandingPageTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    /**
     * Expected section order after save + reload, plus the picker
     * args needed to insert them and the selector that detects each
     * one in the canvas DOM.
     *
     * @var array<int, array{category:string, skin:string, label:string, marker:string}>
     */
    private const SECTIONS = [
        [
            'category' => 'Jumbotron',
            'skin' => 'jumbotron/skin-1',
            'label' => 'jumbotron/skin-1',
            'marker' => 'section.mw-layout-dark-background[field^="layout-jumbotron-skin-1-"]',
        ],
        [
            'category' => 'Titles',
            'skin' => 'titles/skin-1',
            'label' => 'titles/skin-1',
            'marker' => 'section[field^="layout-titles-skin-1-"]',
        ],
        [
            'category' => 'Features',
            'skin' => 'features/skin-2',
            'label' => 'features/skin-2',
            'marker' => 'section.features-skin-2-advantages[field^="layout-features-skin-2-"]',
        ],
        [
            'category' => 'Content',
            'skin' => 'content/skin-1',
            'label' => 'content/skin-1',
            'marker' => 'section[field^="layout-content-skin-1-"]',
        ],
        [
            'category' => 'Pricing',
            'skin' => 'pricing/skin-2',
            'label' => 'pricing/skin-2',
            'marker' => 'section.pricing-skin-2[field^="layout-pricing-skin-2-"]',
        ],
        [
            'category' => 'Pricing',
            'skin' => 'pricing/skin-3',
            'label' => 'pricing/skin-3',
            'marker' => 'section.pricing-skin-3[field^="layout-pricing-skin-3-"]',
        ],
        [
            'category' => 'Blog',
            'skin' => 'blog/skin-1',
            'label' => 'blog/skin-1',
            'marker' => 'section[field^="layout-blog-skin-1-"]',
        ],
        [
            'category' => 'Ecommerce',
            'skin' => 'ecommerce/skin-1',
            'label' => 'ecommerce/skin-1',
            // The ecommerce skin-1 outer section has no .edit and no
            // field attribute, so key on the unique .sidebar__widget
            // node it introduces. For DOM-order purposes the widget's
            // position inside its section is a valid stand-in.
            'marker' => '.sidebar__widget',
        ],
        [
            'category' => 'Footers',
            'skin' => 'footers/skin-1',
            'label' => 'footers/skin-1',
            'marker' => 'section.footer-background[field^="layout-footer-skin-1-"]',
        ],
    ];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function nine_sections_reload_in_expected_order(): void
    {
        $landing = LandingPageFactory::make('Full landing reload');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            // Insert each skin in order; re-prime the layout handle
            // between inserts so the picker's fallback resolves even
            // if the admin-side Vue state drifted. We use a
            // multi-insert-aware helper instead of the single-shot
            // trait method because the picker's `filterCategory` is
            // preserved across opens — on the 2nd+ insert, clicking
            // a different category triggers a Vue watcher and the
            // card list rebuilds asynchronously, so the original
            // helper's synchronous card scan sees stale DOM. The
            // local helper adds a pause between category click and
            // card scan.
            foreach (self::SECTIONS as $i => $section) {
                $this->primeLayoutHandleOnMainContent($browser);
                $this->insertSkinWithCategoryWait($browser, $section['category'], $section['skin']);
                $this->waitForMarker($browser, $section['marker'], $i + 1);
            }

            // Mark every newly-inserted .edit subtree under
            // main-content as .changed so save's collectData() picks
            // everything up in a single pass.
            $this->markAllEditsChanged($browser);
            $this->saveLiveEdit($browser);

            // Reload the live-edit URL from scratch — that's the
            // invariant the plan is actually asserting.
            $this->openInLiveEdit($browser, $landing->pageId);

            $observedOrder = $this->readSectionOrderFromCanvas($browser);

            $expectedOrder = array_map(
                static fn (array $s) => $s['label'],
                self::SECTIONS
            );

            $this->assertSame(
                $expectedOrder,
                $observedOrder,
                'All 9 sections must re-appear in the canvas in the original insertion order after reload'
            );
        });
    }

    /**
     * Open the Insert Layout picker, switch category (waiting for
     * Vue's filter watcher to settle), then click the matching card.
     *
     * The `filterCategory` reactive data on ListLayouts.vue is
     * preserved across dispatches of `insertLayoutRequestOnBottom`
     * (the event only flips `showModal`, not `filterCategory`). So
     * on the 2nd+ picker open, clicking a different category LI
     * kicks off an async filterLayouts() → DOM-update cycle we have
     * to wait out before scanning cards.
     */
    private function insertSkinWithCategoryWait(
        Browser $browser,
        string $category,
        string $skinSlug
    ): void {
        $browser->script("
            if (window.mw && mw.app && mw.app.editor) {
                mw.app.editor.dispatch('insertLayoutRequestOnBottom', null);
            }
        ");
        $browser->pause(2000);

        $dialogOpen = $browser->script("
            return document.querySelector('.mw-le-dialog-block.mw-le-layouts-dialog.active') !== null ? 1 : 0;
        ");
        if (($dialogOpen[0] ?? 0) !== 1) {
            throw new \RuntimeException(
                "insertSkinWithCategoryWait: picker dialog never opened for '{$skinSlug}'"
            );
        }

        $browser->script("
            var wantCat = " . json_encode(strtolower($category)) . ";
            var catLinks = document.querySelectorAll(
                '.mw-le-layouts-dialog .modules-list-categories li'
            );
            for (var i = 0; i < catLinks.length; i++) {
                var label = (catLinks[i].textContent || '').trim().toLowerCase();
                if (label === wantCat) {
                    catLinks[i].click();
                    break;
                }
            }
        ");
        $browser->pause(1200);

        $clicked = $browser->script("
            var skinSlug = " . json_encode($skinSlug) . ";
            var cards = document.querySelectorAll(
                '.mw-le-layouts-dialog .modules-list-block-item, '
                + '.mw-le-layouts-dialog .modules-list-block-item-masonry'
            );
            var tokens = skinSlug.toLowerCase().split(/[-_\\/]+/).filter(Boolean);
            for (var j = 0; j < cards.length; j++) {
                var card = cards[j];
                var haystack = '';
                var iframe = card.querySelector('iframe.layout-preview-iframe');
                if (iframe) { haystack += (iframe.getAttribute('src') || ''); }
                var title = card.querySelector('.modules-list-block-item-masonry-title, .modules-list-block-item-title');
                if (title) { haystack += ' ' + (title.textContent || ''); }
                haystack += ' ' + (card.getAttribute('data-template') || '');
                haystack += ' ' + (card.getAttribute('aria-label') || '');

                var lower = haystack.toLowerCase();
                if (lower.indexOf(skinSlug.toLowerCase()) !== -1
                    || lower.indexOf(skinSlug.replace('/', '__').toLowerCase()) !== -1) {
                    card.click();
                    return 1;
                }
                var normalized = ' ' + lower.replace(/[^a-z0-9]+/g, ' ').replace(/\\s+/g, ' ').trim() + ' ';
                var allTokensMatch = tokens.length > 0 && tokens.every(function (t) {
                    return normalized.indexOf(' ' + t + ' ') !== -1;
                });
                if (allTokensMatch) {
                    card.click();
                    return 1;
                }
            }
            return 0;
        ");

        if (($clicked[0] ?? 0) !== 1) {
            throw new \RuntimeException(
                "insertSkinWithCategoryWait: no card matched skin '{$skinSlug}' under category '{$category}'"
            );
        }

        $browser->pause(2500);
    }

    /**
     * Set mw.app.liveEdit.handles.get('layout') target to the page's
     * `.section.edit.main-content`.
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
     * Poll the canvas for the marker selector for up to 15s. Used both
     * after each insert (to prove the section materialised) and for
     * better failure output (we know which step in the 9-step chain
     * fell over).
     */
    private function waitForMarker(Browser $browser, string $marker, int $stepNumber): void
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                return doc.querySelector(" . json_encode($marker) . ") ? 1 : 0;
            ");
            if (($res[0] ?? 0) === 1) {
                $browser->pause(500);
                return;
            }
            $browser->pause(500);
        }
        $browser->screenshot("fail-full-landing-step-{$stepNumber}");
        throw new \RuntimeException(
            "Step {$stepNumber}: marker '{$marker}' never appeared in the canvas within 15s"
        );
    }

    /**
     * Mark every `.edit` descendant of `.section.edit.main-content` as
     * `.changed`. collectData() only picks up `.edit.changed` nodes,
     * and with 9 inserts spanning both `field="layout-…"` sections and
     * the ecommerce skin-1 (which has no `.edit` on its own section),
     * the safest approach is a broad sweep rooted at main-content.
     */
    private function markAllEditsChanged(Browser $browser): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var mc = doc.querySelector('.section.edit.main-content');
            if (!mc) return 'NO_MAIN_CONTENT';
            mc.classList.add('changed');
            var edits = mc.querySelectorAll('.edit');
            for (var i = 0; i < edits.length; i++) {
                edits[i].classList.add('changed');
            }
            return 'OK:' + edits.length;
        ");
        $outcome = (string)($res[0] ?? 'UNKNOWN');
        $this->assertStringStartsWith('OK', $outcome,
            'Must be able to tag .edit.changed across main-content');
    }

    /**
     * Walk every configured marker, collect its first matching node
     * from the canvas, then sort the nodes by compareDocumentPosition
     * so the returned label list reflects document order.
     *
     * A marker with no match in the canvas is surfaced as
     * `"MISSING:<label>"` so the failing assertion shows exactly
     * which section didn't come back — rather than an opaque
     * array-length mismatch.
     */
    private function readSectionOrderFromCanvas(Browser $browser): array
    {
        $markers = array_map(
            static fn (array $s) => ['label' => $s['label'], 'selector' => $s['marker']],
            self::SECTIONS
        );

        $res = $browser->script("
            var markers = " . json_encode($markers) . ";
            var doc = mw.app.canvas.getDocument();
            var found = [];
            var missing = [];
            for (var i = 0; i < markers.length; i++) {
                var el = doc.querySelector(markers[i].selector);
                if (el) {
                    found.push({ label: markers[i].label, el: el });
                } else {
                    missing.push(markers[i].label);
                }
            }
            found.sort(function (a, b) {
                var rel = a.el.compareDocumentPosition(b.el);
                if (rel & Node.DOCUMENT_POSITION_FOLLOWING) return -1;
                if (rel & Node.DOCUMENT_POSITION_PRECEDING) return 1;
                return 0;
            });
            var order = found.map(function (f) { return f.label; });
            for (var j = 0; j < missing.length; j++) {
                order.push('MISSING:' + missing[j]);
            }
            return order;
        ");

        return $res[0] ?? [];
    }
}
