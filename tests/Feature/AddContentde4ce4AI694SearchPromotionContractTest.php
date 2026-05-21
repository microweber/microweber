<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-de4ce4 / AI-694 — Add-Content modal Slice 4:
 * Search promotion.
 *
 * Spec ref: designer-agent/output/add-content-modal-spec-2026-05-16.md
 *           §2 + §4 + §7 Slice 4.
 *
 * Designer-dispatched 2026-05-16T13:19 (in the same email that
 * accepted AI-692). Promotes the search input from "filter helper"
 * to "primary affordance" of the picker modal:
 *
 *   1. 44px min-height input (WCAG 2.5.5 touch-target floor + visual
 *      prominence).
 *   2. Auto-focus on modal open (kept from prior x-init).
 *   3. `⌘K` shortcut chip on the right edge — visual hint ONLY at
 *      this slice; global ⌘K hotkey routing intentionally deferred
 *      per designer dispatch note ("visual hint only"). Hidden on
 *      coarse-pointer / mobile breakpoints (hidden sm:inline-flex).
 *      Hidden while the input has text (clear button uses the slot).
 *   4. ENTER on search → activateFirstVisibleCard(). Zero-match
 *      fallback: activate the primary "Add a block" card so users
 *      never hit an ENTER dead-end.
 *   5. ←/→ arrow handlers added on cards as aliases for prev/next
 *      (true 2D grid navigation deferred — designer flagged ←/→
 *      "between columns" but the current DOM-order traversal in a
 *      row-major grid is close enough until grid-aware nav is
 *      explicitly requested).
 *   6. Filtered cards switch from `x-show` (display: none) to a
 *      class-bound `mw-add-content-card--hidden` (visibility:
 *      hidden) so they keep their grid cell — surrounding cards do
 *      not reflow as the user types. The CSS rule lives in
 *      live-edit-module-settings.blade.php's existing
 *      .mw-content-picker-modal style block.
 *   7. `visibleCards()` helper updated to skip both display: none
 *      AND visibility: hidden so keyboard navigation + activation
 *      treat hidden cards as absent.
 *   8. Zero-match hint "Press Enter to add a block to this page"
 *      surfaces when q is non-empty and no card matches — the
 *      visible counterpart to the primary ENTER fallback.
 *
 * Designer side-notes honoured:
 *   - No new focus animations (prefers-reduced-motion N/A for this
 *     slice's actual changes — flagged for future enhancements).
 *   - No focus-trap added inside the search input (Tab still moves
 *     search → cards → footer per native DOM order).
 *
 * Token scoping for spec-doc-nit compliance (per SOUL #108): the
 * 44px input height uses Tailwind utility `min-h-[44px]` (literal
 * value in template), NOT a project token. Rationale: the picker
 * modal lives in the Filament-portaled modal outside any ESE
 * scope; ESE `--space-xl` (4.236rem ≈ 67.8px) does not match the
 * WCAG 2.5.5 floor we want here. Pinning the literal keeps the
 * intent explicit ("44px is the WCAG floor, not a design system
 * choice").
 */
class AddContentde4ce4AI694SearchPromotionContractTest extends TestCase
{
    private string $blade;
    private string $adminStyleBlade;

    protected function setUp(): void
    {
        parent::setUp();
        // task-2026-05-18-76a360 pin-evolution: factory body moved from
        // add-content-modal.blade.php → live-edit.blade.php @push('scripts').
        // Concatenate both so AI-694 factory-body assertions
        // (activateFirstVisibleCard, visibleCount, visibility filter)
        // still find their targets in the union.
        $this->blade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php'
        )) . "\n" . (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit.blade.php'
        ));
        $this->adminStyleBlade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit-module-settings.blade.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Search input promotion (44px + ⌘K + zero-match hint)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function search_input_carries_44px_min_height(): void
    {
        // Tailwind arbitrary-value utility for the WCAG 2.5.5 floor.
        $this->assertMatchesRegularExpression(
            '/class="mw-add-content-modal-search-input[^"]*\bmin-h-\[44px\]/',
            $this->blade,
            'Search input must carry min-h-[44px] (WCAG 2.5.5 floor) per AI-694 spec §2 + §4.'
        );
    }

    #[Test]
    public function search_input_padding_right_reserves_chip_and_clear_slot(): void
    {
        // pe-16 reserves 4rem of right-padding so the ⌘K chip + clear
        // button don't overlap the typed text. Previous slice used pe-12;
        // AI-694 widens to pe-16 for the chip room.
        $this->assertMatchesRegularExpression(
            '/class="mw-add-content-modal-search-input[^"]*\bpe-16\b/',
            $this->blade,
            'Search input must use pe-16 to reserve room for the ⌘K chip + clear button slot.'
        );
    }

    #[Test]
    public function cmd_k_shortcut_chip_present_and_visual_only(): void
    {
        // The chip is a <kbd> element rendering "⌘K". aria-hidden because
        // the chip is a visual affordance hint; the actual hotkey routing
        // is deferred per designer note. Hidden on touch / coarse-pointer
        // breakpoints (hidden sm:inline-flex).
        $this->assertMatchesRegularExpression(
            '/<kbd[^>]*x-show="q === \'\'"[^>]*aria-hidden="true"[^>]*class="[^"]*mw-add-content-modal-search-shortcut[^"]*hidden sm:inline-flex[\s\S]*?⌘K/',
            $this->blade,
            '⌘K shortcut chip must be a <kbd> with x-show="q===\'\'", aria-hidden, hidden sm:inline-flex (mobile/touch hidden), with literal ⌘K text.'
        );
    }

    #[Test]
    public function zero_match_hint_renders_when_q_present_and_no_matches(): void
    {
        // The hint sits below the search input with the inline
        // style="display: none;" default (LESSONS: no global [x-cloak]).
        $this->assertMatchesRegularExpression(
            '/x-show="q !== \'\' && visibleCount\(\) === 0"[\s\S]*?class="[^"]*mw-add-content-modal-zero-match-hint/',
            $this->blade,
            'Zero-match hint must surface when q is non-empty AND visibleCount() === 0.'
        );
        $this->assertMatchesRegularExpression(
            '/mw-add-content-modal-zero-match-hint[\s\S]*?style="display: none;"/',
            $this->blade,
            'Zero-match hint must carry inline default-hidden style (no [x-cloak] reliance per LESSONS).'
        );
        $this->assertStringContainsString(
            'Press',
            $this->blade
        );
        $this->assertStringContainsString(
            'to add a block to this page.',
            $this->blade,
            'Zero-match hint copy must match designer spec.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Alpine helpers: visibility-aware filter + zero-match fallback
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function visible_cards_helper_filters_both_display_and_visibility(): void
    {
        // The old filter checked only display !== 'none'. AI-694 adds
        // visibility !== 'hidden' so cards switched to visibility:hidden
        // (no-reflow path) are correctly excluded.
        $this->assertMatchesRegularExpression(
            "/visibleCards\\(\\)\\s*\\{[\\s\\S]*?s\\.display\\s*!==\\s*'none'[\\s\\S]*?s\\.visibility\\s*!==\\s*'hidden'/s",
            $this->blade,
            'visibleCards() must filter on both display !== none AND visibility !== hidden.'
        );
    }

    #[Test]
    public function activate_first_visible_card_falls_back_to_primary_on_zero_match(): void
    {
        // Zero-match ENTER → primary card click. Uses JSON.stringify(group)
        // per the LESSONS safe-attribute-selector pattern from AI-692.
        $this->assertMatchesRegularExpression(
            "/activateFirstVisibleCard\\(\\)\\s*\\{[\\s\\S]*?else if \\(this\\.q !== ''\\)[\\s\\S]*?data-mw-add-content-group=' \\+ JSON\\.stringify\\('primary'\\)/",
            $this->blade,
            "activateFirstVisibleCard() must fall back to the primary card (data-mw-add-content-group='primary') when q is non-empty AND zero cards visible — using JSON.stringify('primary') for safe attribute-selector interpolation."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Card visibility class binding (no-reflow path)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function cards_use_class_binding_not_x_show_for_visibility(): void
    {
        // Both primary + secondary loops must use the new class-binding
        // path. Count :class bindings to .mw-add-content-card--hidden.
        $count = preg_match_all(
            "/:class=\"\\{ 'mw-add-content-card--hidden': q !== '' && !@js\\(\\\$mwAddContentHaystack\\)\\.includes\\(q\\.toLowerCase\\(\\)\\) \\}\"/",
            $this->blade,
            $m
        );
        $this->assertGreaterThanOrEqual(
            2,
            $count,
            'Both card foreach loops (primary + secondary) must bind .mw-add-content-card--hidden when q is non-empty and the haystack does not match.'
        );
    }

    #[Test]
    public function legacy_x_show_card_filter_path_removed(): void
    {
        // Regression guard: the old per-card haystack-includes x-show
        // form must NOT remain. We check for the exact literal string
        // that the old form emitted on each card — the SECTION-level
        // x-show via hasVisibleCardsInGroup is a different shape (uses
        // a helper, not a haystack-includes inline) so this assertion
        // does not collide with it.
        $this->assertStringNotContainsString(
            'x-show="q === \'\' || @js($mwAddContentHaystack).includes(q.toLowerCase())"',
            $this->blade,
            'Per-card x-show display:none path must be replaced by the class-binding visibility:hidden path.'
        );
    }

    #[Test]
    public function cards_carry_arrow_left_right_handlers_in_addition_to_up_down(): void
    {
        // AI-694 spec §4 — "←/→ between columns". Aliased to prev/next
        // pending true 2D grid navigation (deferred).
        $leftCount = substr_count($this->blade, "x-on:keydown.arrow-left.prevent=\"focusPrevCard(\$el)\"");
        $rightCount = substr_count($this->blade, "x-on:keydown.arrow-right.prevent=\"focusNextCard(\$el)\"");
        $this->assertGreaterThanOrEqual(2, $leftCount,
            'Both card loops must add x-on:keydown.arrow-left handler.');
        $this->assertGreaterThanOrEqual(2, $rightCount,
            'Both card loops must add x-on:keydown.arrow-right handler.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — CSS rule for the visibility:hidden state
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function hidden_card_css_rule_applies_visibility_hidden(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-add-content-modal-action-wrapper\.mw-add-content-card--hidden\s*\{[^}]*visibility:\s*hidden/',
            $this->adminStyleBlade,
            '.mw-add-content-card--hidden rule must apply visibility: hidden.'
        );
    }

    #[Test]
    public function hidden_card_css_rule_disables_pointer_events(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-add-content-modal-action-wrapper\.mw-add-content-card--hidden\s*\{[^}]*pointer-events:\s*none/',
            $this->adminStyleBlade,
            '.mw-add-content-card--hidden rule must include pointer-events: none so hidden cards do not intercept clicks/keyboard.'
        );
    }

    #[Test]
    public function ai694_css_block_carries_task_id_marker(): void
    {
        // The CSS rule must be flanked by an AI-694 task-id marker for
        // audit-grep coverage across blade/CSS/test surfaces.
        $start = strpos($this->adminStyleBlade, 'AI-694 (task-2026-05-16-de4ce4)');
        $this->assertNotFalse(
            $start,
            'AI-694 task-id marker must be present in the admin-side style block.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — Markers + back-compat
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function blade_carries_task_id_marker(): void
    {
        // Multiple AI-694 markers — one per substantive change point —
        // to enable single-pass grep audits.
        $count = substr_count($this->blade, 'task-2026-05-16-de4ce4');
        $this->assertGreaterThanOrEqual(
            3,
            $count,
            'add-content-modal.blade.php must carry at least 3 task-id markers (search input docblock, visibleCards comment, activateFirstVisibleCard fallback comment).'
        );
    }

    #[Test]
    public function prior_search_keyboard_contract_preserved(): void
    {
        // Regression guard — the prior task-dac0b8 keyboard handlers must
        // survive AI-694: ENTER → activateFirstVisibleCard, ESC clears,
        // arrow-down/up jump from input to first/last visible card.
        $this->assertStringContainsString(
            'x-on:keydown.enter.prevent="activateFirstVisibleCard()"',
            $this->blade
        );
        $this->assertStringContainsString(
            'x-on:keydown.arrow-down.prevent="focusFirstVisibleCard()"',
            $this->blade
        );
        $this->assertStringContainsString(
            'x-on:keydown.arrow-up.prevent="focusLastVisibleCard()"',
            $this->blade
        );
        $this->assertStringContainsString(
            "x-on:keydown.escape=\"if (q !== '') { q = ''; \$refs.search.focus(); \$event.stopPropagation(); }\"",
            $this->blade,
            'task-dac0b8 ESC-clear-then-escalate handler must survive AI-694.'
        );
    }

    #[Test]
    public function x_init_auto_focus_on_open_preserved(): void
    {
        // Auto-focus contract from the original picker — search input
        // gets focus when the modal mounts.
        $this->assertStringContainsString(
            'x-init="$nextTick(() => $refs.search && $refs.search.focus())"',
            $this->blade,
            'Auto-focus on modal open must be preserved by AI-694.'
        );
    }

    #[Test]
    public function no_block_comments_inside_xdata_attribute(): void
    {
        // LESSONS regression-guard (from task-968a71 AI-692): no `/*` may
        // appear inside the x-data attribute body — embedded `"` inside
        // a block comment terminates the HTML attribute. We slice from
        // `x-data="` to its closing `"` and assert.
        $start = strpos($this->blade, 'x-data="');
        $this->assertNotFalse($start);
        // Find the closing — x-data block ends at the `"` followed by
        // newline + whitespace + `x-init=`.
        $closeMarker = "\"\n     x-init=";
        $end = strpos($this->blade, $closeMarker, $start);
        $this->assertNotFalse($end, 'x-data block must close before x-init.');
        $xdataChunk = substr($this->blade, $start, $end - $start);
        $this->assertStringNotContainsString(
            '/*',
            $xdataChunk,
            'No /* block comment may appear inside the x-data attribute — embedded "..." in prose would terminate the HTML attribute (LESSONS 2026-05-16).'
        );
    }
}
