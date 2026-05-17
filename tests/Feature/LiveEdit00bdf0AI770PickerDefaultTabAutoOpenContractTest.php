<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-00bdf0 / AI-770 — Image picker desktop Media
 * library empty on first paint. Jira:
 *   https://microweber.atlassian.net/browse/AI-770
 *
 * Designer dispatch 2026-05-17T05:48:28 (P2 first-impression —
 * default tab looks broken on desktop).
 *
 * Root cause: the library tab's iframe mount lives inside
 * `library: function () { $(scope).on("$firstOpen", … if (type ===
 * "library") mw.tools.loading + iframe.append) }`. `$firstOpen`
 * only fires when a tab is CLICKED. The default first tab
 * (currently "library") was shown as visually active by mw.tabs()
 * init but its onclick never fired → `$firstOpen` never triggered
 * → the iframe never mounted → blank body.
 *
 * The existing `firstActiveComponent` setting (AI-117 / cycle-
 * 108) handled the NON-DEFAULT first-tab case via a programmatic
 * trigger("click") — but there was no fallback for when
 * `firstActiveComponent` was unset and we just wanted the genuinely
 * first component to fire its first-open content load.
 *
 * Fix: add an `else if (scope.settings.components.length > 0)`
 * branch to the existing firstActiveComponent block. When no
 * override is set, trigger a click on the first component's tab
 * anchor so its `$firstOpen` event fires once and the content
 * mounts on initial paint.
 *
 * Mobile rendering unchanged (mobile coincidentally hit a click-
 * path that triggered $firstOpen — but post-fix the path is
 * deterministic for both viewports).
 */
class LiveEdit00bdf0AI770PickerDefaultTabAutoOpenContractTest extends TestCase
{
    private string $filepicker;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filepicker = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/components/filepicker.js'
        ));
        $bundlePath = base_path(
            'packages/frontend-assets/resources/dist/build/admin.js'
        );
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — auto-click fallback added
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function else_if_branch_handles_default_first_tab(): void
    {
        // The fix adds an `else if (scope.settings.components.length > 0)`
        // sibling to the existing `if (scope.settings.firstActiveComponent)`
        // branch. Pin the structural shape.
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*scope\.settings\.firstActiveComponent\s*\)\s*\{[\s\S]*?\}\s*else\s+if\s*\(\s*scope\.settings\.components\.length\s*>\s*0\s*\)\s*\{/',
            $this->filepicker,
            'Tab-nav setup must include `} else if (scope.settings.components.length > 0) {` branch per AI-770 fallback.'
        );
    }

    #[Test]
    public function else_if_triggers_click_on_first_component_tab(): void
    {
        // Inside the else-if branch the first component's type is
        // read and its tab anchor receives a programmatic click.
        $this->assertMatchesRegularExpression(
            '/scope\.settings\.components\[0\]\.type/',
            $this->filepicker,
            'AI-770 fallback must read scope.settings.components[0].type to derive the first-tab slug.'
        );
        $this->assertMatchesRegularExpression(
            '/else\s+if\s*\(\s*scope\.settings\.components\.length[\s\S]*?firstType\s*=\s*scope\.settings\.components\[0\]\.type[\s\S]*?\$target\.trigger\(\s*[\'"]click[\'"]\s*\)/',
            $this->filepicker,
            'AI-770 fallback must trigger("click") on the first-tab anchor so $firstOpen fires for the default tab.'
        );
    }

    #[Test]
    public function else_if_uses_setTimeout_for_post_tabs_init_dispatch(): void
    {
        // mw.tabs() must finish wiring its handlers before the
        // programmatic click — same one-tick defer pattern as the
        // existing firstActiveComponent branch.
        $this->assertMatchesRegularExpression(
            '/else\s+if\s*\(\s*scope\.settings\.components\.length[\s\S]*?setTimeout\(/',
            $this->filepicker,
            'AI-770 fallback must defer the click via setTimeout so mw.tabs() init has wired handlers first.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — non-regression: firstActiveComponent path preserved
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function firstActiveComponent_branch_still_handles_override(): void
    {
        // The AI-117 / cycle-108 firstActiveComponent path must
        // remain — designers can still force a non-default first tab.
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*scope\.settings\.firstActiveComponent\s*\)\s*\{[\s\S]*?\$target\.trigger\(\s*[\'"]click[\'"]\s*\)/',
            $this->filepicker,
            'firstActiveComponent override path must be preserved (AI-117).'
        );
    }

    #[Test]
    public function library_tab_iframe_mount_path_unchanged(): void
    {
        // The library tab's iframe mount inside its $firstOpen
        // handler is the consumer of the AI-770 fix — pin that the
        // handler still exists. Look for the function head + the
        // moduleFrameRoute call + the "library" type check in
        // separate assertions for robustness against regex escape
        // gymnastics around the `$firstOpen` literal.
        $this->assertStringContainsString(
            'library: function () {',
            $this->filepicker,
            'library tab render function must still exist.'
        );
        $this->assertStringContainsString(
            'filament.admin.pages.media-library',
            $this->filepicker,
            'library tab must still mount the filament.admin.pages.media-library iframe.'
        );
        $this->assertStringContainsString(
            'type === "library"',
            $this->filepicker,
            'library tab $firstOpen handler must still gate on type === "library".'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — bundle runtime probe + markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_carries_components_zero_dot_type_pattern(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Vite admin.js bundle not present — run `cd packages/frontend-assets && npm run build` to enable runtime probe.');
        }
        // After Vite minification the readable identifiers may be
        // mangled; probe for the structural pattern that survives —
        // the `components[0].type` access path (which Vite keeps
        // for property access). Looser regex tolerates whitespace
        // and minified variable renames.
        $this->assertMatchesRegularExpression(
            '/components\[0\]\.type/i',
            $this->bundle,
            'Vite admin.js bundle must carry the `.components[0].type` access path from the AI-770 fallback.'
        );
    }

    #[Test]
    public function task_id_and_ai770_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-17-00bdf0', $this->filepicker);
        $this->assertStringContainsString('AI-770', $this->filepicker);
    }
}
