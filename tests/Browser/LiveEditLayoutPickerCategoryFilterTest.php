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
 * Phase-3 Insert Layout picker regression: clicking a category in
 * the left-hand nav narrows the visible card list to only layouts
 * that declare that category in their blade header.
 *
 * The plan's concrete invariant is: "pricing category shows exactly
 * 3 cards". Bootstrap ships three Pricing skins
 * (`pricing/skin-1.blade.php`, `.../skin-2.blade.php`, `.../skin-3`)
 * each with `categories: Pricing` in the blade header comment. The
 * API reflects those strings verbatim into `item.categories`, and
 * the Vue filter in ListLayouts.vue narrows by
 * `item.categories.toUpperCase().includes(category.toUpperCase())`.
 *
 * Test flow:
 *   1. Seed a Bootstrap landing page (LandingPageFactory).
 *   2. Open live-edit + Insert Layout picker.
 *   3. Snapshot the unfiltered card count.
 *   4. Click the "Pricing" category LI.
 *   5. Wait for Vue's `filterLayouts()` watcher to settle
 *      (`layoutsListLoaded` flips false → true around the filter).
 *   6. Assert:
 *        a. Exactly 3 cards are visible.
 *        b. Every visible card's preview_url targets `pricing/skin-*`
 *           (proves the filter actually narrowed, not that the card
 *           count coincidentally matched).
 *        c. The pricing count is strictly less than the unfiltered
 *           count (proves the filter narrows rather than no-ops).
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditLayoutPickerCategoryFilterTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const PRICING_CATEGORY = 'Pricing';
    private const PRICING_EXPECTED_COUNT = 3;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function pricing_category_narrows_picker_to_exactly_three_cards(): void
    {
        $landing = LandingPageFactory::make('Picker category filter check');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->openPicker($browser);

            $unfilteredCount = $this->visibleCardCount($browser);
            $this->assertGreaterThan(
                self::PRICING_EXPECTED_COUNT,
                $unfilteredCount,
                sprintf(
                    'Unfiltered picker must show more than %d cards (observed: %d) so the category filter has something to narrow',
                    self::PRICING_EXPECTED_COUNT,
                    $unfilteredCount
                )
            );

            $this->clickCategory($browser, self::PRICING_CATEGORY);

            $templates = $this->visibleTemplates($browser);

            $this->assertCount(
                self::PRICING_EXPECTED_COUNT,
                $templates,
                sprintf(
                    '"%s" category must narrow the picker to exactly %d cards (observed %d): %s',
                    self::PRICING_CATEGORY,
                    self::PRICING_EXPECTED_COUNT,
                    count($templates),
                    json_encode($templates)
                )
            );

            // The API emits the template slug with `__` as the path
            // separator (e.g. `pricing__skin-1`) in the preview_url
            // query param — the `/`-form reappears once the picker
            // invokes mw.app.editor.insertLayout. Normalise both
            // separators to `/` before asserting the prefix.
            foreach ($templates as $template) {
                $normalised = str_replace('__', '/', strtolower((string)$template));
                $this->assertStringStartsWith(
                    'pricing/',
                    $normalised,
                    sprintf(
                        'Every card visible under "%s" must be a pricing skin; offending template: %s',
                        self::PRICING_CATEGORY,
                        $template
                    )
                );
            }

            $this->assertLessThan(
                $unfilteredCount,
                count($templates),
                'Category filter must narrow the card list, not no-op'
            );
        });
    }

    /**
     * Open the Insert Layout picker and wait for the first batch of
     * cards to render.
     */
    private function openPicker(Browser $browser): void
    {
        $browser->script("
            if (window.mw && mw.app && mw.app.editor) {
                mw.app.editor.dispatch('insertLayoutRequestOnBottom', null);
            }
        ");
        $browser->pause(2500);

        $dialogOpen = $browser->script("
            return document.querySelector('.mw-le-dialog-block.mw-le-layouts-dialog.active') !== null ? 1 : 0;
        ");
        if (($dialogOpen[0] ?? 0) !== 1) {
            throw new \RuntimeException('openPicker: picker dialog never opened');
        }

        for ($i = 0; $i < 30; $i++) {
            $count = $this->visibleCardCount($browser);
            if ($count > 0) {
                $browser->pause(800);
                return;
            }
            $browser->pause(500);
        }
        $browser->screenshot('fail-picker-category-filter-no-cards');
        throw new \RuntimeException('openPicker: no cards rendered within 15s');
    }

    /**
     * Count cards currently visible in the dialog. Covers both
     * masonry and list templates; ListLayouts.vue uses one or the
     * other depending on `layoutsListTypePreview`.
     */
    private function visibleCardCount(Browser $browser): int
    {
        $res = $browser->script("
            return document.querySelectorAll(
                '.mw-le-layouts-dialog .modules-list-block-item, '
                + '.mw-le-layouts-dialog .modules-list-block-item-masonry'
            ).length;
        ");
        return (int)($res[0] ?? 0);
    }

    /**
     * Click the category LI whose label matches (case-insensitive),
     * then wait for Vue's filter to settle. `filterCategorySubmit`
     * flips `layoutsListLoaded` false → true around the filter, but
     * the transition is synchronous — the observable signal is just
     * "the rendered card count stabilised".
     */
    private function clickCategory(Browser $browser, string $category): void
    {
        $clicked = $browser->script("
            var want = " . json_encode(strtolower($category)) . ";
            var links = document.querySelectorAll(
                '.mw-le-layouts-dialog .modules-list-categories li'
            );
            for (var i = 0; i < links.length; i++) {
                var label = (links[i].textContent || '').trim().toLowerCase();
                if (label === want) {
                    links[i].click();
                    return 1;
                }
            }
            return 0;
        ");

        if (($clicked[0] ?? 0) !== 1) {
            throw new \RuntimeException(
                "clickCategory: no category LI matched '{$category}' in the picker sidebar"
            );
        }

        // Wait for the card count to stabilise (two identical
        // readings 400ms apart). Caps out at 10s.
        $last = -1;
        $stableReads = 0;
        for ($i = 0; $i < 25; $i++) {
            $browser->pause(400);
            $now = $this->visibleCardCount($browser);
            if ($now === $last) {
                $stableReads++;
                if ($stableReads >= 2) {
                    return;
                }
            } else {
                $stableReads = 0;
                $last = $now;
            }
        }
    }

    /**
     * Extract the `template=` query param from each visible card's
     * iframe src. A card without an iframe (static screenshot) is
     * represented by `(no-iframe:<title>)` so the test can still
     * reason about the count.
     *
     * @return array<int, string>
     */
    private function visibleTemplates(Browser $browser): array
    {
        $res = $browser->script("
            function extractTemplate(src) {
                var m = (src || '').match(/[?&]template=([^&]+)/);
                if (!m) return '';
                try { return decodeURIComponent(m[1]); } catch (e) { return m[1]; }
            }
            var cards = document.querySelectorAll(
                '.mw-le-layouts-dialog .modules-list-block-item, '
                + '.mw-le-layouts-dialog .modules-list-block-item-masonry'
            );
            var templates = [];
            for (var i = 0; i < cards.length; i++) {
                // Prefer the data-template attribute bound by Vue — present on
                // both masonry and list card divs regardless of whether the
                // card renders a screenshot image (no iframe) or a live-preview
                // iframe. Parsing the iframe src is kept as a secondary fallback
                // for cards that mount an iframe before Vue sets data-template.
                var dataTpl = (cards[i].getAttribute('data-template') || '').trim();
                if (dataTpl) {
                    templates.push(dataTpl);
                    continue;
                }
                var iframe = cards[i].querySelector('iframe.layout-preview-iframe');
                var title = cards[i].querySelector('.modules-list-block-item-masonry-title, .modules-list-block-item-title');
                var titleText = title ? (title.textContent || '').trim() : '';
                if (iframe) {
                    var tpl = extractTemplate(iframe.getAttribute('src') || '');
                    templates.push(tpl || ('(no-template:' + titleText + ')'));
                } else {
                    templates.push('(no-data-template:' + titleText + ')');
                }
            }
            return templates;
        ");
        return $res[0] ?? [];
    }
}
