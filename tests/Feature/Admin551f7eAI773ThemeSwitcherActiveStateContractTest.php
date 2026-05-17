<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-551f7e / AI-773 — admin topbar user-menu theme switcher
 * active-state visibility + aria-pressed.
 * Jira: https://microweber.atlassian.net/browse/AI-773
 *
 * Designer's Round-9 audit caught two defects on the Filament stock
 * theme-switcher's 3 toggle buttons (Light / Dark / System inside the
 * user-menu dropdown):
 *
 *   1. Active indicator was `oklch(0.985 ...)` ≈ white on white —
 *      effectively invisible on the light topbar. User can't tell
 *      which theme is currently active.
 *   2. Missing `aria-pressed` — screen readers hear the button names
 *      but cannot announce which one is the current state.
 *
 * Fix is two-surface:
 *
 *   A. Blade override at
 *      resources/views/vendor/filament-panels/components/theme-switcher/button.blade.php
 *      adds `x-bind:aria-pressed` bound to the same `theme === @js($theme)`
 *      expression that drives the `fi-active` visual class. Identical
 *      truth condition; one source of truth for sighted + AT users.
 *
 *   B. CSS in
 *      packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css
 *      scopes a `body.fi-panel-admin .fi-theme-switcher-btn.fi-active`
 *      rule with accent-soft bg + 1px accent ring + accent foreground.
 *      Dark theme variant via `html.dark` parent. Token-scoping per
 *      SOUL #108 — every var() carries a literal fallback because this
 *      rule consumes :root-scoped ESE tokens from outside .mw-live-edit-page.
 *
 * Back-compat preserved: the override is byte-identical to the vendor
 * template except for the single new `x-bind:aria-pressed` line and
 * its docblock; props / label generation / click handler / tooltip /
 * x-bind:class / icon rendering all unchanged.
 */
