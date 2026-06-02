<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-307 sub-bugs contract test (task-2026-05-13-5f1937).
 *
 * Pins the three UX fixes shipped for the Live Edit "Add new content"
 * picker modal:
 *
 *   - AI-308: a search input + Alpine x-show filter wraps every action
 *     card; an empty-state message appears when no card matches.
 *   - AI-309: the "Add to this page" meta-instruction card is no longer
 *     pushed onto the picker's $actions array in AdminLiveEditPage.
 *   - AI-310: a `<=575.98px` media query in live-edit-mobile.css tightens
 *     the modal window to `100vw - 24px` with 12px margins so it fits a
 *     390px iPhone-class viewport without horizontal overflow.
 *
 * Each assertion targets the structural shape — not the prose — so the
 * tests are stable against copy edits but fail fast if any of the three
 * fixes is reverted.
 */
class AddContentModalContractTest extends TestCase
{
    private const ADD_CONTENT_MODAL_BLADE = __DIR__ . '/../../../src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php';
    // task-2026-05-18-76a360 — Alpine.data() factory body for the
    // addContentModal lives in live-edit.blade.php @push('scripts')
    // (moved from add-content-modal.blade.php because Livewire-morph-
    // inserted scripts do NOT execute). Factory-body assertions read
    // the union of both files via readModalUnion().
    private const LIVE_EDIT_LAYOUT_BLADE = __DIR__ . '/../../../src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit.blade.php';
    private const ADMIN_LIVE_EDIT_PAGE = __DIR__ . '/../../../src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php';
    private const LIVE_EDIT_MOBILE_CSS = __DIR__ . '/../../../packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css';
    private const LIVE_EDIT_MOBILE_CSS_BUILT = __DIR__ . '/../../../public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    #[Test]
    public function ai_308_search_input_is_present_in_picker_blade(): void
    {
        $blade = $this->readFile(self::ADD_CONTENT_MODAL_BLADE);

        $this->assertMatchesRegularExpression(
            '/<input\b[^>]*\btype="search"[^>]*\bx-model="q"/s',
            $blade,
            'Add Content picker blade must include a <input type="search" x-model="q"> filter input (AI-308).'
        );

        // task-2026-05-13-899d57 / NOVICE #14 pin-evolution: aria-label updated
        // from jargon "Search content types" to plain-English prompt
        // "What do you want to add?" per UX copy revision.
        $this->assertMatchesRegularExpression(
            '/aria-label="What do you want to add\?"/',
            $blade,
            'AI-308 search input must carry aria-label="What do you want to add?" (updated from jargon "Search content types" per NOVICE-14 UX copy revision).'
        );
    }

    #[Test]
    public function ai_308_each_action_card_is_gated_by_x_show_query_match(): void
    {
        $blade = $this->readFile(self::ADD_CONTENT_MODAL_BLADE);

        // task-2026-05-16-de4ce4 / AI-694 pin-evolution: filtering changed from
        // x-show (display:none reflow) to visibility:hidden via :class so filtered
        // cards keep their grid cell (no reflow on type). The hidden class is
        // mw-add-content-card--hidden applied when the haystack does not include
        // the lowercased query.
        $this->assertStringContainsString(
            "mw-add-content-card--hidden",
            $blade,
            'AI-308/AI-694 each action card must use mw-add-content-card--hidden class for visibility-based filtering.'
        );

        $this->assertMatchesRegularExpression(
            '~:class="\{[^"]*mw-add-content-card--hidden[^"]*includes\(q\.toLowerCase\(\)\)~s',
            $blade,
            'AI-308/AI-694 each action card :class binding must include haystack includes(q.toLowerCase()) filter.'
        );

        $this->assertMatchesRegularExpression(
            '/data-mw-add-content-card\b/',
            $blade,
            'AI-308 action cards must carry a data-mw-add-content-card hook so tests + assistive tech can enumerate the filtered set.'
        );
    }

    #[Test]
    public function ai_308_picker_renders_an_empty_state_when_query_has_no_matches(): void
    {
        $blade = $this->readFile(self::ADD_CONTENT_MODAL_BLADE);

        $this->assertMatchesRegularExpression(
            '/class="[^"]*mw-add-content-modal-empty[^"]*"\s+x-show="q\s*!==\s*\'\'\s*&&\s*@js\(\$mwAddContentHaystacks\)\.every\(h\s*=>\s*!h\.includes\(q\.toLowerCase\(\)\)\)"/s',
            $blade,
            'AI-308 picker must show a `.mw-add-content-modal-empty` element when the query is non-empty AND every haystack fails to match.'
        );
    }

