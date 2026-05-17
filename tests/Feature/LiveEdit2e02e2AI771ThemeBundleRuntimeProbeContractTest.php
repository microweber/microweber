<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-2e02e2 / AI-771 CHANGE re-ship —
 * theme-bundle runtime-probe contract test.
 *
 * Designer dispatched a `[CHANGE]` per the SOUL #108 verify-before-
 * accept contract: AI-771 source change at
 * `packages/frontend-assets/resources/assets/css/microweber/css/default.css`
 * was correct AND the frontend-assets Vite rebuild captured it,
 * BUT the Webpack-built `microweber-filament-theme.css` bundle
 * was 24 minutes older than the source edit and still carried
 * the stale `.mw-filepicker-ai-tab-wrap { max-width: 400px; margin: auto }`
 * rule. Designer's runtime probe measured the Generate button
 * ratio at 0.465 (need ≥0.85).
 *
 * **Root cause** — duplicated-import path:
 * `packages/microweber-filament-theme/resources/assets/css/index.css:34`
 *
 *     @import '../../../../frontend-assets/resources/assets/css/microweber/css/default.css';
 *
 * The SAME `default.css` is consumed by TWO build pipelines:
 *   1. `packages/frontend-assets/public/build/default.css` — Vite
 *   2. `public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css` — Webpack
 *
 * Both bundles load on `/admin/live-edit`. The theme bundle loads
 * LATER in the cascade so its (stale) rule wins.
 *
 * **The lesson** — Stage-4 failure-family sub-case: two bundles
 * consume the same source CSS via cross-package `@import`; if only
 * one rebuild fires, the runtime is incorrect even though every
 * source-level + one-bundle-level test passes. This test closes
 * the gap by reading the SERVED Webpack bundle directly and
 * asserting the AI-771 rule body is present + bundle mtime ≥
 * source mtime (catches "forgot to run
 * `cd packages/microweber-filament-theme && npm run build`").
 *
 * Skipped gracefully when the served bundle file is absent (fresh
 * clone before `npm run build` has ever run) so CI on a clean
 * checkout doesn't false-fail.
 */
class LiveEdit2e02e2AI771ThemeBundleRuntimeProbeContractTest extends TestCase
{
    private string $themeBundlePath;
    private string $sourcePath;
    private ?string $themeBundle = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->themeBundlePath = base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        );
        $this->sourcePath = base_path(
            'packages/frontend-assets/resources/assets/css/microweber/css/default.css'
        );
        if (! file_exists($this->themeBundlePath)) {
            $this->markTestSkipped(
                'Served microweber-filament-theme.css bundle is absent — run `cd packages/microweber-filament-theme && npm run build` first. This test verifies runtime delivery, not source presence; skipping in environments where the bundle has never been built.'
            );
        }
        $this->themeBundle = (string) file_get_contents($this->themeBundlePath);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — AI-771 rule body present in served theme bundle
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function theme_bundle_contains_ai771_max_width_none_rule(): void
    {
        // The AI-771 rule body: max-width: none + margin: 0. Webpack
        // minifier may strip whitespace, so use a regex tolerant of
        // both pretty and minified output.
        $this->assertMatchesRegularExpression(
            '/\.mw-filepicker-ai-tab-wrap\s*\{[^}]*max-width:\s*none[^}]*margin:\s*0/i',
            $this->themeBundle,
            'Served theme bundle must contain the AI-771 `.mw-filepicker-ai-tab-wrap { max-width: none; margin: 0 }` rule body.'
        );
    }

    #[Test]
    public function theme_bundle_does_not_contain_legacy_400px_rule(): void
    {
        // Negative regression-guard against the exact defect
        // designer caught: a stale `max-width: 400px` (with the
        // companion `margin: auto`) inside the .mw-filepicker-ai-
        // tab-wrap rule body. If both literals coexist inside the
        // rule body the AI-771 fix has been lost.
        $start = strpos($this->themeBundle, '.mw-filepicker-ai-tab-wrap');
        $this->assertNotFalse(
            $start,
            'Theme bundle must contain a `.mw-filepicker-ai-tab-wrap` rule (it disappeared entirely — that is itself a regression).'
        );
        $end = strpos($this->themeBundle, '}', $start);
        $this->assertNotFalse($end);
        $body = substr($this->themeBundle, $start, $end - $start);
        $this->assertDoesNotMatchRegularExpression(
            '/max-width:\s*400px/',
            $body,
            'Legacy `max-width: 400px` literal must NOT appear in the `.mw-filepicker-ai-tab-wrap` rule body of the served theme bundle (AI-771 / task-2e02e2 stale-bundle regression guard).'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/margin:\s*auto/',
            $body,
            'Legacy `margin: auto` literal must NOT appear in the `.mw-filepicker-ai-tab-wrap` rule body of the served theme bundle (AI-771 / task-2e02e2 stale-bundle regression guard).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — narrow secondary-field constraint preserved in bundle
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function theme_bundle_preserves_field_narrow_200px_rule(): void
    {
        // .mw-filepicker-ai-field-narrow must remain 200px so the
        // secondary fields (Width / Height / Reference image /
        // aspect ratio) stay narrow against the now-full-width
        // Generate button. Visual-hierarchy load-bearing rule.
        $this->assertMatchesRegularExpression(
            '/\.mw-filepicker-ai-field-narrow\s*\{[^}]*width:\s*200px/i',
            $this->themeBundle,
            'Served theme bundle must preserve `.mw-filepicker-ai-field-narrow { width: 200px }` — only the outer wrapper was un-constrained by AI-771.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — bundle freshness (the actual "forgot to rebuild" guard)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function theme_bundle_mtime_is_at_least_source_mtime(): void
    {
        // The proof-of-life guard that would have caught the
        // task-2e02e2 defect at SHIP time. If the source was
        // edited but the theme bundle wasn't rebuilt, the bundle
        // is older than the source — fail loud.
        $this->assertFileExists($this->sourcePath, 'Source default.css must exist.');
        $bundleMtime = filemtime($this->themeBundlePath);
        $sourceMtime = filemtime($this->sourcePath);
        $this->assertGreaterThanOrEqual(
            $sourceMtime,
            $bundleMtime,
            sprintf(
                'Served theme bundle mtime (%s) must be ≥ source default.css mtime (%s). Run `cd packages/microweber-filament-theme && npm run build` to refresh the theme bundle after editing frontend-assets `default.css` — the theme bundle consumes default.css via cross-package `@import` (index.css:34) so a frontend-assets-only rebuild is not enough.',
                date('c', $bundleMtime),
                date('c', $sourceMtime)
            )
        );
    }

    #[Test]
    public function theme_index_css_still_imports_frontend_assets_default(): void
    {
        // Pins the architectural fact that the theme bundle
        // consumes the frontend-assets default.css via @import.
        // If this import is ever removed, the two-pipeline
        // duplication problem goes away — but so does any rule
        // currently relied upon to reach the theme bundle via
        // default.css. Anyone removing this import must update
        // this test + audit every rule in default.css that's
        // expected on /admin/live-edit.
        $indexCss = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/index.css'
        ));
        $this->assertStringContainsString(
            "@import '../../../../frontend-assets/resources/assets/css/microweber/css/default.css';",
            $indexCss,
            'Theme `index.css` must still `@import` frontend-assets default.css. If this import was removed, audit every rule in default.css that needs to reach the theme bundle + update this test.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — task-id markers + defect-class lineage
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai771_markers_pinned_in_source(): void
    {
        // The AI-771 source-side markers must remain present in
        // default.css so future cross-surface audits via
        // `git grep task-2026-05-17-23e0ee` continue to find the
        // change. Task-2e02e2 only re-shipped the bundle; the
        // source markers are owned by the original AI-771 commit
        // 0d047f4801.
        $source = (string) file_get_contents($this->sourcePath);
        $this->assertStringContainsString('task-2026-05-17-23e0ee', $source);
        $this->assertStringContainsString('AI-771', $source);
    }
}