class Admin551f7eAI773ThemeSwitcherActiveStateContractTest extends TestCase
{
    private string $blade;
    private string $css;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'resources/views/vendor/filament-panels/components/theme-switcher/button.blade.php'
        ));
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
        $this->bundle = file_exists(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        )) ? (string) file_get_contents(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        )) : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Blade override adds aria-pressed bound to active state
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function blade_override_binds_aria_pressed_to_active_state(): void
    {
        // The single new behaviour vs vendor: aria-pressed bound to
        // the same truth expression as fi-active.
        $this->assertMatchesRegularExpression(
            '/x-bind:aria-pressed="theme\s*===\s*@js\(\$theme\)\s*\?\s*[\'"]true[\'"]\s*:\s*[\'"]false[\'"]"/',
            $this->blade,
            'Blade override must bind `aria-pressed` to `theme === @js($theme) ? "true" : "false"` so AT users hear which theme is active.'
        );
    }

    #[Test]
    public function blade_override_preserves_vendor_active_class_binding(): void
    {
        // Sighted users still get the fi-active class binding — the
        // CSS rule keys off `.fi-active`, and the aria-pressed must
        // track the same expression so the two never diverge.
        $this->assertMatchesRegularExpression(
            "/x-bind:class=\"\{\s*'fi-active':\s*theme\s*===\s*@js\(\\\$theme\)\s*\}\"/",
            $this->blade,
            'Blade override must preserve the vendor `x-bind:class="{ fi-active: theme === @js($theme) }"` binding.'
        );
    }

    #[Test]
    public function blade_override_preserves_vendor_click_handler_and_props(): void
    {
        // The other vendor mechanics — props, label, click handler,
        // tooltip, icon render — must remain byte-equivalent so
        // upstream feature regressions don't sneak in.
        $this->assertStringContainsString("@props([\n    'icon',\n    'theme',\n])", $this->blade);
        $this->assertStringContainsString(
            "x-on:click=\"(theme = @js(\$theme)) && close()\"",
            $this->blade,
            'Click handler must remain vendor-identical.'
        );
        $this->assertStringContainsString(
            "filament-panels::layout.actions.theme_switcher.{\$theme}.label",
            $this->blade,
            'Label translation key must remain vendor-identical.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — CSS active-state visibility rule (scoped, dark-theme aware)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function css_scopes_active_state_rule_to_admin_panel(): void
    {
        // Must use body.fi-panel-admin so the override never leaks into
        // checkout or profile panels.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-theme-switcher-btn\.fi-active\s*\{/',
            $this->css,
            'Admin theme-switcher active-state rule must scope to `body.fi-panel-admin .fi-theme-switcher-btn.fi-active`.'
        );
    }

    #[Test]
    public function css_active_rule_uses_accent_soft_bg_and_accent_ring(): void
    {
        // Slice the AI-773 rule body to assert tokens (avoids
        // selector-self-match guard hits in the docblock prose).
        $start = strpos($this->css, 'body.fi-panel-admin .fi-theme-switcher-btn.fi-active {');
        $this->assertNotFalse($start);
        $end = strpos($this->css, '}', $start);
        $this->assertNotFalse($end);
        $body = substr($this->css, $start, $end - $start);
        $this->assertMatchesRegularExpression(
            '/background-color:\s*var\(--ese-accent-soft,/',
            $body,
            'AI-773 active rule must use `var(--ese-accent-soft, ...)` for the visible background.'
        );
        $this->assertMatchesRegularExpression(
            '/box-shadow:\s*inset\s+0\s+0\s+0\s+1px\s+var\(--ese-accent,/',
            $body,
            'AI-773 active rule must add a 1px inset `var(--ese-accent, ...)` ring for ring-style contrast (works in both light + dark themes).'
        );
        $this->assertMatchesRegularExpression(
            '/color:\s*var\(--ese-accent,/',
            $body,
            'AI-773 active rule must set `color: var(--ese-accent, ...)` so the icon stroke / text inherits accent.'
        );
    }

    #[Test]
    public function css_active_rule_has_dark_theme_override(): void
    {
        $this->assertMatchesRegularExpression(
            '/html\.dark\s+body\.fi-panel-admin\s+\.fi-theme-switcher-btn\.fi-active\s*\{/',
            $this->css,
            'AI-773 must include a `html.dark` variant for the active-state rule (else the indicator drops back to the broken oklch on dark theme too).'
        );
    }

    #[Test]
    public function css_token_fallbacks_present_on_every_var(): void
    {
        // SOUL #108 spec-doc-nit: every var() in tokens-consumed-
        // outside-`.mw-live-edit-page` surfaces MUST carry a literal
        // fallback. Slice ONLY the AI-773 block (slice from the
        // section docblock end `*/` to the next `/*` AI-marker or EOF)
        // to avoid selector-self-match on other rules' var()s.
        $start = strpos($this->css, 'AI-773 (task-2026-05-17-551f7e)');
        $this->assertNotFalse($start);
        // Skip past the docblock close `*/`.
        $docEnd = strpos($this->css, '*/', $start);
        $this->assertNotFalse($docEnd);
        $sliceStart = $docEnd + 2;
        // Slice to next `/*` or EOF.
        $sliceEnd = strpos($this->css, "/*", $sliceStart);
        $slice = $sliceEnd === false
            ? substr($this->css, $sliceStart)
            : substr($this->css, $sliceStart, $sliceEnd - $sliceStart);
        // Every var( in the slice must have a comma-prefixed fallback.
        preg_match_all('/var\(([^)]+)\)/', $slice, $matches);
        foreach ($matches[1] as $varExpr) {
            $this->assertStringContainsString(
                ',',
                $varExpr,
                "Every var() in the AI-773 CSS slice must carry a literal fallback (`var(--x, fallback)`). Offender: `var({$varExpr})`."
            );
        }
        $this->assertGreaterThan(0, count($matches[1]), 'AI-773 slice must consume at least one ESE token.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — runtime probe — bundle ships the AI-773 rule
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_carries_ai773_active_state_rule(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Served microweber-filament-theme.css bundle absent — run `cd packages/microweber-filament-theme && npm run build`.');
        }
        // Minified output may collapse whitespace. The selector +
        // background-color combo is the unique fingerprint.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-theme-switcher-btn\.fi-active\s*\{[^}]*background-color:/s',
            $this->bundle,
            'Served theme bundle must carry the AI-773 active-state rule. If absent, run the Webpack rebuild.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — markers + back-compat
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai773_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-551f7e', $this->blade);
        $this->assertStringContainsString('AI-773', $this->blade);
        $this->assertStringContainsString('task-2026-05-17-551f7e', $this->css);
        $this->assertStringContainsString('AI-773', $this->css);
    }

    #[Test]
    public function vendor_template_path_resolved(): void
    {
        // Sanity check: the override path matches what Filament
        // actually resolves. If Filament ever moves the vendor
        // template, the override silently stops applying — this
        // assertion is a tripwire for that.
        $this->assertFileExists(base_path(
            'vendor/filament/filament/resources/views/components/theme-switcher/button.blade.php'
        ), 'Vendor template path must still exist; if Filament moved it, this override file must move to the new path.');
    }
}
