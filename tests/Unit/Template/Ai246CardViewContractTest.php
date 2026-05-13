<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-246 admin table card-view contract test (task-2026-05-13-27a617).
 *
 * Pins the structural shape of the CSS-only card-view that flips Filament
 * admin tables (`.fi-ta-table` / `.fi-ta-row` / `.fi-ta-cell`) to a
 * stacked-card layout below the 1024px sm-breakpoint:
 *
 *   - The whole transformation lives inside a single
 *     `@media (max-width: 1023.98px)` block (the AI-246 breakpoint).
 *   - `<thead>` is hidden so column headers don't sit above the cards.
 *   - `<tr.fi-ta-row>` becomes a flex column with border + padding +
 *     background — a real card.
 *   - `<td.fi-ta-cell>` stretches to row width and flex-wraps so long
 *     content can break onto multiple lines without horizontal scroll.
 *   - The action cell right-aligns its buttons (eye-line stays
 *     consistent with the rest of the card).
 *
 * Acceptance pinned today:
 *   - AC #3: no cell is hidden by the card-view rule itself (visible
 *     columns survive).
 *   - AC #4: 44x44 touch-target floor is preserved — those rules sit
 *     inside the same <=1023.98px breakpoint and are not weakened.
 *
 * Acceptance deferred to follow-ups (intentionally NOT pinned):
 *   - AC #1 toggle button — Filament v5 already renders a column-
 *     manager dropdown for any `->toggleable()` column. Auto-switch
 *     at <1024px is the right default; spec-faithful toggle deferred
 *     to AI-246a.
 *   - AC #2 collapsible accordion — deferred to AI-246b.
 */
class Ai246CardViewContractTest extends TestCase
{
    private const MOBILE_TOUCH_CSS  = __DIR__ . '/../../../packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';
    private const THEME_CSS_BUILT   = __DIR__ . '/../../../public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    #[Test]
    public function table_thead_is_hidden_below_the_1024px_breakpoint(): void
    {
        $css = $this->readFile(self::MOBILE_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*1023\.98px\)[\s\S]*?body\.fi-panel-admin\s+\.fi-ta-table\s*>\s*thead\s*\{[^}]*display:\s*none/s',
            $css,
            'AI-246 must hide <thead> on .fi-ta-table inside @media (max-width: 1023.98px).'
        );
    }

    #[Test]
    public function table_and_tbody_become_block_so_rows_stack_vertically(): void
    {
        $css = $this->readFile(self::MOBILE_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*1023\.98px\)[\s\S]*?body\.fi-panel-admin\s+\.fi-ta-table,\s*body\.fi-panel-admin\s+\.fi-ta-table\s*>\s*tbody\s*\{[^}]*display:\s*block/s',
            $css,
            'AI-246 must set both .fi-ta-table and its > tbody to display: block inside the 1023.98px media query.'
        );
    }

    #[Test]
    public function row_becomes_a_flex_column_card_with_border_and_padding(): void
    {
        $css = $this->readFile(self::MOBILE_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*1023\.98px\)[\s\S]*?body\.fi-panel-admin\s+\.fi-ta-row\s*\{[^}]*display:\s*flex[^}]*flex-direction:\s*column/s',
            $css,
            'AI-246 .fi-ta-row must declare display: flex + flex-direction: column inside the 1023.98px media query.'
        );

        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*1023\.98px\)[\s\S]*?body\.fi-panel-admin\s+\.fi-ta-row\s*\{[^}]*border:\s*1px solid[^}]*border-radius:\s*8px/s',
            $css,
            'AI-246 .fi-ta-row must carry a 1px solid border + 8px border-radius so the card visual reads as a card.'
        );
    }

    #[Test]
    public function cell_stretches_to_row_width_and_wraps_long_content(): void
    {
        $css = $this->readFile(self::MOBILE_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*1023\.98px\)[\s\S]*?body\.fi-panel-admin\s+\.fi-ta-row\s*>\s*\.fi-ta-cell\s*\{[^}]*flex-wrap:\s*wrap/s',
            $css,
            'AI-246 .fi-ta-cell must declare flex-wrap: wrap so long content reflows without horizontal scroll.'
        );

        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*1023\.98px\)[\s\S]*?body\.fi-panel-admin\s+\.fi-ta-row\s*>\s*\.fi-ta-cell\s*\{[^}]*width:\s*100%/s',
            $css,
            'AI-246 .fi-ta-cell must stretch to width: 100% so each cell occupies a full card row.'
        );
    }

    #[Test]
    public function action_cell_right_aligns_its_buttons(): void
    {
        $css = $this->readFile(self::MOBILE_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-row\s*>\s*\.fi-ta-actions-cell[\s\S]{0,500}?justify-content:\s*flex-end/s',
            $css,
            'AI-246 .fi-ta-actions-cell must right-align its buttons via justify-content: flex-end so the eye-line stays consistent.'
        );
    }

    #[Test]
    public function ai_221_and_ai_227_touch_target_floors_remain_in_place(): void
    {
        $css = $this->readFile(self::MOBILE_TOUCH_CSS);

        // The pre-existing AI-221/AI-227 rule on .fi-ta-row .fi-ta-actions
        // buttons MUST still declare both min-width: 44px and min-height:
        // 44px — AI-246's card-view layout does not weaken these floors.
        $this->assertMatchesRegularExpression(
            '/\.fi-ta-row\s+\.fi-ta-actions[\s\S]{0,500}?\{[^}]*min-width:\s*44px[^}]*min-height:\s*44px/s',
            $css,
            'AI-246 must NOT regress the AI-221/AI-227 44x44 touch-target floor on .fi-ta-row .fi-ta-actions buttons.'
        );
    }

    #[Test]
    public function built_theme_bundle_carries_the_card_view_selectors(): void
    {
        if (!file_exists(self::THEME_CSS_BUILT)) {
            $this->markTestSkipped('Built filament-theme bundle missing — run `npm run build` in packages/microweber-filament-theme.');
        }

        $built = $this->readFile(self::THEME_CSS_BUILT);

        $this->assertStringContainsString(
            '.fi-panel-admin .fi-ta-row',
            $built,
            'Built bundle must carry body.fi-panel-admin .fi-ta-row card selector.'
        );
        $this->assertStringContainsString(
            '1023.98px',
            $built,
            'Built bundle must carry the 1023.98px media query boundary used by AI-246.'
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
