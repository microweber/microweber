<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-14-dac0b8 — Live Edit Add Content modal improvements.
 *
 * Four scoped improvements applied to
 * `src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php`
 * on top of the existing AI-308 / AI-309 / AI-310 / bf1966 / QW1 /
 * NOVICE #14 / AI-307-polish baseline:
 *
 *   1. Clear-button touch-target floor lifted from `w-7 h-7` (28x28)
 *      to `w-11 h-11` (44x44) — matches the project-wide WCAG 2.5.5
 *      standard enforced across AI-516..AI-522.
 *   2. Escape on the search input: if `q` is non-empty, clears the
 *      search and stops propagation so Filament's modal Escape
 *      handler does NOT close the picker. If `q` is empty, lets the
 *      event bubble so the modal closes per AI-240 contract.
 *   3. `aria-live="polite"` result-count announcement (visually
 *      hidden via `.sr-only`). Reports "No results.", "1 result.",
 *      "N results.", or "All N options visible." Empty string when
 *      `q` is empty (suppresses chatter on modal open).
 *   4. Visual-only side-effect: search input `pe-10` → `pe-12` to
 *      reserve space for the larger clear button without overlap.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class AddContentModalDac0b8ContractTest extends TestCase
{
    private const MODAL = 'src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php';

    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = file_get_contents(base_path(self::MODAL));
    }

    #[Test]
    public function dac0b8_marker_present_in_docblock(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-14-dac0b8',
            $this->blade,
            'dac0b8 docblock marker must be present so future agents can trace the improvements'
        );
    }

    #[Test]
    public function clear_button_touch_target_floor_is_44x44(): void
    {
        // Tailwind `w-11 h-11` = 44px x 44px.
        $this->assertMatchesRegularExpression(
            '/aria-label="Clear search"[^>]*class="[^"]*\bw-11\b[^"]*\bh-11\b/s',
            $this->blade,
            'Clear button must use Tailwind w-11 h-11 (44x44 px) to satisfy WCAG 2.5.5 touch-target floor'
        );
        // Guard against the old 28x28 (`w-7 h-7`) regressing on the
        // clear button — it must not appear in any aria-label="Clear search"
        // class list anymore.
        $this->assertDoesNotMatchRegularExpression(
            '/aria-label="Clear search"[^>]*class="[^"]*\bw-7\b/s',
            $this->blade,
            'Clear button must not regress to w-7 (28px); use w-11 (44px) for WCAG 2.5.5'
        );
    }

    #[Test]
    public function search_input_reserves_padding_for_larger_clear_button(): void
    {
        $this->assertMatchesRegularExpression(
            '/mw-add-content-modal-search-input[^"]*\bpe-12\b/s',
            $this->blade,
            'Search input must reserve `pe-12` (48px) right padding so the 44px clear button does not overlap typed text'
        );
    }

    #[Test]
    public function escape_clears_search_when_populated_else_propagates(): void
    {
        // Escape handler must guard on `q !== ''` AND call
        // stopPropagation() so the modal-level Escape handler does
        // NOT fire when we just consumed the keystroke. If `q` is
        // empty, the conditional body never runs and the event
        // bubbles to Filament's modal handler (AI-240 contract).
        $this->assertMatchesRegularExpression(
            '/x-on:keydown\.escape="if\s*\(\s*q\s*!==\s*\x27\x27\s*\)\s*\{[^}]*q\s*=\s*\x27\x27[^}]*\$refs\.search\.focus\(\)[^}]*\$event\.stopPropagation\(\)[^}]*\}"/s',
            $this->blade,
            'Search input Escape handler must guard on q !== "", clear q, refocus, and stopPropagation — otherwise it either always-closes-modal or never-closes-modal'
        );
    }

    #[Test]
    public function aria_live_result_count_region_is_present(): void
    {
        $this->assertMatchesRegularExpression(
            '/<div\s+class="sr-only"\s+aria-live="polite"\s+aria-atomic="true"\s+x-text="resultAnnouncement\(\)"\s*>/s',
            $this->blade,
            'aria-live="polite" sr-only region with resultAnnouncement() x-text must be present for screen-reader users'
        );
    }

    #[Test]
    public function result_announcement_helper_returns_expected_strings(): void
    {
        $this->assertStringContainsString(
            "resultAnnouncement()",
            $this->blade,
            'resultAnnouncement() helper must exist on the x-data block'
        );
        $this->assertStringContainsString("return 'No results.';", $this->blade);
        $this->assertStringContainsString("return '1 result.';", $this->blade);
        $this->assertStringContainsString("return shown + ' results.';", $this->blade);
        $this->assertStringContainsString("return 'All ' + total + ' options visible.';", $this->blade);
        // Empty-q path returns '' (silent) — guard against future
        // refactors that would chat at modal open with no query.
        $this->assertMatchesRegularExpression(
            "/if\s*\(\s*this\.q\s*===\s*''\s*\)\s*return\s*''\s*;/",
            $this->blade,
            'resultAnnouncement() must return empty string when q is empty (no chatter on modal open)'
        );
    }

    #[Test]
    public function existing_baseline_contract_not_broken(): void
    {
        // Regression guards on prior shipped behaviour — none of
        // dac0b8's edits should remove these.
        $this->assertStringContainsString('x-ref="search"', $this->blade);
        $this->assertStringContainsString("x-on:keydown.enter.prevent=\"activateFirstVisibleCard()\"", $this->blade);
        $this->assertStringContainsString("x-on:keydown.arrow-down.prevent=\"focusFirstVisibleCard()\"", $this->blade);
        $this->assertStringContainsString("data-mw-add-content-card", $this->blade);
        $this->assertStringContainsString("\$mwAddContentSynonyms", $this->blade);
        $this->assertStringContainsString('No content types found.', $this->blade);
    }
}