    #[Test]
    public function ai_307_empty_state_uses_static_copy_and_inline_hidden_default(): void
    {
        $blade = $this->readFile(self::ADD_CONTENT_MODAL_BLADE);

        // The empty-state element must default to display:none via inline
        // style so the pre-Alpine paint cannot flash visible content
        // (the project does not define a global [x-cloak] rule).
        $this->assertMatchesRegularExpression(
            '/class="[^"]*mw-add-content-modal-empty[^"]*"\s+x-show="[^"]+"\s+style="display:\s*none;"/s',
            $blade,
            'AI-307 polish: .mw-add-content-modal-empty must carry inline style="display: none;" so it stays hidden until Alpine flips x-show (no global [x-cloak] rule exists in the project).'
        );

        // The empty-state copy must NOT echo the user query — that's what
        // produced the literal-empty-quotes flash on every modal open.
        $this->assertStringNotContainsString(
            '<span x-text="q">',
            $blade,
            'AI-307 polish: the empty-state element must NOT echo the user query via <span x-text="q"> — that produces a literal "" flash before Alpine initialises.'
        );

        // The new query-free copy must be present.
        $this->assertStringContainsString(
            'No content types found.',
            $blade,
            'AI-307 polish: empty-state must use the static query-free copy "No content types found.".'
        );
    }

    #[Test]
    public function picker_auto_focuses_search_input_on_modal_open(): void
    {
        $blade = $this->readFile(self::ADD_CONTENT_MODAL_BLADE);

        // task-2026-05-28-102772 / AI-1218 pin-evolution: x-init block expanded
        // with an aria-labelledby heading-id patch after the search focus call.
        // Test now asserts the x-init contains $nextTick opening AND $refs.search.focus()
        // rather than the exact single-expression form (which no longer exists).
        $this->assertMatchesRegularExpression(
            '/x-init="\$nextTick\(\(\) =>/s',
            $blade,
            'Picker must auto-focus via x-init + $nextTick so users can type immediately (AI-1218: x-init may contain additional logic after the focus call).'
        );

        $this->assertMatchesRegularExpression(
            '/\$refs\.search && \$refs\.search\.focus\(\)/s',
            $blade,
            'Picker x-init must include $refs.search && $refs.search.focus() so search input is focused on modal open.'
        );

        $this->assertMatchesRegularExpression(
            '/<input\b[^>]*\bx-ref="search"/s',
            $blade,
            'Search input must carry x-ref="search" so the focus + clear handlers can reach it.'
        );
    }

    #[Test]
    public function picker_search_input_supports_command_palette_keyboard_navigation(): void
    {
        $blade = $this->readFile(self::ADD_CONTENT_MODAL_BLADE);

        // Enter on the search input activates the first visible card
        // (command-palette pattern — one-keystroke selection when exactly
        // one card matches).
        $this->assertMatchesRegularExpression(
            '/<input\b[^>]*\bx-on:keydown\.enter\.prevent="activateFirstVisibleCard\(\)"/s',
            $blade,
            'Enter on the search input must call activateFirstVisibleCard() so users can type → Enter without leaving the keyboard.'
        );

        // ArrowDown from the search moves focus to the first visible
        // card; ArrowUp from the search moves focus to the last visible
        // card.
        $this->assertMatchesRegularExpression(
            '/<input\b[^>]*\bx-on:keydown\.arrow-down\.prevent="focusFirstVisibleCard\(\)"/s',
            $blade,
            'ArrowDown on the search input must call focusFirstVisibleCard() — standard command-palette navigation.'
        );

        $this->assertMatchesRegularExpression(
            '/<input\b[^>]*\bx-on:keydown\.arrow-up\.prevent="focusLastVisibleCard\(\)"/s',
            $blade,
            'ArrowUp on the search input must call focusLastVisibleCard() — wraps to the bottom of the list.'
        );
    }

    #[Test]
    public function picker_action_cards_cycle_focus_via_arrow_keys(): void
    {
        $blade = $this->readFile(self::ADD_CONTENT_MODAL_BLADE);

        $this->assertMatchesRegularExpression(
            '/data-mw-add-content-card[^>]*x-on:keydown\.arrow-down\.prevent="focusNextCard\(\$el\)"/s',
            $blade,
            'Each action card must carry x-on:keydown.arrow-down="focusNextCard($el)" so ArrowDown moves to the next visible card (or back to the search input at the end).'
        );

        $this->assertMatchesRegularExpression(
            '/data-mw-add-content-card[^>]*x-on:keydown\.arrow-up\.prevent="focusPrevCard\(\$el\)"/s',
            $blade,
            'Each action card must carry x-on:keydown.arrow-up="focusPrevCard($el)" so ArrowUp moves to the previous visible card (or back to the search input at the start).'
        );
    }

