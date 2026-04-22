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
 * Phase-3 Insert Layout picker regression: typing a keyword in the
 * search field must narrow the visible cards to items whose title
 * contains that keyword.
 *
 * The plan phrased the invariant as "searching 'hosting' must surface
 * Pricing skin-2 and the Hosting Compare page layout". In practice
 * the picker is populated from `getTemplates('layouts', …)` — only
 * files under `layouts/templates/*` show up, so full-page layouts
 * like `hosting-compare.blade.php` (at the Bootstrap template root)
 * never appear. The only hosting-named layout skin in Bootstrap is
 * `pricing/skin-2` ("Pricing 2 - Hosting Plans"), so that's what the
 * narrowed picker must expose.
 *
 * One other detail: `titlelize()` strips dashes from the blade
 * header `name` before emitting `item.title`, so the card label
 * reads "Pricing 2   Hosting Plans" (two spaces where `-` used to
 * be). The filter check operates on `.toUpperCase().includes('HOSTING')`
 * which is unaffected by that quirk — but the test's own title-
 * containment assertion must use the unaltered substring "Hosting
 * Plans" rather than the original "Pricing 2 - Hosting Plans".
 *
 * Flow:
 *   1. Seed a Bootstrap landing page (LandingPageFactory).
 *   2. Open live-edit; open the Insert Layout picker.
 *   3. Snapshot the unfiltered card count.
 *   4. Type "hosting" into the `input.filter-layouts-input`
 *      (vue-bound to `filterKeyword`).
 *   5. Wait for Vue's filter watcher to settle.
 *   6. Assert:
 *        a. The "Pricing 2 - Hosting Plans" card (by its dash-
 *           stripped displayed title) is visible.
 *        b. Every visible card's title contains "hosting"
 *           (case-insensitive) — proves the filter actually narrowed
 *           rather than the picker showing its full list.
 *        c. The filtered count is strictly less than unfiltered.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditLayoutPickerKeywordSearchTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const KEYWORD = 'hosting';

    /**
     * Titles we expect the "hosting" keyword to surface. Matched
     * case-insensitive against the visible card title text.
     *
     * `titlelize()` strips `-` from the blade header `name`, so
     * "Pricing 2 - Hosting Plans" becomes "Pricing 2   Hosting Plans"
     * in the rendered card. Use a substring that survives that
     * transformation.
     */
    private const EXPECTED_TITLES = [
        'Hosting Plans',
    ];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function hosting_keyword_surfaces_pricing_skin_2_and_hosting_compare(): void
    {
        $landing = LandingPageFactory::make('Picker keyword search check');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->openPicker($browser);

            $unfilteredCount = $this->visibleCardCount($browser);
            $this->assertGreaterThan(
                count(self::EXPECTED_TITLES),
                $unfilteredCount,
                'Unfiltered picker must show more cards than the expected keyword hits so the keyword search has something to narrow'
            );

            $this->typeKeyword($browser, self::KEYWORD);

            $titles = $this->visibleTitles($browser);

            foreach (self::EXPECTED_TITLES as $expected) {
                $found = false;
                foreach ($titles as $title) {
                    if (stripos((string)$title, $expected) !== false) {
                        $found = true;
                        break;
                    }
                }
                $this->assertTrue(
                    $found,
                    sprintf(
                        'Keyword "%s" must surface a card whose title contains "%s"; visible titles: %s',
                        self::KEYWORD,
                        $expected,
                        json_encode($titles)
                    )
                );
            }

            // Every visible card's title must contain the keyword —
            // otherwise the "filter" is a no-op.
            foreach ($titles as $title) {
                $this->assertStringContainsStringIgnoringCase(
                    self::KEYWORD,
                    (string)$title,
                    sprintf(
                        'Every card visible under keyword "%s" must include that keyword in its title; offending title: "%s"',
                        self::KEYWORD,
                        $title
                    )
                );
            }

            $this->assertLessThan(
                $unfilteredCount,
                count($titles),
                'Keyword search must narrow the card list, not no-op'
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
        $browser->screenshot('fail-picker-keyword-no-cards');
        throw new \RuntimeException('openPicker: no cards rendered within 15s');
    }

    /**
     * Type a keyword into the picker's search box and wait for the
     * card list to stabilise (two consecutive identical reads).
     * Setting `input.value` alone doesn't fire Vue's v-model, so we
     * dispatch `input` and `change` events after setting the value.
     */
    private function typeKeyword(Browser $browser, string $keyword): void
    {
        $set = $browser->script("
            var keyword = " . json_encode($keyword) . ";
            var input = document.querySelector(
                '.mw-le-layouts-dialog input.filter-layouts-input'
            );
            if (!input) {
                input = document.querySelector(
                    '.mw-le-layouts-dialog input[type=\"search\"], '
                    + '.mw-le-layouts-dialog input[type=\"text\"]'
                );
            }
            if (!input) return 'NO_INPUT';
            input.focus();
            input.value = keyword;
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
            return 'OK';
        ");
        $this->assertSame(
            'OK',
            $set[0] ?? 'UNKNOWN',
            'Must be able to type into the picker keyword input'
        );

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
     * Return the title text of each visible card.
     *
     * @return array<int, string>
     */
    private function visibleTitles(Browser $browser): array
    {
        $res = $browser->script("
            var cards = document.querySelectorAll(
                '.mw-le-layouts-dialog .modules-list-block-item, '
                + '.mw-le-layouts-dialog .modules-list-block-item-masonry'
            );
            var titles = [];
            for (var i = 0; i < cards.length; i++) {
                var t = cards[i].querySelector(
                    '.modules-list-block-item-masonry-title, .modules-list-block-item-title'
                );
                titles.push(t ? (t.textContent || '').trim() : '');
            }
            return titles;
        ");
        return $res[0] ?? [];
    }
}
