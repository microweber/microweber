<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-511 — Form layouts restructure (Phase 1, bounded slice 1/2).
 *
 * Audit task 1.3.1 ("Restructure Form Layouts with Visual Grouping
 * and Sticky Actions") asks for four changes. This slice ships two:
 *   A. Sticky `.fi-form-actions` footer on page-level admin forms
 *      (universal — mobile + desktop), with dark-mode variant.
 *   B. `max-width: 48rem` form width constraint on ≥768px viewports
 *      (Tailwind max-w-3xl, the audit's exact recommendation).
 *
 * Deferred to AI-511a/b/c follow-ups (per the CSS comment):
 *   - Tab reorganization (per-resource PHP edits)
 *   - Section visual-grouping enhancement (needs design mockup)
 *   - Width-tuning if the 48rem proves too tight on dense forms
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Ai511FormLayoutsContractTest extends TestCase
{
    private const MOBILE_TOUCH_CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    private function ai511Block(): string
    {
        $css = $this->read(self::MOBILE_TOUCH_CSS);
        $start = strpos($css, 'AI-511');
        $this->assertNotFalse($start, 'mobile-touch.css must contain the AI-511 marker comment.');
        return substr($css, $start);
    }

    #[Test]
    public function ai511_marker_comment_is_present_and_traceable(): void
    {
        $block = $this->ai511Block();
        $this->assertStringContainsString(
            'AI-511 — Form layouts restructure',
            $block,
            'The marker comment must include the human-readable subject line so future readers can grep for it.'
        );
    }

    /**
     * Shape facts the AI-511 block must contain.
     */
    public static function shapeFactsProvider(): array
    {
        return [
            // Sticky form actions footer.
            'sticky position'                  => ['position: sticky'],
            'sticky bottom 0'                  => ['bottom: 0'],
            'sticky form-actions selector A'   => ['body.fi-panel-admin .fi-page > .fi-form .fi-form-actions'],
            'sticky form-actions selector B'   => ['body.fi-panel-admin .fi-page-content > .fi-form .fi-form-actions'],
            'sticky background-color token'    => ['background-color: var(--bs-body-bg'],
            'sticky top border'                => ['border-top: 1px solid rgba(0, 0, 0, 0.08)'],
            'sticky box-shadow upward'         => ['box-shadow: 0 -4px 8px'],

            // Dark-mode variant for sticky footer.
            'sticky dark selector A'           => ['.dark body.fi-panel-admin .fi-page > .fi-form .fi-form-actions'],
            'sticky dark background'           => ['background-color: rgb(17, 24, 39)'],
            'sticky dark border-top'           => ['border-top-color: rgba(255, 255, 255, 0.08)'],

            // Form width constraint at max-w-3xl on ≥768px.
            'width media query 768'            => ['@media (min-width: 768px)'],
            'width constraint 48rem'           => ['max-width: 48rem'],
            'width centered margin auto'       => ['margin-left: auto'],
            'width form selector A'            => ['body.fi-panel-admin .fi-page > .fi-form'],
            'width form selector B'            => ['body.fi-panel-admin .fi-page-content > .fi-form'],
        ];
    }

    #[Test]
    #[DataProvider('shapeFactsProvider')]
    public function ai511_block_contains_each_shape_fact(string $needle): void
    {
        $block = $this->ai511Block();
        $this->assertStringContainsString(
            $needle,
            $block,
            "AI-511 block must contain `{$needle}`."
        );
    }

    #[Test]
    public function ai511_does_not_touch_checkout_panel(): void
    {
        $block = $this->ai511Block();

        // Regression guard — checkout has its own bespoke form
        // layout from AI-211 (border-radius unification). AI-511
        // must not bleed into `.fi-panel-checkout`. The CSS comment
        // explicitly documents this scope boundary.
        $this->assertStringNotContainsString(
            'body.fi-panel-checkout .fi-form',
            $block,
            'AI-511 rules must not target the checkout panel — AI-211 owns checkout form layout.'
        );
    }

    #[Test]
    public function ai511_does_not_touch_modal_forms(): void
    {
        $block = $this->ai511Block();

        // Regression guard — forms inside Filament modals already
        // handle their own overflow + footer. Applying sticky to
        // `.fi-modal-window .fi-form .fi-form-actions` would
        // create a sticky-inside-scrollable-modal layout bug.
        $this->assertStringNotContainsString(
            '.fi-modal-window .fi-form',
            $block,
            'AI-511 sticky-form-actions must not target forms inside modals.'
        );
    }

    #[Test]
    public function ai511_sticky_is_universal_not_media_gated(): void
    {
        $block = $this->ai511Block();

        // The sticky footer rules MUST appear OUTSIDE the
        // @media (min-width: 768px) wrapper so they apply on
        // mobile too. The width-constraint rules are inside the
        // media query (mobile keeps full width).
        $mediaPos = strpos($block, '@media (min-width: 768px)');
        $this->assertNotFalse($mediaPos);

        // The sticky rule selector must appear BEFORE the @media
        // gate (i.e. outside it). Find a sticky-rule marker.
        $stickyPos = strpos($block, 'position: sticky');
        $this->assertNotFalse($stickyPos);
        $this->assertLessThan(
            $mediaPos,
            $stickyPos,
            'Sticky form-actions rule must appear outside the @media (min-width: 768px) gate so it applies on mobile.'
        );
    }

    #[Test]
    public function ai511_width_constraint_matches_audit_max_w_3xl(): void
    {
        $block = $this->ai511Block();

        // Tailwind max-w-3xl = 48rem. The audit (task 1.3.1) asks
        // for exactly this value. If a future commit bumps the
        // value (e.g. to 56rem max-w-4xl per AI-511c), this test
        // forces a deliberate decision rather than silent drift.
        $this->assertStringContainsString(
            'max-width: 48rem',
            $block,
            'Form width must match the audit-specified max-w-3xl (48rem) per task 1.3.1.'
        );
        $this->assertStringNotContainsString(
            'max-width: 56rem',
            $block,
            'Bumping to max-w-4xl is the AI-511c follow-up — do not silently drift in AI-511.'
        );
    }
}
