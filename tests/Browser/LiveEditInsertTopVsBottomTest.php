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
 * Phase-3 Insert Layout picker regression: switching between the "+
 * Insert on Top" and "+ Insert on Bottom" dispatches must place the new
 * section in the correct DOM position relative to the current layout
 * handle target.
 *
 * The editor pipeline is:
 *
 *   layout plus buttons → `insertLayoutRequestOn{Top,Bottom}` event
 *     → ListLayouts.vue sets `layoutInsertLocation = 'top'|'bottom'`
 *     → user clicks a card → `mw.app.editor.insertLayout(options, loc, target)`
 *     → `insertModule(target, 'layouts', options, loc)`
 *     → `mw.module.insert(target, 'layouts', cfg, loc)` in
 *        frontend-assets/resources/assets/core/modules.js:73+
 *
 * Inside `mw.module.insert`, when the target carries the `edit` class:
 *   - loc='top'    → action='prepend' (new section becomes first child)
 *   - loc='bottom' → action='append'  (new section becomes last child)
 *
 * With target set to `.section.edit.main-content` (the canvas's root
 * layout container), Top prepends before every existing section and
 * Bottom appends after every existing section — exactly the behavior a
 * user sees when they click the + buttons on the layout handle.
 *
 * Test flow:
 *   1. Open a fresh Bootstrap landing page in live-edit.
 *   2. Prime the layout handle on `.section.edit.main-content`.
 *   3. Insert content/skin-1 via `insertLayoutRequestOnBottom` — the
 *      anchor.
 *   4. Re-prime the layout handle on main-content.
 *   5. Insert titles/skin-1 via `insertLayoutRequestOnTop` — must end
 *      up BEFORE the anchor in the DOM.
 *   6. Re-prime the layout handle on main-content.
 *   7. Insert jumbotron/skin-1 via `insertLayoutRequestOnBottom` — must
 *      end up AFTER the anchor in the DOM.
 *   8. Assert the canvas DOM order is exactly:
 *      [titles/skin-1, content/skin-1, jumbotron/skin-1].
 *
 * This is the regression guard for "switch the dispatch, lose the
 * placement": if a future refactor collapses Top and Bottom into a
 * single code path (or swaps them), this test fails loudly.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditInsertTopVsBottomTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    /**
     * Shape: [ label, category, skin, dispatch, marker ]
     *   - dispatch: 'top' or 'bottom' (maps to insertLayoutRequestOn{X})
     *   - marker: canvas selector that identifies the inserted skin
     *
     * The order in this array is INSERTION order, not expected DOM
     * order. Expected DOM order is derived below.
     *
     * @var array<int, array{label:string, category:string, skin:string, dispatch:string, marker:string}>
     */
    private const INSERTS = [
        [
            'label' => 'content/skin-1',
            'category' => 'Content',
            'skin' => 'content/skin-1',
            'dispatch' => 'bottom',
            'marker' => 'section[field^="layout-content-skin-1-"]',
        ],
        [
            'label' => 'titles/skin-1',
            'category' => 'Titles',
            'skin' => 'titles/skin-1',
            'dispatch' => 'top',
            'marker' => 'section[field^="layout-titles-skin-1-"]',
        ],
        [
            'label' => 'jumbotron/skin-1',
            'category' => 'Jumbotron',
            'skin' => 'jumbotron/skin-1',
            'dispatch' => 'bottom',
            'marker' => 'section.mw-layout-dark-background[field^="layout-jumbotron-skin-1-"]',
        ],
    ];

    /**
     * After the sequence above, the canvas DOM must contain the three
     * layouts in this exact order:
     *
     *   1. titles/skin-1    — inserted via Top with main-content target,
     *                         so it prepended as the first child.
     *   2. content/skin-1   — the anchor (first to be inserted).
     *   3. jumbotron/skin-1 — inserted via Bottom with main-content
     *                         target, so it appended as the last child.
     */
    private const EXPECTED_ORDER = [
        'titles/skin-1',
        'content/skin-1',
        'jumbotron/skin-1',
    ];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function top_inserts_before_anchor_and_bottom_inserts_after(): void
    {
        $landing = LandingPageFactory::make('Insert top vs bottom check');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            foreach (self::INSERTS as $i => $insert) {
                $this->primeLayoutHandleOnMainContent($browser);
                $this->insertSkinVia(
                    $browser,
                    $insert['dispatch'],
                    $insert['category'],
                    $insert['skin']
                );
                $this->waitForMarker($browser, $insert['marker'], $i + 1);
            }

            $observedOrder = $this->readSectionOrderFromCanvas($browser);

            $this->assertSame(
                self::EXPECTED_ORDER,
                $observedOrder,
                'Top-vs-Bottom dispatches must place each new section on '
                . 'the correct side of the anchor: expected '
                . json_encode(self::EXPECTED_ORDER)
                . ', observed ' . json_encode($observedOrder)
            );
        });
    }

    /**
     * Dispatch `insertLayoutRequestOnTop` or `insertLayoutRequestOnBottom`,
     * then drive the picker's category filter + card click just like
     * the full-landing sibling test does.
     */
    private function insertSkinVia(
        Browser $browser,
        string $dispatch,
        string $category,
        string $skinSlug
    ): void {
        $eventName = $dispatch === 'top'
            ? 'insertLayoutRequestOnTop'
            : 'insertLayoutRequestOnBottom';

        $browser->script("
            if (window.mw && mw.app && mw.app.editor) {
                mw.app.editor.dispatch(" . json_encode($eventName) . ", null);
            }
        ");
        $browser->pause(2000);

        $dialogOpen = $browser->script("
            return document.querySelector('.mw-le-dialog-block.mw-le-layouts-dialog.active') !== null ? 1 : 0;
        ");
        if (($dialogOpen[0] ?? 0) !== 1) {
            throw new \RuntimeException(
                "insertSkinVia: picker dialog never opened for '{$skinSlug}' via '{$eventName}'"
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
            for (var j = 0; j < cards.length; j++) {
                var card = cards[j];
                var haystack = '';
                var iframe = card.querySelector('iframe.layout-preview-iframe');
                if (iframe) { haystack += (iframe.getAttribute('src') || ''); }
                var title = card.querySelector('.modules-list-block-item-masonry-title, .modules-list-block-item-title');
                if (title) { haystack += ' ' + (title.textContent || ''); }
                haystack += ' ' + (card.getAttribute('data-template') || '');

                if (haystack.toLowerCase().indexOf(skinSlug.toLowerCase()) !== -1
                    || haystack.toLowerCase().indexOf(skinSlug.replace('/', '__').toLowerCase()) !== -1) {
                    card.click();
                    return 1;
                }
            }
            return 0;
        ");

        if (($clicked[0] ?? 0) !== 1) {
            throw new \RuntimeException(
                "insertSkinVia: no card matched skin '{$skinSlug}' under category '{$category}'"
            );
        }

        $browser->pause(2500);
    }

    /**
     * Set the layout handle's active target to the canvas's
     * `.section.edit.main-content`. Without this, a second or third
     * dispatch would target whichever section was most recently
     * hovered — and Top/Bottom would be computed relative to THAT,
     * not the main-content root.
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
     * Poll the canvas for a selector for up to 15s. Separate failure
     * message per step so a break is easy to diagnose.
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
        $browser->screenshot("fail-top-vs-bottom-step-{$stepNumber}");
        throw new \RuntimeException(
            "Step {$stepNumber}: marker '{$marker}' never appeared in canvas within 15s"
        );
    }

    /**
     * Locate each skin's marker in the canvas, sort the nodes by
     * compareDocumentPosition, and return the labels in DOM order.
     *
     * A missing marker is reported as `"MISSING:<label>"` so a failing
     * assertion makes the cause obvious rather than surfacing an
     * opaque array-length mismatch.
     *
     * @return array<int, string>
     */
    private function readSectionOrderFromCanvas(Browser $browser): array
    {
        $markers = array_map(
            static fn (array $insert) => [
                'label' => $insert['label'],
                'selector' => $insert['marker'],
            ],
            self::INSERTS
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
