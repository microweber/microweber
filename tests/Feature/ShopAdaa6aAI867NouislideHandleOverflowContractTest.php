<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-18-adaa6a / AI-867 — /shop noUi-slider handle viewport
 * overflow at mobile 390 (4px horizontal scroll).
 * Jira: https://microweber.atlassian.net/browse/AI-867
 *
 * Pre-fix the price-range filter at /shop rendered the noUi-slider with
 * handles overflowing the viewport's right edge by ~4px at mobile 390
 * (`docW=394` vs `viewportW=390`). Root cause: noUi-slider library
 * positions handles via `transform: translate(-50%, 0)` from a percentage-
 * positioned origin. At the upper bound (100% slider value), the handle's
 * centroid sits at the track's right edge; the -50% translate leaves half
 * of the 34px handle width (~17px) extending past. The track sits flush
 * against the sidebar's right edge with no right-padding, so the protruding
 * 17px lands outside the 390px viewport.
 *
 * Fix shape (Option A per designer, library-agnostic):
 *   .noUi-target,
 *   .noUi-horizontal {
 *       margin-left: 8px;
 *       margin-right: 18px;
 *   }
 *
 * 18px right compensates for upper-handle's half-width (17px + 1px breathing);
 * 8px left provides modest breathing for the lower-handle (half-protrudes when
 * value = 0%). Designer's spec values preserved verbatim.
 *
 * Avoided alternative `transform: translate(-50%) !important` library-fighting
 * approach (visually breaks handle centering at intermediate slider values).
 *
 * 1st instance of 3rd-party-widget-overflow-at-mobile defect family per
 * designer's LESSONS canonicalization watch — NOT promoting to LESSONS yet
 * (1-instance below 3-instance threshold).
 *
 * Acceptance gates (verified at HEAD):
 *   - Tier-1 source-pin: rule + docblock present in public-touch.css
 *   - Tier-2 served-bundle: rule body byte-served at /templates/bootstrap/css/public-touch.css
 *   - Tier-2 mirror: src + public/ byte-identical (AI-516 convention)
 *   - Tier-3 runtime (Playwright at 390×844): docW === viewportW (no horizontal scroll);
 *     .noUi-handle.noUi-handle-upper getBoundingClientRect.right ≤ 390
 *     (deferred to designer verify-before-accept — price-range filter
 *     conditionally renders based on product price data)
 *
 * 3-group structure: A = source-presence (rule + AI-867 markers + docblock);
 * B = served-mirror byte-identity + served-bundle carries rule;
 * C = back-compat regression sentinels (AI-866 cart-counter rule + AI-516
 * cart-badge tap-target rule + :root --color-primary token preserved).
 */
class ShopAdaa6aAI867NouislideHandleOverflowContractTest extends TestCase
{
    private function srcPath(): string
    {
        return base_path('Templates/Bootstrap/resources/assets/css/public-touch.css');
    }

    private function servedMirrorPath(): string
    {
        return base_path('public/templates/bootstrap/css/public-touch.css');
    }

    private function srcContents(): string
    {
        return (string) file_get_contents($this->srcPath());
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — AI-867 source-presence
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai867_rule_carries_two_selector_targets(): void
    {
        $source = $this->srcContents();
        $this->assertStringContainsString('.noUi-target,', $source, 'AI-867 must target .noUi-target (the canonical noUi-slider mount point).');
        $this->assertStringContainsString('.noUi-horizontal {', $source, 'AI-867 must target .noUi-horizontal (sibling class for horizontal-orientation sliders).');
    }

    #[Test]
    public function ai867_rule_carries_designer_margin_values(): void
    {
        $source = $this->srcContents();
        $this->assertMatchesRegularExpression(
            '/\.noUi-target,\s*\n\s*\.noUi-horizontal\s*\{[^}]*margin-left:\s*8px[^}]*margin-right:\s*18px[^}]*\}/s',
            $source,
            'AI-867 must carry margin-left: 8px + margin-right: 18px (designer spec values verbatim).'
        );
    }

    #[Test]
    public function ai867_carries_task_id_markers(): void
    {
        $source = $this->srcContents();
        $this->assertStringContainsString('task-2026-05-18-adaa6a', $source, 'AI-867 task-id marker required for cross-surface grep.');
        $this->assertStringContainsString('AI-867', $source);
    }

    #[Test]
    public function ai867_docblock_documents_library_agnostic_rationale(): void
    {
        $source = $this->srcContents();
        // The docblock should mention the library + the avoid-translate-50%
        // anti-pattern note so future agents don't try the library-fighting
        // approach.
        $this->assertStringContainsString('noUi-slider', $source, 'AI-867 docblock must name the noUi-slider library for cross-reference.');
        $this->assertStringContainsString('390', $source, 'AI-867 docblock must reference the mobile 390 viewport where the defect manifests.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — served-mirror byte-identity
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function served_mirror_is_byte_identical_to_src(): void
    {
        $src = $this->srcContents();
        $served = (string) file_get_contents($this->servedMirrorPath());
        $this->assertSame(
            $src,
            $served,
            'Templates/Bootstrap/.../public-touch.css MUST be byte-identical to public/templates/bootstrap/css/public-touch.css (AI-516 served-mirror convention).'
        );
    }

    #[Test]
    public function served_mirror_carries_ai867_rule(): void
    {
        $served = (string) file_get_contents($this->servedMirrorPath());
        $this->assertStringContainsString('task-2026-05-18-adaa6a', $served);
        $this->assertStringContainsString('.noUi-target,', $served);
        $this->assertStringContainsString('margin-right: 18px;', $served);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — back-compat regression sentinels
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai866_cart_counter_rule_preserved(): void
    {
        $source = $this->srcContents();
        $this->assertStringContainsString('task-2026-05-18-2747f0', $source, 'AI-866 task marker must stay intact.');
        $this->assertStringContainsString('.js-shopping-cart-quantity,', $source);
        $this->assertStringContainsString('background-color: var(--color-primary, #0d6efd) !important;', $source, 'AI-866 cart-counter brand-blue cascade rule must stay intact.');
    }

    #[Test]
    public function ai516_cart_badge_tap_target_rule_preserved(): void
    {
        $source = $this->srcContents();
        $this->assertMatchesRegularExpression(
            '/\.js-shopping-cart-quantity:not\(\[hidden\]\)\s*\{[^}]*min-width:\s*44px[^}]*min-height:\s*44px[^}]*\}/s',
            $source,
            'AI-516 cart-badge tap-target rule must stay intact.'
        );
    }

    #[Test]
    public function root_color_primary_token_preserved(): void
    {
        $source = $this->srcContents();
        $this->assertStringContainsString('--color-primary: #0d6efd;', $source, ':root --color-primary token must stay intact.');
    }
}
