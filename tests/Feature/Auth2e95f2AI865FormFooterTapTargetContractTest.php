<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-18-2e95f2 / AI-865 — /login `.form-footer a` tap-target
 * accessibility defect at mobile 390.
 * Jira: https://microweber.atlassian.net/browse/AI-865
 *
 * Pre-fix the `.form-footer a` CSS rule in `auth/index.blade.php` carried
 * only `color: #3b82f6` + `text-decoration: none` — no display override,
 * no min-height, no padding. The 3 in-page footer anchors (forgot-password
 * + 2x login-link from AI-863) collapsed to ~20px height at mobile 390
 * viewport, failing WCAG 2.5.5 Target Size (AAA: 44×44 minimum). Worst
 * case: "Sign in" link at 42×20 = 840 sq-px tap target (well under the
 * AAA 1936 sq-px requirement).
 *
 * Fix shape (~6 lines of CSS):
 *   .form-footer a {
 *       color: #3b82f6;
 *       text-decoration: none;
 *       display: inline-block;       ← AI-865
 *       min-height: 44px;            ← AI-865 (WCAG 2.5.5 floor)
 *       padding: 12px 16px;          ← AI-865 (vertical breathing)
 *       line-height: 20px;           ← AI-865 (preserve visual rhythm)
 *   }
 *
 * Parent `.form-footer` margin-top bumped 20px → 28px per designer's
 * optional polish (accepted as default since tap-zone enlargement makes
 * the prior 20px margin visually too tight).
 *
 * Acceptance gates (verified at HEAD):
 *   - Tier-1 source-pin: new properties present
 *   - Tier-2 served-page: curl /login returns the new CSS rule body
 *   - Tier-3 runtime: getBoundingClientRect height ≥ 44 on all 3 anchors
 *     at mobile 390 (deferred to Playwright probe — source-presence
 *     proxy via this contract test)
 *
 * 4-group structure: A = `.form-footer a` carries new properties (display +
 * min-height + padding + line-height); B = `.form-footer` parent margin
 * bump; C = AI-863 + AI-864 no-regression sentinels; D = back-compat
 * regression sentinels (original color + text-decoration + hover rule
 * preserved).
 */
class Auth2e95f2AI865FormFooterTapTargetContractTest extends TestCase
{
    private function authSource(): string
    {
        return (string) file_get_contents(base_path('src/MicroweberPackages/User/resources/views/admin/auth/index.blade.php'));
    }

    private function stripBladeAndPhpComments(string $source): string
    {
        $source = preg_replace('~\{\{--.*?--\}\}~s', '', $source);
        $source = preg_replace('~/\*.*?\*/~s', '', (string) $source);
        $source = preg_replace('~//[^\n]*~', '', (string) $source);
        return (string) $source;
    }

