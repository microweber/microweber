<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-b30fd9 — ESE Slice 1.7 / AI-690: rgba literals
 * migrated to --ese-* semantic colour aliases.
 *
 * Pairs with task-2026-05-16-11812b (slice 1.1 tokens). Replaces:
 *   - `.mw-italic-toggle` border literals     → --ese-border-strong
 *   - `.mw-typography-advanced` divider       → --ese-border
 *   - `.text-align` divider                   → --ese-border
 *   - `.element-style-editor-toggle-wrapper.active > div:nth-child(2)`
 *     divider                                  → --ese-border
 *   - `.picker-button` border + hover          → --ese-border-strong,
 *                                                --ese-accent (hover)
 *
 * Each migration collapses a dark-base + `:not(.dark)` light-override
 * pair into one theme-aware rule via the auto-switching token. Net
 * effect: −5 `:not(.dark)` overrides, −2 rules. Visual delta ≤ 0.02
 * alpha (imperceptible) per docblock comment in source.
 *
 * Two literals INTENTIONALLY exempt:
 *   - `.mw-live-edit-slider-small ... box-shadow ... rgba(0,0,0,0.25)` —
 *     deferred to slice 1.2 (MwSlider) which rewrites the entire
 *     slider geometry per spec §4.2.
 *   - `.picker-button` inner-ring `inset 0 0 0 2px rgba(0,0,0,0.2)` —
 *     theme-agnostic by design (delineates the user-chosen swatch
 *     colour from container, not against panel chrome).
 */
class ESEb30fd9Slice17ColourAliasMigrationContractTest extends TestCase
{
    private string $src;
    private string $built;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css'
        ));
        $this->built = (string) file_get_contents(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        ));
    }

    #[Test]
    public function italic_toggle_uses_border_strong_token(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-italic-toggle\s*\{[^}]*border:\s*1px\s+solid\s+var\(--ese-border-strong\)/s',
            $this->src,
            '.mw-italic-toggle border must use var(--ese-border-strong).'
        );
        // The redundant `:not(.dark)` override must be gone
        $this->assertDoesNotMatchRegularExpression(
            '/:not\(\.dark\)\s+#mw-element-style-editor-app\s+\.mw-italic-toggle/',
            $this->src,
            'The :not(.dark) override on .mw-italic-toggle must be removed '
            . '(theme auto-switching is now handled by the token).'
        );
    }

    #[Test]
    public function typography_advanced_divider_uses_ese_border_token(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-typography-advanced\s*\{[^}]*border-top:\s*1px\s+solid\s+var\(--ese-border\)/s',
            $this->src
        );
        $this->assertDoesNotMatchRegularExpression(
            '/:not\(\.dark\)\s+#mw-element-style-editor-app\s+\.mw-typography-advanced/',
            $this->src
        );
    }

    #[Test]
    public function text_align_separator_uses_ese_border_token(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.text-align\s*>\s*\*\s*\+\s*\*\s*\{[^}]*border-inline-start:\s*1px\s+solid\s+var\(--ese-border\)/s',
            $this->src
        );
    }

    #[Test]
    public function toggle_wrapper_active_divider_uses_ese_border_token(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.element-style-editor-toggle-wrapper\.active\s*>\s*div:nth-child\(2\)\s*\{[^}]*border-top:\s*1px\s+solid\s+var\(--ese-border\)/s',
            $this->src
        );
        $this->assertDoesNotMatchRegularExpression(
            '/:not\(\.dark\)\s+\.element-style-editor-toggle-wrapper\.active/',
            $this->src
        );
    }

    #[Test]
    public function picker_button_border_uses_border_strong_and_hover_uses_accent(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.picker-button[^{]*\{[^}]*border:\s*1px\s+solid\s+var\(--ese-border-strong\)\s*!important/s',
            $this->src,
            '.picker-button border must alias --ese-border-strong with !important.'
        );
        $this->assertMatchesRegularExpression(
            '/\.picker-button:hover[^{]*\{[^}]*border-color:\s*var\(--ese-accent\)\s*!important/s',
            $this->src,
            '.picker-button:hover must accent-highlight via var(--ese-accent).'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/:not\(\.dark\)\s+\.form-control-live-edit-label-wrapper\s+\.picker-button/',
            $this->src,
            'The :not(.dark) .picker-button border override must be gone '
            . '(theme auto-switching via --ese-border-strong now covers it).'
        );
    }

    #[Test]
    public function exempt_swatch_inner_ring_remains_theme_agnostic_literal(): void
    {
        // The inner box-shadow of .picker-button stays a black-with-alpha
        // literal by deliberate design — see docblock at the source rule.
        $this->assertMatchesRegularExpression(
            '/\.picker-button[^{]*\{[^}]*box-shadow:\s*inset\s+0\s+0\s+0\s+2px\s+rgba\(0,\s*0,\s*0,\s*0\.2\)\s*!important/s',
            $this->src,
            'Swatch inner-ring must remain rgba(0,0,0,0.2) — theme-agnostic by design.'
        );
    }

    #[Test]
    public function consumer_rgba_literals_collapsed_below_threshold(): void
    {
        // Strip all /* ... */ block comments before scanning so the
        // docblock prose that *describes* the migration (and quotes the
        // old rgba values for the audit trail) does not register as a
        // surviving consumer.
        $stripped = preg_replace('/\/\*.*?\*\//s', '', $this->src);

        // Then count rgba(255,255,255,*) / rgba(0,0,0,*) literals.
        // Filter out :root token definitions — those start with
        // `--`-prefixed property names.
        $consumerLiterals = 0;
        foreach (preg_split('/\R/', $stripped) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '--')) {
                continue;
            }
            if (preg_match('/rgba\(\s*255,\s*255,\s*255|rgba\(\s*0,\s*0,\s*0/', $line)) {
                $consumerLiterals++;
            }
        }
        // Expected surviving literals after AI-690:
        //   - 1× slider thumb box-shadow (deferred to slice 1.2 per spec §4.2)
        //   - 1× picker-button inner-ring (theme-agnostic by design — see source docblock)
        $this->assertLessThanOrEqual(2, $consumerLiterals,
            "Expected ≤2 surviving consumer rgba literals after AI-690 "
            . "(slider thumb + swatch inner-ring); found {$consumerLiterals}. "
            . "Extra literal means a consumer wasn't migrated."
        );
    }

    #[Test]
    public function built_bundle_carries_aliased_consumers(): void
    {
        // Webpack pipeline must have integrated the source changes
        // into the served bundle.
        $this->assertMatchesRegularExpression(
            '/\.mw-italic-toggle\s*\{[^}]*var\(--ese-border-strong\)/s',
            $this->built,
            'Built bundle must contain the .mw-italic-toggle → --ese-border-strong migration.'
        );
        $this->assertMatchesRegularExpression(
            '/\.picker-button:hover[^{]*\{[^}]*var\(--ese-accent\)/s',
            $this->built,
            'Built bundle must contain the .picker-button:hover → --ese-accent migration.'
        );
    }

    #[Test]
    public function task_id_marker_present_for_audit_grep(): void
    {
        $this->assertStringContainsString('task-2026-05-16-b30fd9', $this->src);
        // Should appear in 5 migration loci (one per consumer block touched)
        $count = substr_count($this->src, 'task-2026-05-16-b30fd9');
        $this->assertGreaterThanOrEqual(5, $count,
            "task-2026-05-16-b30fd9 must appear in each migration locus "
            . "(italic-toggle / typography-advanced / text-align / toggle-wrapper / picker-button). "
            . "Found {$count} occurrences; expected ≥5."
        );
    }
}