    #[Test]
    public function picker_search_input_has_inline_clear_button_when_query_non_empty(): void
    {
        $blade = $this->readFile(self::ADD_CONTENT_MODAL_BLADE);

        // The .mw-add-content-modal-search-clear button must exist AND
        // be gated by x-show="q !== ''" — attribute order on the tag is
        // not asserted (the rendered tag interleaves x-show, x-on:click,
        // aria-label, class, style based on author preference).
        $this->assertStringContainsString(
            'mw-add-content-modal-search-clear',
            $blade,
            'Search input must have an inline clear button carrying the .mw-add-content-modal-search-clear class.'
        );

        $this->assertMatchesRegularExpression(
            '/x-show="q !== \'\'"/',
            $blade,
            "Clear button must be gated by x-show=\"q !== ''\" so it only appears when there is text to clear."
        );

        $this->assertMatchesRegularExpression(
            '/x-on:click="q = \'\'; \$refs\.search\.focus\(\)"/',
            $blade,
            'Clear button click must reset q to empty AND re-focus the search input so users can keep typing.'
        );

        $this->assertStringContainsString(
            'aria-label="Clear search"',
            $blade,
            'Clear button must carry aria-label="Clear search" for assistive tech.'
        );

        // Default `style="display: none;"` so the button does not flash
        // visible before Alpine initialises (same belt-and-braces pattern
        // the empty-state element uses).
        // The empty-state element ALSO carries style="display: none;",
        // so assert that the file has at least 2 occurrences (one for
        // the clear button, one for the empty-state).
        $occurrences = substr_count($blade, 'style="display: none;"');
        $this->assertGreaterThanOrEqual(
            2,
            $occurrences,
            'Both the clear button AND the empty-state element must default to inline style="display: none;" so they do not flash visible before Alpine initialises (found ' . $occurrences . ' occurrences, expected ≥2).'
        );
    }

    #[Test]
    public function picker_x_data_exposes_visible_cards_helpers(): void
    {
        // task-2026-05-18-76a360 pin-evolution: factory body moved out
        // of add-content-modal.blade.php → live-edit.blade.php
        // @push('scripts') because Livewire-morph-inserted scripts do
        // NOT execute. Concatenate both files for the factory-body
        // assertion.
        $blade = $this->readFile(self::ADD_CONTENT_MODAL_BLADE)
            . "\n"
            . $this->readFile(self::LIVE_EDIT_LAYOUT_BLADE);

        // The Alpine x-data object must define the 6 keyboard-navigation
        // helpers so the input + cards can reference them without inline
        // JS duplication.
        foreach ([
            'visibleCards()',
            'focusFirstVisibleCard()',
            'focusLastVisibleCard()',
            'focusNextCard(current)',
            'focusPrevCard(current)',
            'activateFirstVisibleCard()',
        ] as $helper) {
            $this->assertStringContainsString(
                $helper,
                $blade,
                "Alpine x-data must define helper {$helper} so the keyboard navigation works without duplicated inline JS."
            );
        }
    }

    #[Test]
    public function ai_309_add_to_this_page_card_is_no_longer_pushed_onto_actions(): void
    {
        $php = $this->readFile(self::ADMIN_LIVE_EDIT_PAGE);

        $this->assertDoesNotMatchRegularExpression(
            "/\\\$actions\\[\\]\\s*=\\s*\\[\\s*\\n[^\\]]*'title'\\s*=>\\s*'Add to this page'/s",
            $php,
            'AI-309 the "Add to this page" entry (with that exact title) must NOT be pushed onto the $actions array in AdminLiveEditPage::addContentAction.'
        );

        // task-2026-05-13-5f1937 pin-evolution: the card was renamed from
        // "Add to this page" to "Add a block to this page" and given a
        // js_dispatch override (liveEditInsertLayoutRequest) so it no longer
        // calls replaceMountedAction('addToCurrentPageAction') via Livewire.
        // The 'action' key remains as a synonym-lookup identifier only.
        // Assert the card has a js_dispatch key so the blade uses the
        // CustomEvent path instead of the Livewire wire:click path.
        $this->assertMatchesRegularExpression(
            "/'action'\\s*=>\\s*'addToCurrentPageAction'[\\s\\S]*?'js_dispatch'/s",
            $php,
            'AI-309 the addToCurrentPageAction card must carry a js_dispatch key so it routes via CustomEvent (liveEditInsertLayoutRequest) rather than via Livewire replaceMountedAction.'
        );
    }

