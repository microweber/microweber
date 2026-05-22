<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-a901b0 / AI-901 Phase 1 — Image Picker Surface+Action Model
 *
 * Phase 1 scope (5 items):
 * 1. Remove tabs — Library grid is always visible (no tab nav in action-bar mode)
 * 2. Action bar  — Search full-width + Upload/AI/URL icon buttons (44×44 tap targets)
 * 3. Search      — 150ms debounce, communicates to library iframe
 * 4. Upload overlay — drag-drop support, grid visible behind at 40% opacity
 * 5. Selection   — click thumbnail = immediate auto-select (autoSelect:true, no footer)
 *
 * Designer decisions (2026-05-22):
 * - Library always-mounted at picker open (no lazy-mount / $firstOpen for library in action-bar mode)
 * - $firstOpen / __navigation_first guard preserved for non-action-bar mode (backward compat)
 * - AI-769 dark skin tokens extended to new overlay-content class
 */
class FilepickerA901b0AI901ActionBarPhase1ContractTest extends TestCase
{
    private string $src;
    private string $srcStripped;
    private string $css;
    private string $cssStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $rawJs = (string) file_get_contents(
            base_path('packages/frontend-assets/resources/assets/components/filepicker.js')
        );
        $this->src = $rawJs;
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $rawJs) ?? $rawJs;
        $this->srcStripped = preg_replace('~//[^\n]*~', '', $stripped) ?? $stripped;

        $rawCss = (string) file_get_contents(
            base_path('packages/frontend-assets/resources/assets/css/microweber/css/default.css')
        );
        $this->css = $rawCss;
        $this->cssStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $rawCss) ?? $rawCss;
    }

    // ─── Task marker ──────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-a901b0', $this->src,
            'filepicker.js must carry the AI-901 Phase 1 task marker.'
        );
        $this->assertStringContainsString('task-2026-05-22-a901b0', $this->css,
            'default.css must carry the AI-901 Phase 1 task marker.'
        );
    }

    // ─── Scope 1: action-bar nav mode ─────────────────────────────────────────

    #[Test]
    public function action_bar_nav_mode_is_handled_in_navigation(): void
    {
        $this->assertStringContainsString("nav === 'action-bar'", $this->srcStripped,
            'navigation() must have an action-bar branch replacing the tab nav.'
        );
    }

    #[Test]
    public function action_bar_sets_autoselect_true_and_no_footer(): void
    {
        // In init(), action-bar mode sets autoSelect=true and footer=false.
        // Use strrpos — 'action-bar' appears multiple times in the file;
        // the LAST occurrence is inside init() which contains the two assignments.
        $pos = strrpos($this->srcStripped, "nav === 'action-bar'");
        $this->assertNotFalse($pos);
        $slice = substr($this->srcStripped, (int) $pos, 300);
        $this->assertMatchesRegularExpression('~autoSelect\s*=\s*true~', $slice,
            'action-bar mode must set autoSelect:true so clicking a thumbnail immediately selects.'
        );
        $this->assertMatchesRegularExpression('~footer\s*=\s*false~', $slice,
            'action-bar mode must set footer:false (no Insert button — click = immediate select).'
        );
    }

    // ─── Scope 2: Action bar UI ────────────────────────────────────────────────

    #[Test]
    public function action_bar_has_search_input(): void
    {
        $this->assertStringContainsString('mw-filepicker-search-input', $this->srcStripped,
            'Action bar must contain a .mw-filepicker-search-input element.'
        );
        $this->assertStringContainsString('$searchInput', $this->srcStripped,
            'Action bar search input must be stored as scope.$searchInput for external access.'
        );
    }

    #[Test]
    public function action_bar_has_upload_ai_url_buttons(): void
    {
        $this->assertStringContainsString('mw-filepicker-action-btn--upload', $this->srcStripped,
            'Action bar must include an Upload icon button.'
        );
        $this->assertStringContainsString('mw-filepicker-action-btn--ai', $this->srcStripped,
            'Action bar must include an AI icon button.'
        );
        $this->assertStringContainsString('mw-filepicker-action-btn--url', $this->srcStripped,
            'Action bar must include a URL icon button.'
        );
    }

    #[Test]
    public function icon_buttons_have_44px_touch_targets_in_css(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-filepicker-action-btn\s*\{[^}]*width:\s*44px[^}]*height:\s*44px~s',
            $this->cssStripped,
            '.mw-filepicker-action-btn must be 44×44px for WCAG 2.5.5 touch targets.'
        );
    }

    // ─── Scope 3: Search (150ms debounce) ─────────────────────────────────────

    #[Test]
    public function search_uses_150ms_debounce(): void
    {
        $this->assertStringContainsString('150', $this->srcStripped,
            'Search must use 150ms debounce as specified in the acceptance criteria.'
        );
        $this->assertStringContainsString('clearTimeout', $this->srcStripped,
            'Search debounce must use clearTimeout/setTimeout pattern.'
        );
    }

    #[Test]
    public function search_communicates_with_library_iframe(): void
    {
        $this->assertStringContainsString('mw-filepicker-search', $this->srcStripped,
            'Search must send a postMessage with action mw-filepicker-search to the library iframe.'
        );
        $this->assertStringContainsString('keywords=', $this->srcStripped,
            'Search must also reload library iframe with keywords URL param for maximum compatibility.'
        );
    }

    // ─── Scope 4: Upload overlay ──────────────────────────────────────────────

    #[Test]
    public function upload_button_triggers_overlay(): void
    {
        $this->assertStringContainsString("toggleOverlay('desktop')", $this->srcStripped,
            'Upload button must call toggleOverlay(\'desktop\') to show/hide the upload overlay.'
        );
    }

    #[Test]
    public function dragenter_triggers_upload_overlay(): void
    {
        $this->assertStringContainsString('dragenter', $this->srcStripped,
            'dragenter event on picker root must trigger the upload overlay.'
        );
        $this->assertStringContainsString('dragover', $this->srcStripped,
            'dragover event on picker root must be handled for drag-drop support.'
        );
    }

    #[Test]
    public function overlay_css_dims_background_at_40_percent(): void
    {
        // The overlay background uses rgba with ~0.4 opacity to dim the library grid
        $this->assertMatchesRegularExpression(
            '~\.mw-filepicker-overlay\s*\{[^}]*rgba\(0,\s*0,\s*0,\s*0\.4\)~s',
            $this->cssStripped,
            '.mw-filepicker-overlay must use rgba(0,0,0,0.4) to dim grid at 40% opacity.'
        );
    }

    #[Test]
    public function overlay_is_position_absolute_inside_relative_library_section(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-filepicker-library-section\s*\{[^}]*position:\s*relative~s',
            $this->cssStripped,
            '.mw-filepicker-library-section must be position:relative as anchor for absolute overlays.'
        );
        $this->assertMatchesRegularExpression(
            '~\.mw-filepicker-overlay\s*\{[^}]*position:\s*absolute~s',
            $this->cssStripped,
            '.mw-filepicker-overlay must be position:absolute inside the library section.'
        );
    }

    #[Test]
    public function upload_overlay_has_dashed_border(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-filepicker-overlay--desktop[^{]*\{[^}]*border-style:\s*dashed~s',
            $this->cssStripped,
            'Upload overlay must use a dashed border to signal drop-zone intent.'
        );
    }

    // ─── Scope 5: Library immediate-mount (no lazy-mount) ────────────────────

    #[Test]
    public function library_mounts_immediately_in_action_bar_mode(): void
    {
        // _mountLib(null) is the immediate-mount call (null = no loading indicator).
        // It appears inside setTimeout(function() { _mountLib(null); }, 0) for action-bar mode.
        $this->assertStringContainsString('_mountLib(null)', $this->srcStripped,
            'Library must call _mountLib(null) for immediate mounting in action-bar mode.'
        );
        $this->assertMatchesRegularExpression(
            '~setTimeout\s*\(\s*function\s*\(\s*\)\s*\{[^}]*_mountLib\(null\)~s',
            $this->srcStripped,
            'Immediate library mount must be wrapped in setTimeout(0) for action-bar mode.'
        );
    }

    #[Test]
    public function library_still_uses_first_open_for_non_action_bar_mode(): void
    {
        // backward compat: $firstOpen listener must still exist for tab/dropdown modes
        $this->assertStringContainsString('$firstOpen', $this->srcStripped,
            '$firstOpen event listener must still exist for backward-compatible tab/dropdown modes.'
        );
        $this->assertStringContainsString('__navigation_first', $this->srcStripped,
            '__navigation_first guard must be preserved for non-action-bar mode (AI-770 fix).'
        );
    }

    // ─── Overlay infrastructure ────────────────────────────────────────────────

    #[Test]
    public function overlay_build_method_exists(): void
    {
        $this->assertStringContainsString('_buildOverlay', $this->srcStripped,
            '_buildOverlay() method must exist for lazy-building component overlays.'
        );
    }

    #[Test]
    public function toggle_overlay_method_exists(): void
    {
        $this->assertStringContainsString('toggleOverlay', $this->srcStripped,
            'toggleOverlay() method must exist for showing/hiding component overlays.'
        );
        $this->assertStringContainsString('hideAllOverlays', $this->srcStripped,
            'hideAllOverlays() method must exist for mutually-exclusive panel behaviour.'
        );
    }

    #[Test]
    public function overlay_close_button_uses_aria_label(): void
    {
        $this->assertStringContainsString('mw-filepicker-overlay-close', $this->srcStripped,
            'Overlay must include a close button with .mw-filepicker-overlay-close class.'
        );
        $this->assertStringContainsString('aria-label', $this->srcStripped,
            'Overlay close button must carry aria-label for accessibility.'
        );
    }

    // ─── Dark mode (AI-769 token extension) ──────────────────────────────────

    #[Test]
    public function dark_mode_extends_to_overlay_content(): void
    {
        $this->assertStringContainsString('mw-filepicker-overlay-content', $this->cssStripped,
            'dark mode must extend to .mw-filepicker-overlay-content via AI-769 ESE tokens.'
        );
    }

    // ─── Build — bundle contains new selectors ────────────────────────────────

    #[Test]
    public function built_bundle_contains_action_bar_class(): void
    {
        // filepicker.js is compiled into admin.js (not live-edit-app.js)
        $bundlePath = base_path('public/vendor/microweber-packages/frontend-assets/build/admin.js');
        if (!file_exists($bundlePath)) {
            $this->markTestSkipped('Vite admin.js bundle not found in this environment.');
        }
        $bundle = (string) file_get_contents($bundlePath);
        $this->assertStringContainsString('mw-filepicker-action-bar', $bundle,
            'Vite admin.js bundle must contain the action-bar JS from filepicker.js.'
        );
        $this->assertStringContainsString('action-bar', $bundle,
            'Vite admin.js bundle must contain the action-bar nav mode string.'
        );
    }

    #[Test]
    public function built_css_bundle_contains_action_bar_styles(): void
    {
        // CSS from default.css is compiled into default.css (the Vite CSS bundle)
        $bundlePath = base_path('public/vendor/microweber-packages/frontend-assets/build/default.css');
        if (!file_exists($bundlePath)) {
            $this->markTestSkipped('Vite default.css bundle not found in this environment.');
        }
        $bundle = (string) file_get_contents($bundlePath);
        $this->assertStringContainsString('mw-filepicker-action-bar', $bundle,
            'Vite default.css bundle must contain the action-bar styles.'
        );
        $this->assertStringContainsString('mw-filepicker-overlay', $bundle,
            'Vite default.css bundle must contain the overlay styles.'
        );
    }
}
