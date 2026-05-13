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

        $this->assertMatchesRegularExpression(
            '/aria-label="Search content types"/',
            $blade,
            'AI-308 search input must carry aria-label="Search content types".'
        );
    }

    #[Test]
    public function ai_308_each_action_card_is_gated_by_x_show_query_match(): void
    {
        $blade = $this->readFile(self::ADD_CONTENT_MODAL_BLADE);

        $this->assertMatchesRegularExpression(
            '/x-show="q\s*===\s*\'\'\s*\|\|\s*@js\(\$mwAddContentHaystack\)\.includes\(q\.toLowerCase\(\)\)"/',
            $blade,
            'AI-308 each action card must be gated by x-show with a case-insensitive substring match on the haystack of its title + description.'
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
    public function ai_309_add_to_this_page_card_is_no_longer_pushed_onto_actions(): void
    {
        $php = $this->readFile(self::ADMIN_LIVE_EDIT_PAGE);

        $this->assertDoesNotMatchRegularExpression(
            "/\\\$actions\\[\\]\\s*=\\s*\\[\\s*\\n[^\\]]*'title'\\s*=>\\s*'Add to this page'/s",
            $php,
            'AI-309 the "Add to this page" entry must NOT be pushed onto the $actions array in AdminLiveEditPage::addContentAction.'
        );

        $this->assertDoesNotMatchRegularExpression(
            "/'action'\\s*=>\\s*'addToCurrentPageAction'/",
            $php,
            'AI-309 no picker action may route to addToCurrentPageAction — the meta-instruction card was removed.'
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

        $this->assertSame(
            5,
            $pushCount,
            'AI-309 picker must push exactly 5 entries (Page, Post, Category, Product, Image) — found ' . $pushCount . '.'
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