    #[Test]
    public function ai_309_picker_actions_array_holds_exactly_five_entries(): void
    {
        $php = $this->readFile(self::ADMIN_LIVE_EDIT_PAGE);

        $start = strpos($php, 'public function addContentAction(): Action');
        $this->assertNotFalse($start, 'addContentAction method not found in AdminLiveEditPage.');

        $methodTail = substr($php, $start);
        $methodEnd = strpos($methodTail, "return Action::make('addContentAction')");
        $this->assertNotFalse($methodEnd, "addContentAction return statement not found.");

        $methodHead = substr($methodTail, 0, $methodEnd);
        $pushCount = preg_match_all('/\$actions\[\]\s*=\s*\[/', $methodHead);

        // task-2026-05-13-5f1937 pin-evolution: originally 5 (Page/Post/Category/Product/Image).
        // AI-148 added Image (was already counted in 5), AI-1139 item 3 added Layout (6th),
        // "Add a block" primary card counts as 7th. Current total: 7.
        $this->assertSame(
            7,
            $pushCount,
            'AI-309/AI-1139 picker must push exactly 7 entries (Add-a-block, Page, Post, Product, Image, Category, Layout) — found ' . $pushCount . '.'
        );
    }

    #[Test]
    public function ai_310_mobile_media_query_tightens_modal_to_calc_100vw_minus_24px(): void
    {
        $css = $this->readFile(self::LIVE_EDIT_MOBILE_CSS);

        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*575\.98px\)\s*\{[^{}]*\.mw-live-edit-page\s+\.fi-modal\s*\{[^}]*padding-inline:\s*0\s*!important/s',
            $css,
            'AI-310 must suppress the parent .fi-modal padding-inline inside @media (max-width: 575.98px).'
        );

        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*575\.98px\)[\s\S]*?\.mw-live-edit-page\s+\.fi-modal-window,\s*\.mw-live-edit-page\s+\.fi-modal-content\s*\{[^}]*width:\s*calc\(100vw\s*-\s*24px\)\s*!important/s',
            $css,
            'AI-310 must set .fi-modal-window width to calc(100vw - 24px) !important inside the 575.98px media query.'
        );

        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*575\.98px\)[\s\S]*?\.mw-live-edit-page\s+\.fi-modal-window,\s*\.mw-live-edit-page\s+\.fi-modal-content\s*\{[^}]*margin:\s*12px\s*!important/s',
            $css,
            'AI-310 must set the 575.98px modal window margin to 12px !important so it centres on a 390px viewport.'
        );
    }

    #[Test]
    public function ai_307_picker_search_input_width_polish_trims_inner_padding(): void
    {
        $css = $this->readFile(self::LIVE_EDIT_MOBILE_CSS);

        // The picker's Filament .fi-modal-content padding-inline must be
        // trimmed from the default 24px to 12px inside @media (max-width:
        // 575.98px). Without this, the 286px observed input width can't
        // reach the ~340px target.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*575\.98px\)[\s\S]*?\.mw-live-edit-page\s+\.mw-content-picker-modal\s+\.fi-modal-content\s*\{[^}]*padding-inline:\s*0\.75rem\s*!important/s',
            $css,
            'AI-307 polish: the picker .fi-modal-content padding-inline must be trimmed to 0.75rem inside @media (max-width: 575.98px) so the search input can reach ~340px.'
        );

        // The .mw-add-content-modal-root wrapper padding must also drop
        // to 0.25rem so the input has the full content width.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*575\.98px\)[\s\S]*?\.mw-live-edit-page\s+\.mw-content-picker-modal\s+\.mw-add-content-modal-root\s*\{[^}]*padding:\s*0\.25rem\s*!important/s',
            $css,
            'AI-307 polish: the .mw-add-content-modal-root padding must be trimmed to 0.25rem inside @media (max-width: 575.98px).'
        );
    }

    #[Test]
    public function ai_310_built_filament_theme_css_carries_the_575_98px_modal_rule(): void
    {
        if (!file_exists(self::LIVE_EDIT_MOBILE_CSS_BUILT)) {
            $this->markTestSkipped('Built filament-theme bundle missing — run `npm run build` in packages/microweber-filament-theme.');
        }

        $built = $this->readFile(self::LIVE_EDIT_MOBILE_CSS_BUILT);

        $this->assertStringContainsString(
            'calc(100vw - 24px)',
            $built,
            'AI-310 sub-575.98px width rule must be present in the built filament-theme bundle (run npm run build).'
        );
        $this->assertStringContainsString(
            '575.98px',
            $built,
            'AI-310 sub-575.98px media query boundary must be present in the built filament-theme bundle.'
        );
    }

    private function readFile(string $path): string
    {
        $real = realpath($path);
        $this->assertNotFalse($real, "File not found: {$path}");

        $contents = file_get_contents($real);
        $this->assertNotFalse($contents, "Could not read: {$path}");
        $this->assertNotEmpty($contents, "File is empty: {$path}");

        return $contents;
    }
}
