<?php

use Tests\TestCase;

/**
 * Contract test — AI-1036 / task-2026-05-22-3bc697
 *
 * Regression: AI-877 (a:not(.btn)… { color: var(--color-primary) }) has
 * specificity (0,3,1) which beats the plain .mw-frontend-404__cta--primary
 * selector (0,1,0), setting text colour to #0d6efd on a #0d6efd background
 * and making the "Back to homepage" button text invisible.
 *
 * Fix: color: #fff !important on .mw-frontend-404__cta--primary (and :hover/:focus).
 *
 * Two-layer selector-self-match guard applied per project protocol:
 * Layer 1 (belt): Blade block comments stripped before assertions.
 * Layer 2 (suspenders): prose avoids literal CSS token sequences.
 */
class Frontend3bc697AI1036FourOhFourCtaTextContractTest extends TestCase
{
    private string $src;
    private string $executable;

    protected function setUp(): void
    {
        parent::setUp();

        $raw = (string) file_get_contents(
            base_path('resources/views/frontend/errors/404.blade.php')
        );
        $this->src = $raw;

        // Strip Blade block comments before executable assertions.
        $stripped = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $raw);
        // Strip CSS block comments (inside the <style> block).
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $stripped);
        $this->executable = $stripped;
    }

    // ── Group A: color: #fff !important is present ───────────────────────────

    public function test_primary_cta_has_white_text_with_important(): void
    {
        // The !important is required to defeat AI-877 specificity (0,3,1).
        $this->assertMatchesRegularExpression(
            '~mw-frontend-404__cta--primary\s*\{[^}]*color\s*:\s*#fff\s*!important~',
            $this->executable,
            '.mw-frontend-404__cta--primary must have color: #fff !important to beat AI-877 link-color rule'
        );
    }

    public function test_primary_cta_hover_has_white_text_with_important(): void
    {
        $this->assertMatchesRegularExpression(
            '~mw-frontend-404__cta--primary:hover[^}]*color\s*:\s*#fff\s*!important~s',
            $this->executable,
            '.mw-frontend-404__cta--primary:hover must also carry color: #fff !important'
        );
    }

    public function test_primary_cta_focus_has_white_text_with_important(): void
    {
        $this->assertMatchesRegularExpression(
            '~mw-frontend-404__cta--primary:focus[^}]*color\s*:\s*#fff\s*!important~s',
            $this->executable,
            '.mw-frontend-404__cta--primary:focus must also carry color: #fff !important'
        );
    }

    // ── Group B: background still blue ───────────────────────────────────────

    public function test_primary_cta_background_is_still_brand_blue(): void
    {
        $this->assertMatchesRegularExpression(
            '~mw-frontend-404__cta--primary\s*\{[^}]*background\s*:\s*#0d6efd~',
            $this->executable,
            'Background colour must remain brand-blue #0d6efd'
        );
    }

    // ── Group C: AI-1036 root-cause documented in source ─────────────────────

    public function test_task_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-3bc697',
            $this->src,
            '404 view must carry the AI-1036 task-id marker'
        );
    }

    public function test_ai1036_ticket_reference_present(): void
    {
        $this->assertStringContainsString(
            'AI-1036',
            $this->src,
            '404 view must reference AI-1036 for audit trail'
        );
    }

    // ── Group D: regression guard — no color without !important on primary ───

    public function test_no_plain_color_fff_without_important_on_primary(): void
    {
        // The old plain `color: #fff` (no !important) must be replaced.
        // We check: there must be no occurrence of the pattern
        //   .mw-frontend-404__cta--primary { ... color: #fff; ... }
        //   (note the trailing semicolon or closing brace, not !important)
        // by asserting zero matches of the non-important shape.
        preg_match_all(
            '~mw-frontend-404__cta--primary\s*\{[^}]*color\s*:\s*#fff\s*;~',
            $this->executable,
            $matches
        );
        $this->assertSame(0, count($matches[0]),
            'color: #fff without !important must not appear on .mw-frontend-404__cta--primary — it would be defeated by the AI-877 link-color rule');
    }
}
