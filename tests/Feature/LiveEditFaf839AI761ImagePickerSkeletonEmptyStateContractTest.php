<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-faf839 / AI-761 — Image picker Media library tab
 * skeleton loader + empty state.
 *
 * Deferred from AI-739 slice 1 (commit dc63e3d987). AI-739 removed the
 * redundant "Uploaded" tab; AI-761 completes the UX with:
 *
 * 1. Skeleton loader during iframe load — 6 shimmer tiles matching the
 *    post-load 3-column file grid. Hidden on `mw-filemanager:row-count`
 *    postMessage OR 8-second safety timeout.
 *
 * 2. Empty-state detection — media-library-page.blade.php posts
 *    `{ type: 'mw-filemanager:row-count', count: N }` to parent window.
 *    Parent listens; when count === 0, renders in-picker empty state.
 *
 * 3. Empty-state copy + CTA — "No files yet" heading, explainer body,
 *    "Upload from device →" button that programmatically switches to
 *    the desktop tab.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class LiveEditFaf839AI761ImagePickerSkeletonEmptyStateContractTest extends TestCase
{
    private const FILEPICKER_JS = 'packages/frontend-assets/resources/assets/components/filepicker.js';
    private const MEDIA_BLADE   = 'Modules/MediaLibrary/resources/views/filament/admin/pages/media-library-page.blade.php';
    private const DEFAULT_CSS   = 'packages/frontend-assets/resources/assets/css/microweber/css/default.css';
    private const BUNDLE        = 'packages/frontend-assets/resources/dist/build/admin.js';

    private string $js;
    private string $jsStripped;
    private string $blade;
    private string $bladeStripped;
    private string $css;
    private string $cssStripped;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();

        $rawJs = (string) file_get_contents(base_path(self::FILEPICKER_JS));
        $this->js = $rawJs;
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $rawJs) ?? $rawJs;
        $this->jsStripped = preg_replace('~//[^\n]*~', '', $stripped) ?? $stripped;

        $rawBlade = (string) file_get_contents(base_path(self::MEDIA_BLADE));
        $this->blade = $rawBlade;
        $this->bladeStripped = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $rawBlade) ?? $rawBlade;

        $rawCss = (string) file_get_contents(base_path(self::DEFAULT_CSS));
        $this->css = $rawCss;
        $this->cssStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $rawCss) ?? $rawCss;

        $bundlePath = base_path(self::BUNDLE);
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─── Task markers ─────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present_in_filepicker(): void
    {
        $this->assertStringContainsString('task-2026-05-22-faf839', $this->js,
            'filepicker.js must carry the AI-761 task marker.');
    }

    #[Test]
    public function task_marker_present_in_media_blade(): void
    {
        $this->assertStringContainsString('task-2026-05-22-faf839', $this->blade,
            'media-library-page.blade.php must carry the AI-761 task marker.');
    }

    #[Test]
    public function task_marker_present_in_css(): void
    {
        $this->assertStringContainsString('task-2026-05-22-faf839', $this->css,
            'default.css must carry the AI-761 task marker.');
    }

    // ─── 1. Skeleton loader ───────────────────────────────────────────────────

    #[Test]
    public function skeleton_container_inserted_before_iframe(): void
    {
        // The skeleton div must be appended to $wrap BEFORE the iframe
        // is appended — so the shimmer tiles are visible during load.
        $skPos = strpos($this->jsStripped, 'mw-filepicker-library-skeleton');
        $frPos = strpos($this->jsStripped, 'moduleFrameRoute');
        $this->assertNotFalse($skPos, 'mw-filepicker-library-skeleton must be present in filepicker.js');
        $this->assertNotFalse($frPos, 'moduleFrameRoute call must be present in filepicker.js');
        $this->assertLessThan($frPos, $skPos,
            'Skeleton must be constructed before the iframe is created (skeleton first → no blank flash)');
    }

    #[Test]
    public function skeleton_has_six_tiles(): void
    {
        // Must iterate _si = 0; _si < 6 to produce 6 tiles.
        $pos = strpos($this->jsStripped, 'mw-filepicker-library-skeleton-tile');
        $this->assertNotFalse($pos, 'Skeleton tile class must be present');
        // Verify a loop that builds 6 tiles
        $this->assertMatchesRegularExpression(
            '~for\s*\([^;]*;\s*_si\s*<\s*6\s*;~',
            $this->jsStripped,
            'Skeleton must have a loop building 6 tiles (_si < 6)'
        );
    }

    #[Test]
    public function safety_timeout_of_8_seconds(): void
    {
        // 8000ms safety net so skeleton never gets stuck forever.
        $this->assertTrue(
            str_contains($this->jsStripped, '}, 8000)') ||
            str_contains($this->jsStripped, '}, 8e3)') ||
            str_contains($this->jsStripped, '},8000)') ||
            str_contains($this->jsStripped, '},8e3)'),
            'Safety timeout of 8000ms (or 8e3) must be present in _mountLib'
        );
    }

    #[Test]
    public function skeleton_css_shimmer_animation_present(): void
    {
        $this->assertStringContainsString('.mw-filepicker-library-skeleton-tile', $this->cssStripped,
            'default.css must define .mw-filepicker-library-skeleton-tile');
        $this->assertStringContainsString('mw-filepicker-skeleton-shimmer', $this->cssStripped,
            'default.css must define the shimmer animation keyframe');
    }

    #[Test]
    public function skeleton_css_has_prefers_reduced_motion_guard(): void
    {
        $this->assertMatchesRegularExpression(
            '~prefers-reduced-motion[^}]*mw-filepicker-library-skeleton-tile~s',
            $this->cssStripped,
            'Skeleton animation must be suppressed under prefers-reduced-motion'
        );
    }

    #[Test]
    public function skeleton_css_has_dark_mode_variant(): void
    {
        $this->assertMatchesRegularExpression(
            '~html\.dark[^{]*\.mw-filepicker-library-skeleton-tile\s*,|\.dark[^{]*\.mw-filepicker-library-skeleton-tile~',
            $this->cssStripped,
            'default.css must have a dark-mode variant for the skeleton tile'
        );
    }

    // ─── 2. postMessage bridge ────────────────────────────────────────────────

    #[Test]
    public function filepicker_listens_for_row_count_message(): void
    {
        $this->assertStringContainsString("'mw-filemanager:row-count'", $this->jsStripped,
            "filepicker.js must listen for { type: 'mw-filemanager:row-count' }");
    }

    #[Test]
    public function media_blade_posts_row_count_to_parent(): void
    {
        $this->assertStringContainsString('mw-filemanager:row-count', $this->bladeStripped,
            'media-library-page.blade.php must post mw-filemanager:row-count to parent');
        $this->assertStringContainsString('window.parent.postMessage', $this->bladeStripped,
            'media-library-page.blade.php must use window.parent.postMessage');
    }

    #[Test]
    public function media_blade_uses_server_side_rendered_count(): void
    {
        // The count must be PHP-rendered into the script — no Livewire wait needed.
        $this->assertMatchesRegularExpression(
            '~mwMediaLibTotal~',
            $this->blade,
            'media-library-page.blade.php must use $mwMediaLibTotal as the PHP-rendered count variable'
        );
        $this->assertStringContainsString('$mwMediaLibTotal', $this->blade,
            '$mwMediaLibTotal PHP variable must be declared and inlined into the script');
    }

    // ─── 3. Empty state ───────────────────────────────────────────────────────

    #[Test]
    public function empty_state_heading_is_no_files_yet(): void
    {
        $this->assertStringContainsString('mw-filepicker-library-empty-heading', $this->jsStripped,
            'filepicker.js must render .mw-filepicker-library-empty-heading');
        $this->assertStringContainsString("mw.lang('No files yet')", $this->jsStripped,
            'Empty state heading must use mw.lang("No files yet")');
    }

    #[Test]
    public function empty_state_cta_switches_to_desktop_tab(): void
    {
        $pos = strpos($this->jsStripped, 'mw-filepicker-library-empty-cta');
        $this->assertNotFalse($pos);
        $slice = substr($this->jsStripped, (int) $pos, 600);
        $this->assertStringContainsString('js-filepicker-pick-type-tab-desktop', $slice,
            'Empty state CTA must switch to the desktop (upload) tab');
    }

    #[Test]
    public function empty_state_body_copy_present(): void
    {
        $this->assertStringContainsString('mw-filepicker-library-empty-body', $this->jsStripped,
            'filepicker.js must render .mw-filepicker-library-empty-body');
    }

    #[Test]
    public function empty_state_disappears_after_file_upload(): void
    {
        // One FileUploaded event removes the empty state and reloads the iframe.
        // Use the LAST occurrence (the dark-mode block in tree.scss comes after),
        // and extend the window to 3000 chars to cover the full empty-state block.
        $pos = strrpos($this->jsStripped, 'mw-filepicker-library-empty-cta');
        $this->assertNotFalse($pos);
        $slice = substr($this->jsStripped, (int) $pos, 3000);
        $this->assertStringContainsString('FileUploaded', $slice,
            'Empty state must listen for FileUploaded to self-remove');
        $this->assertStringContainsString('_libraryFrameBaseSrc', $slice,
            'After upload, iframe src must be reset from _libraryFrameBaseSrc');
    }

    #[Test]
    public function empty_state_css_defined(): void
    {
        $this->assertStringContainsString('.mw-filepicker-library-empty', $this->cssStripped,
            'default.css must define .mw-filepicker-library-empty');
        $this->assertStringContainsString('.mw-filepicker-library-empty-cta', $this->cssStripped,
            'default.css must define .mw-filepicker-library-empty-cta');
    }

    #[Test]
    public function empty_state_cta_meets_touch_target_floor(): void
    {
        $pos = strpos($this->cssStripped, '.mw-filepicker-library-empty-cta');
        $this->assertNotFalse($pos);
        $slice = substr($this->cssStripped, (int) $pos, 300);
        $this->assertStringContainsString('min-height: 44px', $slice,
            '.mw-filepicker-library-empty-cta must have min-height: 44px (WCAG 2.5.5)');
    }

    #[Test]
    public function empty_state_css_has_dark_mode_variant(): void
    {
        $this->assertMatchesRegularExpression(
            '~html\.dark[^{]*\.mw-filepicker-library-empty|\.dark[^{]*\.mw-filepicker-library-empty~',
            $this->cssStripped,
            'default.css must define dark-mode variant for .mw-filepicker-library-empty'
        );
    }

    // ─── 4-tab strip regression guard ────────────────────────────────────────

    #[Test]
    public function four_tab_strip_unchanged(): void
    {
        // AI-739 removed "server" (Uploaded) from the COMPONENTS ARRAY;
        // the `server: function(){}` component body still exists as a back-compat
        // implementation. We pin that the components array (the defaults block)
        // does NOT list { type: "server" }.
        $defaultsPos = strpos($this->jsStripped, 'components: [');
        $this->assertNotFalse($defaultsPos, 'components array must exist in filepicker.js defaults');
        // Slice the components array (bounded to 600 chars — enough for 4 entries).
        $componentsSlice = substr($this->jsStripped, (int) $defaultsPos, 600);
        $this->assertStringNotContainsString(
            '"server"',
            $componentsSlice,
            'components array must not list "server" (Uploaded) — removed by AI-739'
        );
        // Four tab types remain in the defaults.
        foreach (['library', 'desktop', 'ai', 'url'] as $type) {
            $this->assertStringContainsString(
                '"' . $type . '"',
                $componentsSlice,
                'Tab type "' . $type . '" must remain in the components defaults array'
            );
        }
    }

    // ─── Bundle probe ─────────────────────────────────────────────────────────

    #[Test]
    public function bundle_contains_all_three_ai761_markers(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Vite bundle not present in this environment.');
        }
        foreach ([
            'mw-filepicker-library-skeleton',
            'mw-filemanager:row-count',
            'mw-filepicker-library-empty',
        ] as $marker) {
            $this->assertStringContainsString($marker, $this->bundle,
                'Vite bundle must contain AI-761 marker: ' . $marker);
        }
    }

    #[Test]
    public function bundle_mtime_newer_than_source(): void
    {
        $bundlePath = base_path(self::BUNDLE);
        if (!file_exists($bundlePath)) {
            $this->markTestSkipped('Vite bundle not present.');
        }
        $this->assertGreaterThanOrEqual(
            filemtime(base_path(self::FILEPICKER_JS)),
            filemtime($bundlePath),
            'Bundle mtime must be >= filepicker.js source mtime.'
        );
    }
}
