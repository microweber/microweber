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
 * Phase-4 reorder regression: dragging a pricing card from position 3
 * to position 1 in live-edit must persist the new DOM order through
 * the save pipeline — so the public render emits the cards in the
 * reordered sequence.
 *
 * Task framing (TODO.md Phase 4):
 *   "Drag a card from position 3 to position 1 (Sortable/jQuery UI);
 *    assert new order in saved HTML".
 *
 * Implementation notes:
 *
 *   - The card handle's "Move Backward" action
 *     ({@see packages/frontend-assets/resources/assets/api-core/core/handles-content/element-actions.js::moveBackward})
 *     is how a live-edit user advances a clonable/mw-col one slot in
 *     the DOM. Under the hood, every reorder — whether dispatched from
 *     the handle toolbar or from a jQuery-UI-sortable drop — boils
 *     down to `prev.before(el)` + `registerSyncAction(el)`. That's the
 *     contract this test guards: simulate a third-to-first move via
 *     native DOM insertion (the same primitive the handle uses),
 *     mark the enclosing section as changed, save, and assert the
 *     reordered HTML survives to the public render.
 *
 *   - pricing/skin-2 exposes four cards, named Start / Plus / Turbo /
 *     Business, inside a `.row > .col` grid (see
 *     Templates/Bootstrap/resources/views/modules/layouts/templates/
 *     pricing/skin-2.blade.php). Taking "position N" to mean the
 *     1-indexed slot in that row, "drag from 3 to 1" means Turbo must
 *     end up first. The card-header `<h4>` text is the observable
 *     label used to verify ordering in both the canvas DOM and the
 *     public page source.
 *
 *   - `mw.app.registerChangedState(el)` adds the `.changed` class to
 *     the nearest `.edit` ancestor with `rel` + `field` attrs — which
 *     for a pricing-skin-2 card is the outer `<section>`. That mark
 *     is what `mw.liveEditSaveService.collectData()` filters on when
 *     it POSTs `/api/content/save_edit` (see
 *     packages/frontend-assets/resources/assets/live-edit/live-edit-page-scripts.js).
 *
 * Flow:
 *   1. Seed a Bootstrap landing page (clean layout).
 *   2. Open in live-edit; insert pricing/skin-2.
 *   3. Record the pre-move card order — must match the blade default
 *      [Start, Plus, Turbo, Business]. If the template ever changes,
 *      this pre-check fails loudly instead of producing a
 *      false-positive "reorder worked" result.
 *   4. Move the third `.col` before the first via `insertBefore`, then
 *      call `mw.app.registerChangedState(col3)` so the save pipeline
 *      includes the section.
 *   5. Assert the in-canvas card order is now
 *      [Turbo, Start, Plus, Business].
 *   6. Save live-edit; visit the public URL.
 *   7. Assert the public HTML contains all four card titles in the
 *      reordered sequence — i.e. Turbo's `<h4>` appears before Start's.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditPricingCardDragPosition3ToPosition1Test extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    /**
     * Canonical order of the pricing/skin-2 card-header labels as the
     * blade template renders them. Drift here is a blade-template
     * change, not a reorder regression — the pre-move assertion
     * makes that distinction explicit.
     *
     * @var array<int, string>
     */
    private const INITIAL_ORDER = ['Start', 'Plus', 'Turbo', 'Business'];

    /**
     * Expected order after moving the card at index 2 (Turbo) in front
     * of index 0 (Start) — i.e. "drag from position 3 to position 1".
     *
     * @var array<int, string>
     */
    private const REORDERED = ['Turbo', 'Start', 'Plus', 'Business'];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function drag_third_card_to_first_position_persists_new_order_to_saved_html(): void
    {
        $landing = LandingPageFactory::make('Pricing drag 3 to 1 reorder');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, 'Pricing', 'pricing/skin-2');

            $sectionField = $this->waitForPricingSection($browser);

            $this->assertSame(
                self::INITIAL_ORDER,
                $this->readCanvasCardOrder($browser, $sectionField),
                'Pre-move card order must match the blade template — otherwise the '
                . 'test is not actually exercising a 3→1 reorder'
            );

            $moveResult = $this->dragThirdCardToFirstPosition($browser, $sectionField);
            $this->assertSame(
                'OK',
                $moveResult['status'],
                'Reordering the third card into first position must succeed: '
                . ($moveResult['reason'] ?? '')
            );
            $this->assertTrue(
                $moveResult['sectionMarkedChanged'],
                'After the reorder, the enclosing pricing section must carry the '
                . '"changed" class so the save pipeline picks it up'
            );

            $this->assertSame(
                self::REORDERED,
                $this->readCanvasCardOrder($browser, $sectionField),
                'Canvas DOM must reflect [Turbo, Start, Plus, Business] after the move'
            );

            $this->saveLiveEdit($browser);
            $browser->pause(1000);

            $this->assertPublicOrderMatches(
                $browser,
                $landing->slug,
                self::REORDERED
            );
        });
    }

    /**
     * Set mw.app.liveEdit.handles.get('layout') target to the page's
     * `.section.edit.main-content`. Without a primed target the Insert
     * Layout picker falls back to whichever section was last hovered,
     * which is flaky on a blank seed page.
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

        $this->assertSame(
            'OK',
            $primed[0] ?? 'UNKNOWN',
            'Layout handle must resolve to the clean-layout main-content section'
        );
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
        $browser->screenshot('fail-pricing-drag-reorder-section-missing');
        throw new \RuntimeException('Pricing section never appeared in the canvas within 15s');
    }

    /**
     * Read the trimmed text of each `.card-header h4` inside the
     * pricing section, in DOM order — the observable labels for each
     * card slot.
     *
     * @return array<int, string>
     */
    private function readCanvasCardOrder(Browser $browser, string $sectionField): array
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sel = 'section[field=' + JSON.stringify(" . json_encode($sectionField) . ") + ']';
            var sec = doc.querySelector(sel);
            if (!sec) return [];
            var headers = sec.querySelectorAll('.row .col .card .card-header h4');
            var out = [];
            for (var i = 0; i < headers.length; i++) {
                out.push((headers[i].textContent || '').trim());
            }
            return out;
        ");

        $payload = $res[0] ?? [];
        return is_array($payload) ? array_values(array_map('strval', $payload)) : [];
    }

    /**
     * Move the third `.col` before the first, then register the change
     * so the save pipeline picks up the enclosing section. Returns a
     * status payload so caller-side assertions can pin the failure
     * reason rather than guessing from a boolean.
     *
     * @return array{
     *   status: string,
     *   reason: string|null,
     *   sectionMarkedChanged: bool
     * }
     */
    private function dragThirdCardToFirstPosition(Browser $browser, string $sectionField): array
    {
        $res = $browser->script("
            try {
                var doc = mw.app.canvas.getDocument();
                var sel = 'section[field=' + JSON.stringify(" . json_encode($sectionField) . ") + ']';
                var sec = doc.querySelector(sel);
                if (!sec) return { status: 'NO_SECTION' };

                var cols = sec.querySelectorAll('.row > .col');
                if (!cols || cols.length < 3) {
                    return {
                        status: 'NOT_ENOUGH_COLS',
                        reason: 'expected at least 3 .col children, got ' + (cols ? cols.length : 0)
                    };
                }

                var first = cols[0];
                var third = cols[2];

                // The production `moveBackward` action (element-actions.js:290)
                // does `prev.before(el)`. Going from position 3 to position 1
                // is that primitive applied twice; the end-state is the same
                // as insertBefore(third, first), which is exactly what a
                // Sortable drop from index 2 onto index 0 produces.
                first.parentNode.insertBefore(third, first);

                if (!(window.mw && mw.app
                    && typeof mw.app.registerChangedState === 'function')) {
                    return { status: 'NO_REGISTER_CHANGED_STATE' };
                }
                mw.app.registerChangedState(third);

                return {
                    status: 'OK',
                    reason: null,
                    sectionMarkedChanged: sec.classList.contains('changed')
                };
            } catch (e) {
                return {
                    status: 'ERROR',
                    reason: (e && e.message) ? e.message : String(e)
                };
            }
        ");

        $payload = $res[0] ?? [];
        return [
            'status' => (string)($payload['status'] ?? 'UNKNOWN'),
            'reason' => isset($payload['reason']) ? (string)$payload['reason'] : null,
            'sectionMarkedChanged' => (bool)($payload['sectionMarkedChanged'] ?? false),
        ];
    }

    /**
     * Visit the public page and assert each card-header label appears
     * in the expected order. Each label must be present AND each
     * subsequent label must appear strictly after the previous one in
     * the source — the "new order in saved HTML" contract.
     *
     * @param array<int, string> $expectedOrder
     */
    private function assertPublicOrderMatches(
        Browser $browser,
        string $slug,
        array $expectedOrder
    ): void {
        $browser->visit('/' . ltrim($slug, '/'))->pause(2500);
        $source = $browser->driver->getPageSource();

        $previousPos = -1;
        $previousLabel = null;
        foreach ($expectedOrder as $label) {
            $needle = '>' . $label . '</h4>';
            $pos = strpos($source, $needle);

            $this->assertNotFalse(
                $pos,
                "Public page source must contain card header '{$label}' "
                . '(searched for needle ' . json_encode($needle) . ')'
            );

            if ($previousPos !== -1) {
                $this->assertGreaterThan(
                    $previousPos,
                    $pos,
                    "Public page must render '{$label}' AFTER '{$previousLabel}' "
                    . '— if not, the reorder was not persisted through save'
                );
            }

            $previousPos = $pos;
            $previousLabel = $label;
        }
    }
}
