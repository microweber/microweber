<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-144 / AI-151 — toolbar text-label suppression rule guard rail.
 *
 * Tester re-test of cycle-143 selector correction confirmed AI-150's
 * AI-141-half didn't actually fix the toolbar labels. The corrected
 * selector targets the right buttons, but cycle-143 also added a
 * `:has(*:not(:empty))::after { content: none }` suppression rule
 * intended to avoid double-labels on buttons that already had visible
 * inline text. That rule was overly broad: every Vue toolbar button
 * contains a non-empty `<span v-html="iconXxx" aria-hidden>SVG</span>`
 * child (and a Vuetify `<v-tooltip>` portal element), so the
 * suppression matched EVERY button. content:none won the cascade and
 * stripped the auto-label for all toolbar buttons.
 *
 * Cycle-144 fix: remove the suppression rule. The Vue toolbar buttons
 * have NO visible inline text content (only aria-hidden SVG icons),
 * so `content: attr(aria-label)` on `::after` cannot cause a visible
 * double-label. Buttons that DO have inline text (ADMIN at
 * Toolbar.vue:61 with a `<span class="ms-1 font-weight-bold">ADMIN
 * </span>`, or the View/Save buttons in SaveButton.vue) use different
 * class chains that this scoped selector doesn't match — they are
 * untouched.
 *
 * This test pins:
 *   1. The suppression rule MUST NOT come back — a future PR that
 *      reintroduces `:has(*:not(:empty))` on a `.live-edit-toolbar-
 *      buttons` selector silently strips the labels again.
 *   2. The `content: attr(aria-label)` rule MUST be present.
 *   3. The cycle-144 anchor and AI-151 reference MUST stay inline so
 *      the rationale is discoverable at refactor time.
 *   4. Functional pin: the built bundle must NOT contain the
 *      suppression rule at all.
 */
class Ai151ToolbarLabelSuppressionContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_does_not_carry_overly_broad_suppression(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // Negative pin: the cycle-143 suppression rule MUST NOT come
        // back. Anyone re-introducing `:has(*:not(:empty))` on the
        // toolbar selector silently breaks AI-141 again.
        $this->assertDoesNotMatchRegularExpression(
            '/\.live-edit-toolbar-buttons[^{]*:has\(\s*\*:not\(:empty\)\s*\)::after[^{]*\{[\s\S]*?content:\s*none/m',
            $src,
            'live-edit-mobile.css MUST NOT carry a `:has(*:not(:empty))'
            . '::after { content: none }` suppression on the toolbar '
            . 'selector. That rule matches every Vue toolbar button '
            . '(they all contain a non-empty SVG child) and strips '
            . 'the auto-label content:attr(aria-label) for all of them.'
        );
    }

    #[Test]
    public function source_keeps_the_auto_label_rule(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // Positive pin: the auto-label rule must remain.
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.live-edit-toolbar-buttons\.mw-toolbar-icon-btn::after[\s\S]{0,300}content:\s*attr\(aria-label\)/m',
            $src,
            'live-edit-mobile.css MUST keep the `content: attr(aria-label)` '
            . 'rule on the corrected toolbar selector — without it, '
            . 'icon-only Vue toolbar buttons have no visible text on '
            . 'mobile (AI-141 symptom).'
        );
    }

    #[Test]
    public function source_documents_cycle_144_and_ai_151_inline(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $this->assertStringContainsString(
            'AI-151',
            $src,
            'live-edit-mobile.css MUST carry the AI-151 anchor inline '
            . 'so a future maintainer can trace the cycle-144 corrigendum.'
        );
        $this->assertStringContainsString(
            'cycle-144',
            $src,
            'live-edit-mobile.css MUST carry the cycle-144 anchor inline.'
        );
    }

    #[Test]
    public function built_bundle_carries_auto_label_without_suppression(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped(
                "Built bundle not present at {$rel}; skipping the production-CSS pin. "
                . 'Run `npm run build` in packages/microweber-filament-theme/ to refresh.'
            );
        }
        $built = file_get_contents($path);

        // Functional pin #1: built bundle MUST contain the auto-label
        // rule body so the labels actually render in the browser.
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.live-edit-toolbar-buttons\.mw-toolbar-icon-btn::after[^{]*\{[^}]*content:\s*attr\(aria-label\)/m',
            $built,
            'Built CSS bundle MUST carry the toolbar auto-label rule '
            . 'body. If this fails, live-edit-mobile.css is no longer '
            . 'reaching the bundle (AI-149 regression class) OR the '
            . 'rule was edited out at source (AI-151 regression class).'
        );

        // Functional pin #2: the overly-broad suppression rule MUST
        // NOT be in the built bundle. A future PR adding it back
        // silently strips the labels again.
        $this->assertDoesNotMatchRegularExpression(
            '/\.live-edit-toolbar-buttons[^{]*:has\(\s*\*:not\(:empty\)\s*\)::after[^{]*\{[^}]*content:\s*none/m',
            $built,
            'Built CSS bundle MUST NOT contain the `:has(*:not(:empty))'
            . '::after { content: none }` suppression on the toolbar '
            . 'selector. AI-151 root cause: that rule matches every '
            . 'Vue toolbar button and strips the auto-label.'
        );
    }
}
