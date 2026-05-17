<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-23e0ee / AI-771 — Image picker AI (Enter prompt)
 * tab single-column collapse per designer Option A. Jira:
 *   https://microweber.atlassian.net/browse/AI-771
 *
 * Designer dispatch (paired with AI-772 ACK) — Option A greenlit.
 *
 * Pre-fix the AI tab occupied a narrow centered column with dead
 * space on both sides at desktop:
 *   - Outer wrapper: `<div class="mw-image-picker-ai max-w-sm mx-auto">`
 *     (Tailwind max-width: 24rem ≈ 384px, centered).
 *   - Inner wrapper: `.mw-filepicker-ai-tab-wrap { max-width: 400px;
 *     margin: auto; }` (filepicker.css).
 *   - Combined effect: AI tab pinned to ~400px regardless of
 *     modal width. Generate button (w-100) was visually equal
 *     to secondary fields; width hierarchy broken.
 *
 * Fix per Option A:
 *   - Outer wrapper class: `max-w-sm mx-auto` → `w-full`.
 *   - Inner CSS: `max-width: 400px; margin: auto` → `max-width:
 *     none; margin: 0`.
 *   - Secondary fields keep `.mw-filepicker-ai-field-narrow`
 *     (200px) constraint — they SHOULD stay narrow for visual
 *     hierarchy. The fix only un-constrains the OUTER wrapper.
 *
 * Result: single-column flow fills modal width; Generate becomes
 * truly full-width primary; secondary fields stay 200px-narrow.
 *
 * Designer's runtime Tier-3 probe target (not in this PHPUnit
 * contract test — needs Playwright/headless):
 *   const ratio = genBtn.getBoundingClientRect().width /
 *                 modalBody.getBoundingClientRect().width;
 *   expect(ratio).toBeGreaterThanOrEqual(0.85);  // was ≈ 0.45 pre-fix
 */
class LiveEdit23e0eeAI771AiTabSingleColumnContractTest extends TestCase
{
    private string $filepicker;
    private string $css;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filepicker = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/components/filepicker.js'
        ));
        $this->css = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/css/microweber/css/default.css'
        ));
        $bundlePath = base_path(
            'packages/frontend-assets/resources/dist/build/admin.js'
        );
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — outer wrapper class swap
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function outer_wrapper_uses_w_full_not_max_w_sm(): void
    {
        $this->assertStringContainsString(
            '<div class="mw-image-picker-ai w-full">',
            $this->filepicker,
            'AI tab outer wrapper must use `mw-image-picker-ai w-full` per AI-771 Option A.'
        );
    }

    #[Test]
    public function legacy_max_w_sm_mx_auto_is_gone_from_outer_wrapper(): void
    {
        // Strip block + line comments so the AI-771 docblock that
        // legitimately mentions the old Tailwind classes doesn't
        // false-match. LESSONS selector-self-match guard — 11+
        // session hits.
        $rules = preg_replace('/\/\*.*?\*\//s', '', $this->filepicker);
        $rules = preg_replace('/\/\/.*$/m', '', $rules);
        // The OLD outer wrapper shape was:
        //   `<div class="mw-image-picker-ai max-w-sm mx-auto">`.
        // Must be absent in the rendered JS template.
        $this->assertDoesNotMatchRegularExpression(
            '/<div class="mw-image-picker-ai max-w-sm mx-auto">/',
            $rules,
            'Legacy `class="mw-image-picker-ai max-w-sm mx-auto"` outer wrapper must be replaced (AI-771).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — inner wrapper CSS max-width dropped
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai_tab_wrap_max_width_now_none(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-filepicker-ai-tab-wrap\s*\{[^}]*max-width:\s*none/i',
            $this->css,
            '.mw-filepicker-ai-tab-wrap must set `max-width: none` per AI-771 Option A.'
        );
    }

    #[Test]
    public function ai_tab_wrap_margin_no_longer_auto(): void
    {
        // The center-via-auto-margin behaviour is gone; the
        // wrapper no longer needs to center within a wider parent
        // because the wrapper now IS the full width.
        $this->assertMatchesRegularExpression(
            '/\.mw-filepicker-ai-tab-wrap\s*\{[^}]*margin:\s*0/i',
            $this->css,
            '.mw-filepicker-ai-tab-wrap must set `margin: 0` (no longer auto-centered) per AI-771 Option A.'
        );
    }

    #[Test]
    public function legacy_400px_max_width_is_gone_from_css_rule_body(): void
    {
        // Strip block comments so the migration-rationale comment
        // that mentions "400px max-width" doesn't false-match.
        $rules = preg_replace('/\/\*.*?\*\//s', '', $this->css);
        // Within the rule body, the explicit `max-width: 400px` must be gone.
        $start = strpos($rules, '.mw-filepicker-ai-tab-wrap');
        $this->assertNotFalse($start);
        $end = strpos($rules, '}', $start);
        $slice = substr($rules, $start, $end - $start);
        $this->assertDoesNotMatchRegularExpression(
            '/max-width:\s*400px/',
            $slice,
            'Legacy `max-width: 400px` declaration must be gone from .mw-filepicker-ai-tab-wrap rule body.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — non-regression: narrow secondary fields preserved
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function narrow_field_constraint_preserved(): void
    {
        // .mw-filepicker-ai-field-narrow must remain 200px so
        // Width / Height / Reference image / aspect ratio stay
        // narrow (visual hierarchy preserved). AI-771 only
        // touched the OUTER wrapper.
        $this->assertMatchesRegularExpression(
            '/\.mw-filepicker-ai-field-narrow\s*\{[^}]*width:\s*200px/i',
            $this->css,
            '.mw-filepicker-ai-field-narrow must remain `width: 200px` — only the outer wrapper was un-constrained.'
        );
    }

    #[Test]
    public function generate_button_keeps_w_100_full_width_class(): void
    {
        // The Generate button already had Bootstrap `w-100`
        // (full-width within its parent). With the outer wrapper
        // now full-width, w-100 expands to the full modal width.
        // Pin the class so the visual hierarchy hint stays in
        // place.
        $this->assertMatchesRegularExpression(
            '/<button\s+type="button"[^>]*class="[^"]*\bw-100\b[^"]*mw-filepicker-ai-generate-btn/',
            $this->filepicker,
            'Generate button must keep its `w-100` class so it fills the now-full-width outer wrapper.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — bundle runtime probe + markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_carries_w_full_outer_wrapper(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Vite admin.js bundle not present.');
        }
        // Bundle minification keeps the className string intact.
        $this->assertStringContainsString(
            'mw-image-picker-ai w-full',
            $this->bundle,
            'Vite admin.js bundle must carry the AI-771 single-column outer wrapper class string.'
        );
    }

    #[Test]
    public function task_id_and_ai771_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-17-23e0ee', $this->filepicker);
        $this->assertStringContainsString('AI-771', $this->filepicker);
        $this->assertStringContainsString('task-2026-05-17-23e0ee', $this->css);
        $this->assertStringContainsString('AI-771', $this->css);
    }
}