    private function formFooterAnchorRule(): string
    {
        // Slice from the `.form-footer a {` selector to the next `}`.
        // Strip CSS comments FIRST so the AI-865 docblock (which
        // legitimately contains `.form-footer { text-align: center }`
        // in its prose) does NOT terminate the slice at the comment's
        // embedded `}` (LESSONS selector-self-match UNIFORMITY rule
        // — 22nd session-recurrence applied first-run).
        $source = preg_replace('~/\*.*?\*/~s', '', $this->authSource());
        $start = strpos((string) $source, '.form-footer a {');
        $this->assertNotFalse($start, '.form-footer a CSS selector must exist in auth/index.blade.php.');
        $end = strpos((string) $source, '}', $start);
        $this->assertNotFalse($end);
        return substr((string) $source, $start, $end - $start + 1);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — .form-footer a carries new WCAG 2.5.5 properties
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function form_footer_anchor_rule_carries_display_inline_block(): void
    {
        $rule = $this->formFooterAnchorRule();
        $this->assertMatchesRegularExpression(
            '/display:\s*inline-block\s*;/',
            $rule,
            '.form-footer a MUST carry display: inline-block so min-height + padding apply (inline elements ignore both).'
        );
    }

    #[Test]
    public function form_footer_anchor_rule_carries_min_height_44px(): void
    {
        $rule = $this->formFooterAnchorRule();
        $this->assertMatchesRegularExpression(
            '/min-height:\s*44px\s*;/',
            $rule,
            '.form-footer a MUST carry min-height: 44px (WCAG 2.5.5 Target Size AAA floor).'
        );
    }

    #[Test]
    public function form_footer_anchor_rule_carries_padding_12_16(): void
    {
        $rule = $this->formFooterAnchorRule();
        $this->assertMatchesRegularExpression(
            '/padding:\s*12px\s+16px\s*;/',
            $rule,
            '.form-footer a MUST carry padding: 12px 16px (vertical breathing + horizontal touch-area extension per designer spec).'
        );
    }

    #[Test]
    public function form_footer_anchor_rule_carries_line_height_20px(): void
    {
        $rule = $this->formFooterAnchorRule();
        $this->assertMatchesRegularExpression(
            '/line-height:\s*20px\s*;/',
            $rule,
            '.form-footer a MUST carry line-height: 20px to preserve the original visual text rhythm inside the enlarged tap zone.'
        );
    }

    #[Test]
    public function source_carries_ai_865_task_marker(): void
    {
        $source = $this->authSource();
        $this->assertStringContainsString('task-2026-05-18-2e95f2', $source, 'AI-865 task-id marker required for cross-surface grep.');
        $this->assertStringContainsString('AI-865', $source);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — .form-footer parent margin bump
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function form_footer_parent_margin_top_bumped_to_28px(): void
    {
        $source = $this->authSource();
        $parentStart = strpos($source, '.form-footer {');
        $this->assertNotFalse($parentStart);
        $parentEnd = strpos($source, '}', $parentStart);
        $this->assertNotFalse($parentEnd);
        $parentRule = substr($source, $parentStart, $parentEnd - $parentStart + 1);
        $this->assertMatchesRegularExpression(
            '/margin-top:\s*28px\s*;/',
            $parentRule,
            '.form-footer parent MUST carry margin-top: 28px (was 20px; designer optional polish accepted as default).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — AI-863 + AI-864 no-regression sentinels
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai863_route_urls_preserved(): void
    {
        $source = $this->authSource();
        $this->assertMatchesRegularExpression(
            '/<a\s+href="\{\{\s*route\(\'password\.request\'\)\s*\}\}"\s+class="forgot-password-link">/',
            $source,
            'AI-863 forgot-password-link route URL must stay intact post-AI-865.'
        );
        $count = preg_match_all('/<a\s+href="\{\{\s*route\(\'login\'\)\s*\}\}"\s+class="login-link">/', $source);
        $this->assertSame(2, $count, 'AI-863 login-link route URLs (2 instances) must stay intact post-AI-865.');
    }

    #[Test]
    public function ai864_autocomplete_counts_preserved(): void
    {
        $source = $this->stripBladeAndPhpComments($this->authSource());
        $this->assertSame(1, substr_count($source, 'autocomplete="current-password"'), 'AI-864 current-password count preserved.');
        $this->assertSame(2, substr_count($source, 'autocomplete="new-password"'), 'AI-864 new-password count preserved.');
        $this->assertSame(2, substr_count($source, 'autocomplete="email"'), 'AI-864 email count preserved.');
        $this->assertSame(2, substr_count($source, 'autocomplete="username"'), 'AI-864 username count preserved.');
        $this->assertSame(1, substr_count($source, 'autocomplete="name"'), 'AI-864 name count preserved.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — back-compat regression sentinels
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function original_anchor_color_and_text_decoration_preserved(): void
    {
        $rule = $this->formFooterAnchorRule();
        $this->assertMatchesRegularExpression(
            '/color:\s*#3b82f6\s*;/',
            $rule,
            'Original `.form-footer a` color #3b82f6 must stay intact (visual brand consistency).'
        );
        $this->assertMatchesRegularExpression(
            '/text-decoration:\s*none\s*;/',
            $rule,
            'Original `.form-footer a` text-decoration: none must stay intact.'
        );
    }

    #[Test]
    public function form_footer_anchor_hover_rule_preserved(): void
    {
        $source = $this->authSource();
        // The hover rule is a separate selector; AI-865 didn't touch it.
        $this->assertMatchesRegularExpression(
            '/\.form-footer\s+a:hover\s*\{[^}]*text-decoration:\s*underline\s*;[^}]*\}/s',
            $source,
            '.form-footer a:hover { text-decoration: underline; } rule must stay intact post-AI-865.'
        );
    }

    #[Test]
    public function form_footer_parent_text_align_and_font_size_preserved(): void
    {
        $source = $this->authSource();
        $parentStart = strpos($source, '.form-footer {');
        $this->assertNotFalse($parentStart);
        $parentEnd = strpos($source, '}', $parentStart);
        $parentRule = substr($source, $parentStart, $parentEnd - $parentStart + 1);
        $this->assertStringContainsString('text-align: center;', $parentRule, '.form-footer text-align: center must stay intact (centers the now-enlarged anchor).');
        $this->assertStringContainsString('font-size: 14px;', $parentRule, '.form-footer font-size: 14px must stay intact.');
    }
}
