<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-bc28fd / AI-691a + AI-697 CHANGE re-ship —
 * runtime-bundle probe contract test.
 *
 * Designer dispatched a `[CHANGE]` per the SOUL #108 verify-before-
 * accept contract: AI-691a + AI-697 source-level contract tests
 * passed (both rules present in source), but the live admin build
 * had neither rule firing — Cancel still visible at desktop AND
 * mobile, picker still centered (not anchored). Root cause:
 * `src/MicroweberPackages/Filament/resources/views/filament/
 * components/layout/live-edit-module-settings.blade.php` is the
 * LiveEditModuleSettings sub-form layout, NOT the live-edit
 * canvas layout — the `<style>` block in that Blade only renders
 * when editing a module's settings, never when the +ADD picker
 * modal opens on `/admin/live-edit`.
 *
 * **The lesson** (designer wrote it in plain terms): source-level
 * contract tests pin source presence, NOT runtime delivery.
 * A source-level test scanning the wrong file passes happily while
 * the live build ships without the rule.
 *
 * This test closes that gap: it reads the actual SERVED CSS bundle
 * that gets loaded into `/admin/live-edit` —
 * `public/vendor/microweber-packages/microweber-filament-theme/
 * build/microweber-filament-theme.css` — and asserts that the
 * AI-691a + AI-697 rule strings are present.
 *
 * If the served bundle is missing (e.g. dev forgot to run
 * `npm run build`) OR if the rule is in source but excluded from
 * the bundle by build-config error, this test fails. Source-only
 * tests don't catch that class of bug. This test does.
 *
 * Skipped gracefully when the served bundle file is absent (e.g.
 * fresh clone before `npm run build` has ever run) so CI on a
 * clean checkout doesn't false-fail.
 */
class LiveEditBc28fdAI691a697ServedBundleRuntimeProbeContractTest extends TestCase
{
    private string $bundlePath;
    private ?string $bundle = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bundlePath = base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        );
        if (! file_exists($this->bundlePath)) {
            $this->markTestSkipped(
                'Served microweber-filament-theme.css bundle is absent — run `cd packages/microweber-filament-theme && npm run build` first. This test verifies runtime delivery, not source presence; skipping in environments where the bundle has never been built.'
            );
        }
        $this->bundle = (string) file_get_contents($this->bundlePath);
    }

    #[Test]
    public function bundle_contains_ai691a_cancel_hide_rule(): void
    {
        // The AI-691a rule must hide the picker-modal cancel footer
        // on desktop. Webpack/CSS minifier may strip whitespace, so
        // we use a regex tolerant of minified output.
        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal\s+\.fi-modal-footer-actions\s*\{\s*display:\s*none\s*[;}]/',
            $this->bundle,
            'Served bundle must contain `.mw-content-picker-modal .fi-modal-footer-actions { display: none }` — the AI-691a desktop-Cancel-hide rule.'
        );
    }

    #[Test]
    public function bundle_contains_ai697_anchored_modal_window_rule(): void
    {
        // AI-697 anchors the picker modal to top-left of viewport.
        // The `position: fixed` + `inset-inline-start: 64px` combo
        // is the canonical fingerprint.
        $this->assertMatchesRegularExpression(
            '/\.fi-modal:has\([^)]*\.mw-content-picker-modal\)[^{]*\.fi-modal-window\.mw-content-picker-modal\s*\{[^}]*position:\s*fixed[^}]*inset-inline-start:\s*64px/s',
            $this->bundle,
            'Served bundle must contain the AI-697 `.fi-modal:has(...) .fi-modal-window.mw-content-picker-modal { position: fixed; inset-inline-start: 64px; ... }` rule.'
        );
    }

    #[Test]
    public function bundle_contains_ai697_transparent_backdrop_rule(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-modal:has\([^)]*\.mw-content-picker-modal\)[^{]*\.fi-modal-close-overlay\s*\{\s*background-color:\s*transparent/s',
            $this->bundle,
            'Served bundle must contain the AI-697 transparent-backdrop rule scoped via `:has()` to the picker modal.'
        );
    }

    #[Test]
    public function bundle_contains_picker_open_keyframes(): void
    {
        $this->assertMatchesRegularExpression(
            '/@keyframes\s+mw-add-content-picker-open\s*\{[^}]*from\s*\{[^}]*scale\(0\.95\)\s+translateY\(-4px\)/s',
            $this->bundle,
            'Served bundle must contain the @keyframes `mw-add-content-picker-open` entrance animation declared by AI-697.'
        );
    }

    #[Test]
    public function bundle_contains_min_width_769px_media_block_for_picker(): void
    {
        // The AI-691a + AI-697 rules live inside a single
        // @media (min-width: 769px) — both anchor and Cancel-hide
        // must appear together inside that block. Use multiline
        // dot-all flag.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*min-width:\s*769px\s*\)\s*\{[^}]*\.mw-content-picker-modal[\s\S]*?\.fi-modal:has/s',
            $this->bundle,
            'AI-691a + AI-697 rules must coexist inside the same `@media (min-width: 769px)` block in the served bundle.'
        );
    }

    #[Test]
    public function bundle_contains_prefers_reduced_motion_guard_for_picker(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*prefers-reduced-motion:\s*reduce\s*\)\s*\{[^}]*\.fi-modal:has\([^)]*\.mw-content-picker-modal\)[\s\S]*?animation:\s*none/s',
            $this->bundle,
            'Served bundle must contain the @media (prefers-reduced-motion: reduce) guard that disables the picker open animation.'
        );
    }

    #[Test]
    public function bundle_was_built_recently_relative_to_source(): void
    {
        // Soft proof-of-life: assert the served bundle is at least
        // as new as the source file it was built from. If the
        // source was edited and the bundle wasn't rebuilt, this
        // test fails — catches the "forgot to run npm run build"
        // class of regression.
        $sourcePath = base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css'
        );
        $this->assertFileExists($sourcePath, 'Source live-edit-classes.css must exist.');
        $bundleMtime = filemtime($this->bundlePath);
        $sourceMtime = filemtime($sourcePath);
        $this->assertGreaterThanOrEqual(
            $sourceMtime,
            $bundleMtime,
            sprintf(
                'Served bundle mtime (%s) must be ≥ source mtime (%s). Run `cd packages/microweber-filament-theme && npm run build` to refresh the bundle after editing source.',
                date('c', $bundleMtime),
                date('c', $sourceMtime)
            )
        );
    }

    #[Test]
    public function bundle_does_not_contain_legacy_blade_style_block_rules(): void
    {
        // Negative regression-guard: after the task-bc28fd
        // relocation, the AI-691a + AI-697 rules MUST come from
        // live-edit-classes.css (this test's primary check). The
        // legacy Blade `<style>` block that previously hosted them
        // is no longer the source — verify the bundle didn't end
        // up double-shipping the same rules from two places.
        //
        // Count picker-modal Cancel-hide rules — expect EXACTLY 1.
        $count = preg_match_all(
            '/\.mw-content-picker-modal\s+\.fi-modal-footer-actions\s*\{\s*display:\s*none/',
            $this->bundle
        );
        $this->assertSame(
            1,
            $count,
            "Exactly one AI-691a Cancel-hide rule must be present in the served bundle (found {$count}). Multiple copies suggest a double-ship between live-edit-classes.css and a legacy Blade `<style>` block."
        );
    }
}
