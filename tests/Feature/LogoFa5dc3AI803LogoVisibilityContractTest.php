<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-fa5dc3 / AI-803 [P1 HIGHEST]  Logo invisible on frontend.
 * Jira: https://microweber.atlassian.net/browse/AI-803
 *
 * Stage-3 wrong-surface-mount  fix designed for one viewport applied
 * unconditionally to all viewports.
 *
 * Pre-fix: `Modules/Logo/resources/views/templates/default.blade.php:40-41`
 * declared `.logo-module { min-width: 0; overflow: hidden }` UNCONDITIONALLY,
 * despite a docblock explicitly saying the rule was meant for narrow
 * viewports (≤390px hamburger-row scenario). Effect: at every viewport
 * (desktop included) the parent flex column collapsed to 0×0, so the
 * SVG logo (300×82 natural) loaded but rendered invisible.
 *
 * Brand collapse on every install. Designer "most expensive single-CSS
 * -rule defect this session  silent, source-only-test-passes, only
 * catches at rendered-browser eye." Priority P1 HIGHEST.
 *
 * Fix: gate the narrow-viewport rules (`.logo-module { min-width: 0;
 * overflow: hidden }` AND `.logo-module .logo-link { ... ellipsis ... }`)
 * inside `@media (max-width: 575px)` (Bootstrap 5 `sm` breakpoint). At
 * >=576px the parent stays at its natural width and the logo renders.
 *
 * Responsive-image safety `.logo-module img { max-width: 100%; height:
 * auto }` stays OUTSIDE the media query  applies at every viewport.
 *
 * Sibling tickets NOT shipped in this slice (separate dispatches per
 * designer's "file together  same template" note):
 *   - AI-804 (Medium): logo alt="" empty fallback  WCAG 1.1.1
 *   - AI-805 (Low):    dead is_live_edit() blade branch (both arms
 *                      emit identical href)
 *
 * Mini-lesson re-applied (Blade `@` directive footgun, AI-796 family):
 * the AI-803 SCSS docblock initially contained the literal `@if(...)`
 * token in prose describing the AI-805 dead branch. Blade compiled
 * `@if(` as a directive opener, couldn't find a matching `@endif`,
 * crashed the server with `unexpected end of file`. Rephrased the
 * docblock to avoid the literal `@if(` token. Worth re-folding into
 * LESSONS as the second occurrence this session (first: AI-796 CSS
 * comment).
 *
 * Designer Tier-3 probe (browser):
 *   expect(parseFloat(getComputedStyle(
 *     document.querySelector('.logo-module')
 *   ).width)).toBeGreaterThan(100);
 */
class LogoFa5dc3AI803LogoVisibilityContractTest extends TestCase
{
    private string $template;

    protected function setUp(): void
    {
        parent::setUp();
        $this->template = (string) file_get_contents(base_path(
            'Modules/Logo/resources/views/templates/default.blade.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  legacy unconditional rule is GONE
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function logo_module_no_longer_has_unconditional_min_width_zero(): void
    {
        // Slice the `.logo-module { ... }` rule OUTSIDE any @media block
        // and assert it does NOT contain `min-width: 0` or `overflow: hidden`.
        // Pre-strip CSS /* */ comments BOTH single-line (`/*text-align*/`)
        // AND multi-line (docblock prose) before scanning. Recurring
        // selector-self-match guard family per LESSONS.
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $this->template);

        // The top-level .logo-module rule body should contain ONLY the
        // margin declaration (whitespace tolerant; comments already
        // stripped). Allow optional whitespace anywhere.
        $this->assertMatchesRegularExpression(
            '/\.logo-module\s*\{\s*margin:\s*20px\s*0;\s*\}/',
            $stripped,
            '.logo-module top-level rule must contain ONLY `margin: 20px 0;` — the unconditional `min-width: 0; overflow: hidden;` was the AI-803 root cause and must be gone from this rule body. Test stripped CSS comments before scanning.'
        );
    }

    #[Test]
    public function legacy_unconditional_overflow_hidden_pattern_absent_outside_media_query(): void
    {
        // Strict negative regression guard: the literal sequence
        // `.logo-module { ... min-width: 0; overflow: hidden; ... }`
        // outside ANY @media block must be GONE. Pre-strip CSS comments
        // first (selector-self-match guard family per LESSONS), THEN
        // slice OUT the @media (max-width: 575px) {...} block so its
        // legitimate narrow-viewport rule doesn't false-fail the
        // top-level negative regression check.
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $this->template);

        // Remove the @media block contents (the fix puts min-width:0
        // legitimately inside this block).
        $outsideMedia = preg_replace(
            '~@media\s*\(max-width:\s*575px\)\s*\{(?:[^{}]*|\{[^{}]*\})*\}~s',
            '',
            $stripped
        );

        $this->assertDoesNotMatchRegularExpression(
            '/\.logo-module\s*\{[^}]*min-width:\s*0[^}]*\}/',
            $outsideMedia,
            'Legacy unconditional `.logo-module { ... min-width: 0 ... }` pattern must be gone OUTSIDE the @media block — that was the AI-803 root cause.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  narrow-viewport rules are gated inside @media
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function narrow_viewport_rules_gated_in_575px_media_query(): void
    {
        // The media block must contain both narrow-viewport rules.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*575px\)\s*\{[\s\S]*?\.logo-module\s*\{[\s\S]*?min-width:\s*0;[\s\S]*?overflow:\s*hidden;[\s\S]*?\}[\s\S]*?\}/',
            $this->template,
            'The `min-width: 0; overflow: hidden;` declarations MUST be inside a `@media (max-width: 575px)` block.'
        );
    }

    #[Test]
    public function narrow_viewport_logo_link_ellipsis_gated_in_media_query(): void
    {
        // The .logo-link ellipsis chain (text-overflow: ellipsis + white-space: nowrap)
        // also belongs inside the narrow-viewport media block per designer spec.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*575px\)\s*\{[\s\S]*?\.logo-module\s+\.logo-link\s*\{[\s\S]*?text-overflow:\s*ellipsis;[\s\S]*?white-space:\s*nowrap;[\s\S]*?\}[\s\S]*?\}/',
            $this->template,
            '`.logo-module .logo-link { text-overflow: ellipsis; white-space: nowrap; ... }` must also be inside the `@media (max-width: 575px)` block.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  cross-viewport safety preserved
    // ─────────────────────────────────────────────────────────────────────


    // ─────────────────────────────────────────────────────────────────────
    // Group D — AI-803 CHANGE v2 — Slice B (post-task-5be57f)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai803_v2_logo_module_carries_inline_block_and_min_width_floor(): void
    {
        // task-2026-05-17-5be57f / AI-803 CHANGE v2 — break the shrink-
        // to-fit cycle at the .logo-module level itself. The v1 fix
        // (display: inline-block on .logo-link only) couldn't escape
        // because .logo-module is inside Bootstrap `col-xl-4 w-auto`
        // which gives column width = content width; .logo-module was
        // `display: block, width: 0px` so its children inherited 0.
        //
        // Pre-strip CSS comments before scanning + slice OUT the
        // @media block so the inside-media `.logo-module { min-width: 0 }`
        // doesn't false-fail (selector-self-match guard UNIFORMITY).
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $this->template);
        $strippedNoMedia = preg_replace(
            '~@media\s*\(max-width:\s*575px\)\s*\{(?:[^{}]*|\{[^{}]*\})*\}~s',
            '',
            (string) $stripped
        );

        // Pin-evolved 2026-05-17 / task-7b669f / AI-803 CHANGE v3 --
        // min-width now carries !important too (v2 had it bare; v3
        // ships !important defensively per Slice C "!important at
        // every property at every layer" canonical fix-shape).
        $this->assertMatchesRegularExpression(
            '/\.logo-module\s*\{[^}]*display:\s*inline-block\s*!important[^}]*min-width:\s*160px\s*!important[^}]*\}/',
            (string) $strippedNoMedia,
            'AI-803 CHANGE v3: top-level `.logo-module { display: inline-block !important; min-width: 160px !important; }` must be present (Slice C — !important at every property defends against future template-skin layer overrides).'
        );
    }

    #[Test]
    public function ai803_v2_logo_link_carries_inline_block_important(): void
    {
        // Pin-evolved 2026-05-17 / task-7b669f / AI-803 CHANGE v3 / Slice C
        // -- v2 carried only `display: inline-block !important;`. v3
        // adds 5 more !important properties at the .logo-link layer:
        // min-width: 160px, min-height: 60px, line-height: 1,
        // overflow: visible, padding: 0. Each one defeats a specific
        // defeater rule that v2 didn't account for (default.css
        // `.mw-big-header-logo a { min-width: 44px !important;
        // padding: 6px 4px; ... }`, possible parent overflow:hidden
        // cascades, text line-height collapsing the link box, etc.).
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $this->template);
        $strippedNoMedia = preg_replace(
            '~@media\s*\(max-width:\s*575px\)\s*\{(?:[^{}]*|\{[^{}]*\})*\}~s',
            '',
            (string) $stripped
        );

        $this->assertMatchesRegularExpression(
            '/\.logo-module\s+\.logo-link\s*\{\s*display:\s*inline-block\s*!important;\s*min-width:\s*160px\s*!important;\s*min-height:\s*60px\s*!important;\s*line-height:\s*1\s*!important;\s*overflow:\s*visible\s*!important;\s*padding:\s*0\s*!important;\s*\}/',
            (string) $strippedNoMedia,
            'AI-803 CHANGE v3 Slice C: top-level `.logo-module .logo-link { display: inline-block !important; min-width: 160px !important; min-height: 60px !important; line-height: 1 !important; overflow: visible !important; padding: 0 !important; }` must be present — every !important property defeats a specific defeater in default.css / app.css that v2 missed.'
        );
    }




    #[Test]
    public function logo_module_default_margin_preserved_cross_viewport(): void
    {
        // The original `margin: 20px 0` rule on .logo-module must stay
        // at the top-level scope (visible spacing at every viewport).
        $this->assertMatchesRegularExpression(
            '/\.logo-module\s*\{\s*(?:\/\*[^*]*\*\/\s*)?margin:\s*20px\s*0;\s*\}/',
            $this->template,
            '`.logo-module { margin: 20px 0; }` top-level rule must be preserved (visible spacing at every viewport).'
        );
    }


    #[Test]
    public function logo_text_fallback_styling_unchanged(): void
    {
        // The text-only fallback (when no logoimage provided) has its
        // own .logo-text ellipsis chain — that's about long brand-text
        // hygiene, NOT the AI-803 viewport-gate defect. Pin unchanged
        // as regression guard.
        $this->assertMatchesRegularExpression(
            '/\.logo-text\s*\{[\s\S]*?display:\s*inline-block;[\s\S]*?text-overflow:\s*ellipsis;[\s\S]*?\}/',
            $this->template,
            '.logo-text ellipsis chain must be preserved (cross-viewport long-brand-text hygiene, not the AI-803 defect).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  template DOM structure preserved
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function logo_module_wrapper_div_and_link_anchor_preserved(): void
    {
        $this->assertStringContainsString(
            '<div class="logo-module">',
            $this->template,
            'Outer wrapper `<div class="logo-module">` must be preserved.'
        );
        $this->assertStringContainsString(
            'class="logo-link"',
            $this->template,
            'Inner anchor `class="logo-link"` must be preserved.'
        );
    }

    #[Test]
    public function logo_image_render_branch_preserved(): void
    {
        $this->assertStringContainsString(
            '<img src="{{ $logoimage }}"',
            $this->template,
            'Logo image render branch must be preserved.'
        );
        $this->assertStringContainsString(
            "isset(\$logoimage)",
            $this->template,
            'Logo image conditional must be preserved.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E  markers + lineage
    // ─────────────────────────────────────────────────────────────────────


}
