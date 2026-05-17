<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-2c33e0 / AI-768 — Image picker tab nav hidden
 * on mobile (P1). Jira:
 *   https://microweber.atlassian.net/browse/AI-768
 *
 * Designer dispatch 2026-05-17T05:47:52 (P1 functional — three
 * of four entry modes dead on mobile).
 *
 * Root cause (confirmed by designer DOM probe at 390×844): the
 * picker's tab header wrapper carried `.mw-live-edit-resolutions-
 * wrapper` — a legacy class shared with the canvas resolution
 * switcher. `mobile-touch.css` line 150 hid that class at
 * <576px (intended for the canvas device-switcher only, since
 * the user IS on a phone — the desktop preview is the wrong
 * default). The picker tabs disappeared at the same breakpoint
 * with no replacement, leaving users trapped on the last-active
 * tab (Media library by default).
 *
 * Fix per designer's Option A — drop the shared hide-class
 * coupling. The picker now also carries a sibling
 * `.mw-filepicker-component-tab-nav` marker class; the mobile
 * hide rule is scoped via `:not(.mw-filepicker-component-tab-nav)`
 * so only the canvas device-switcher matches the hide. Visual
 * styling rules on `.mw-live-edit-resolutions-wrapper` (rounded
 * chip strip, padding, dark-mode bg) still apply to both
 * surfaces — those rules have no `:not()` guard.
 *
 * Acceptance per dispatch:
 *   - 390 px: all four tab labels visible and tappable
 *   - 1440 px: no desktop regression
 *   - Light + dark both verified
 */
class LiveEdit2c33e0AI768PickerTabMobileContractTest extends TestCase
{
    private string $filepicker;
    private string $mobileCss;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filepicker = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/components/filepicker.js'
        ));
        $this->mobileCss = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css'
        ));
        $bundlePath = base_path(
            'packages/microweber-filament-theme/resources/dist/build/microweber-filament-theme.css'
        );
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — picker JS carries the marker class
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function picker_tab_nav_wrapper_includes_marker_class(): void
    {
        // The `<div>` template string in filepicker.js's
        // navigation() method must include the picker marker
        // ALONGSIDE the legacy resolutions-wrapper class.
        $this->assertMatchesRegularExpression(
            "/<div class=\"form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper mw-filepicker-component-tab-nav mx-0\"/",
            $this->filepicker,
            'Picker tab nav wrapper template must include `mw-filepicker-component-tab-nav` marker class alongside the legacy `mw-live-edit-resolutions-wrapper`.'
        );
    }

    #[Test]
    public function picker_keeps_legacy_resolutions_wrapper_class_for_visual_styling(): void
    {
        // Defensive — the legacy class must remain so the picker
        // inherits the rounded chip-strip styling from
        // general-styles.css. The fix is hide-rule scoping, not
        // class removal.
        $this->assertStringContainsString(
            'mw-live-edit-resolutions-wrapper',
            $this->filepicker,
            'Picker must retain `.mw-live-edit-resolutions-wrapper` for visual styling — the fix narrows the hide rule, not the class set.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — mobile hide rule scoped via :not()
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function mobile_hide_rule_exempts_picker_via_not_selector(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-resolutions-wrapper:not\(\.mw-filepicker-component-tab-nav\)\s*\{[^}]*display:\s*none\s*!important/i',
            $this->mobileCss,
            'Mobile hide rule must read `.mw-live-edit-resolutions-wrapper:not(.mw-filepicker-component-tab-nav) { display: none !important; }` per AI-768 Option A.'
        );
    }

    #[Test]
    public function unscoped_legacy_hide_rule_is_gone(): void
    {
        // Strip block + line comments so the migration-rationale
        // block (which legitimately mentions the old unscoped
        // rule) doesn't false-match. LESSONS selector-self-match
        // family — hit 9+ times this session.
        $rules = preg_replace('/\/\*.*?\*\//s', '', $this->mobileCss);
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.mw-live-edit-resolutions-wrapper\s*\{[^}]*display:\s*none/im',
            $rules,
            'Unscoped `.mw-live-edit-resolutions-wrapper { display: none }` must be gone — replaced by the :not() variant.'
        );
    }

    #[Test]
    public function mobile_hide_rule_still_inside_575_98px_media_block(): void
    {
        // The scope-shift must happen inside the existing
        // @media (max-width: 575.98px) block — desktop must not
        // be affected.
        $start = strpos($this->mobileCss, '@media (max-width: 575.98px)');
        $this->assertNotFalse($start, '@media (max-width: 575.98px) block must be present in mobile-touch.css.');
        $end = strpos($this->mobileCss, '@media', $start + 30);
        $slice = $end !== false
            ? substr($this->mobileCss, $start, $end - $start)
            : substr($this->mobileCss, $start);
        $this->assertStringContainsString(
            '.mw-live-edit-resolutions-wrapper:not(.mw-filepicker-component-tab-nav)',
            $slice,
            'The :not()-scoped hide rule must live inside the @media (max-width: 575.98px) block.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — bundle runtime probe (SOUL #108)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_carries_the_not_scoped_hide_rule(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present — run `cd packages/microweber-filament-theme && npm run build` to enable runtime probe.');
        }
        $this->assertStringContainsString(
            '.mw-live-edit-resolutions-wrapper:not(.mw-filepicker-component-tab-nav)',
            $this->bundle,
            'Webpack bundle must carry the :not()-scoped hide rule per AI-768.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai768_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-17-2c33e0', $this->filepicker);
        $this->assertStringContainsString('AI-768', $this->filepicker);
        $this->assertStringContainsString('task-2026-05-17-2c33e0', $this->mobileCss);
        $this->assertStringContainsString('AI-768', $this->mobileCss);
    }
}
