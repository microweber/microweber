<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-77fedf / AI-734 — PageChip popover anchor flip
 * needed on mobile. Jira:
 *   https://microweber.atlassian.net/browse/AI-734
 *
 * Designer dispatch 2026-05-16T15:32:22 — adjacent finding from
 * AI-701 verification.
 *
 * Problem: PageChip popover (320 px) anchors to chip-center via
 * translateX(-50%). When chip sits in toolbar's horizontally-
 * scrolled area at mobile 390 px viewport, popover extends ~136 px
 * past viewport right edge. `max-width: 90vw` is defined but
 * doesn't clamp because popover is absolute-positioned within
 * scroll container.
 *
 * Fix (Option A — designer-recommended): smart anchor flip at
 * mount + on resize. When chipRect.right + POPOVER_WIDTH/2 >
 * window.innerWidth, add class `mw-page-chip-popover--anchor-right`
 * (popover hugs chip right edge, no transform). Same pattern as
 * Floating UI / Popper.
 *
 * Acceptance: popover right edge ≤ window.innerWidth - 8 px at
 * any viewport.
 */
class LiveEdit77fedfAI734PageChipAnchorFlipContractTest extends TestCase
{
    private string $vueSrc;
    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vueSrc = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/PageChip.vue'
        ));
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Vue state + class binding
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function popover_anchor_state_initialised_centered(): void
    {
        // Default anchor 'center' preserves the existing
        // centered-below-chip behaviour for desktop + non-edge
        // mobile cases. The flip is purposeful, not always-on.
        $this->assertMatchesRegularExpression(
            "/popoverAnchor:\\s*['\"]center['\"]/",
            $this->vueSrc,
            "PageChip data() must initialise popoverAnchor: 'center' so the default centered-below-chip behaviour is preserved."
        );
    }

    #[Test]
    public function popover_class_binding_applies_anchor_right_when_flipped(): void
    {
        // Template uses :class with the 'mw-page-chip-popover--
        // anchor-right' class gated on popoverAnchor === 'right'.
        $this->assertMatchesRegularExpression(
            "/:class=\"\\{\\s*'mw-page-chip-popover--anchor-right':\\s*popoverAnchor\\s*===\\s*'right'\\s*\\}\"/",
            $this->vueSrc,
            "Popover element must bind .mw-page-chip-popover--anchor-right class on popoverAnchor === 'right'."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — computeAnchor() logic
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function compute_anchor_uses_320px_popover_width_and_8px_edge_margin(): void
    {
        // Pin the constants designer specced: 320 px popover, 8 px
        // safe gutter from viewport right.
        $this->assertMatchesRegularExpression(
            '/POPOVER_WIDTH\s*=\s*320/',
            $this->vueSrc,
            'computeAnchor() must use POPOVER_WIDTH = 320 (matches CSS width).'
        );
        $this->assertMatchesRegularExpression(
            '/EDGE_MARGIN\s*=\s*8/',
            $this->vueSrc,
            'computeAnchor() must use EDGE_MARGIN = 8 (designer acceptance criterion).'
        );
    }

    #[Test]
    public function compute_anchor_reads_chip_rect_and_compares_against_window_inner_width(): void
    {
        // Pin the overflow-detection algorithm shape: read chip
        // rect, compute centered-popover right edge, compare to
        // window.innerWidth minus the gutter.
        $this->assertStringContainsString(
            'getBoundingClientRect',
            $this->vueSrc,
            'computeAnchor() must read the chip rect via getBoundingClientRect.'
        );
        $this->assertMatchesRegularExpression(
            '/chipCenterX\s*\+\s*\(POPOVER_WIDTH\s*\/\s*2\)/',
            $this->vueSrc,
            'computeAnchor() must compute chipCenterX + POPOVER_WIDTH/2 to find the centered-popover right edge.'
        );
        $this->assertMatchesRegularExpression(
            '/window\.innerWidth\s*-\s*EDGE_MARGIN/',
            $this->vueSrc,
            'computeAnchor() must compare against window.innerWidth - EDGE_MARGIN.'
        );
    }

    #[Test]
    public function compute_anchor_flips_to_right_on_overflow(): void
    {
        $this->assertMatchesRegularExpression(
            "/this\\.popoverAnchor\\s*=\\s*['\"]right['\"]/",
            $this->vueSrc,
            "computeAnchor() must set popoverAnchor = 'right' when the centered popover would overflow."
        );
        $this->assertMatchesRegularExpression(
            "/this\\.popoverAnchor\\s*=\\s*['\"]center['\"]/",
            $this->vueSrc,
            "computeAnchor() must reset popoverAnchor = 'center' when the centered popover fits."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — lifecycle wiring
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function open_calls_compute_anchor_in_next_tick(): void
    {
        // computeAnchor() must run AFTER the popover paints so
        // getBoundingClientRect returns real layout values.
        $this->assertMatchesRegularExpression(
            '/open\s*\(\s*\)\s*\{[\s\S]*?this\.\$nextTick[\s\S]*?this\.computeAnchor\(\)/',
            $this->vueSrc,
            'open() must call this.computeAnchor() inside this.$nextTick() so layout is settled before measuring.'
        );
    }

    #[Test]
    public function resize_handler_recomputes_anchor_when_open(): void
    {
        // onResize() recomputes only when popover is open (no
        // wasted work on resize while closed).
        $this->assertMatchesRegularExpression(
            '/onResize\s*\(\s*\)\s*\{[\s\S]*?this\.isOpen[\s\S]*?this\.computeAnchor\(\)/',
            $this->vueSrc,
            'onResize() must recompute anchor only when popover is open.'
        );
        $this->assertMatchesRegularExpression(
            "/addEventListener\\(\\s*['\"]resize['\"]\\s*,\\s*this\\._resizeHandler\\s*\\)/",
            $this->vueSrc,
            'mounted() must register the resize listener.'
        );
        $this->assertMatchesRegularExpression(
            "/removeEventListener\\(\\s*['\"]resize['\"]\\s*,\\s*this\\._resizeHandler\\s*\\)/",
            $this->vueSrc,
            'beforeUnmount() + beforeDestroy() must remove the resize listener to avoid leaks.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — CSS rule for anchor-right variant
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function css_rule_for_anchor_right_variant_present(): void
    {
        // The rule must override the centered-anchor transform with
        // a right-edge anchor (no transform). Pin the three required
        // declarations: left:auto, right:0, transform:none.
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip-popover--anchor-right\s*\{[^}]*left:\s*auto/i',
            $this->css,
            'AI-734 CSS rule must reset `left: auto` to release the centered anchor.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip-popover--anchor-right\s*\{[^}]*right:\s*0/i',
            $this->css,
            'AI-734 CSS rule must set `right: 0` to hug the chip right edge.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-page-chip-popover--anchor-right\s*\{[^}]*transform:\s*none/i',
            $this->css,
            'AI-734 CSS rule must reset `transform: none` to drop the centered-anchor translate.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai734_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-16-77fedf', $this->vueSrc);
        $this->assertStringContainsString('AI-734', $this->vueSrc);
        $this->assertStringContainsString('task-2026-05-16-77fedf', $this->css);
        $this->assertStringContainsString('AI-734', $this->css);
    }
}
