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

    #[Test]
    public function responsive_image_safety_stays_outside_media_query(): void
    {
        // Pin-evolved 2026-05-17 / task-5be57f / AI-803 CHANGE v2 —
        // designer's deep Tier-3 verify on the v1 ship (task-5b0a92)
        // found the img STILL rendered 0×0. Two reasons: (1) active-
        // template skin selector `.header-background.mw-menu-skin-com
        // .mw-big-header-logo a { display: flex }` (specificity 0,3,1)
        // beat the AI-803 v1 `.logo-link { display: inline-block }`
        // (0,2,0); (2) shrink-to-fit cycle moved up one DOM level to
        // `.logo-module` inside Bootstrap `col-xl-4 w-auto` column.
        // Slice B fix (mirrors AI-848 Stage-2 sub-variant 4 `CSS-rules-
        // mutual-dependency`): explicit dimensions on img break the
        // cycle independently of any parent's computed width. img rule
        // is now `width: auto; height: 60px; max-width: 100%;` — the
        // explicit `height: 60px` lets the SVG intrinsic ratio resolve
        // width regardless of parent shrink-to-fit cascade.
        // Updated in place per pin-evolution discipline (AI-770 v2 /
        // AI-805 Path B).
        $this->assertMatchesRegularExpression(
            '/\.logo-module\s+img\s*\{\s*width:\s*auto;\s*height:\s*60px;\s*max-width:\s*100%;\s*\}/',
            $this->template,
            '`.logo-module img { width: auto; height: 60px; max-width: 100% }` must be present as a top-level rule (AI-803 CHANGE v2 Slice B — explicit dimensions break the shrink-to-fit cycle independently of any parent computed width).'
        );

        // Pre-strip CSS comments before strpos scanning the docblock
        // prose mentions `.logo-module img` and `@media (max-width: 575px)`
        // in the rationale text; un-stripped scan finds the docblock
        // occurrences first instead of the rule positions. Recurring
        // selector-self-match guard family per LESSONS.
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $this->template);

        // Find the img RULE and the media block, verify img is NOT inside.
        $imgPos = strpos($stripped, '.logo-module img');
        $mediaStart = strpos($stripped, '@media (max-width: 575px)');
        $this->assertNotFalse($imgPos);
        $this->assertNotFalse($mediaStart);

        // The img rule must appear BEFORE the media block (top-level scope).
        $this->assertLessThan(
            $mediaStart,
            $imgPos,
            '.logo-module img rule must appear OUTSIDE/BEFORE the @media block so responsive-image rule applies cross-viewport.'
        );
    }

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

        // The new rule must declare display: inline-block !important +
        // min-width: 160px outside the @media block.
        $this->assertMatchesRegularExpression(
            '/\.logo-module\s*\{[^}]*display:\s*inline-block\s*!important[^}]*min-width:\s*160px[^}]*\}/',
            (string) $strippedNoMedia,
            'AI-803 CHANGE v2: top-level `.logo-module { display: inline-block !important; min-width: 160px; }` must be present (breaks shrink-to-fit cycle at the .logo-module level + reasonable floor for any brand mark).'
        );
    }

    #[Test]
    public function ai803_v2_logo_link_carries_inline_block_important(): void
    {
        // task-2026-05-17-5be57f / AI-803 CHANGE v2 — the !important
        // qualifier is required to defeat the active-template skin's
        // `.header-background.mw-menu-skin-com .mw-big-header-logo a
        // { display: flex }` 0,3,1 selector that wins on specificity.
        //
        // Pin evolved from v1 shape `display: inline-block;` (without
        // !important) to v2 shape `display: inline-block !important;`.
        // Updated in place per pin-evolution discipline (AI-770 v2 /
        // AI-805 Path B).
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $this->template);
        $strippedNoMedia = preg_replace(
            '~@media\s*\(max-width:\s*575px\)\s*\{(?:[^{}]*|\{[^{}]*\})*\}~s',
            '',
            (string) $stripped
        );

        $this->assertMatchesRegularExpression(
            '/\.logo-module\s+\.logo-link\s*\{\s*display:\s*inline-block\s*!important;\s*\}/',
            (string) $strippedNoMedia,
            'AI-803 CHANGE v2: top-level `.logo-module .logo-link { display: inline-block !important; }` must be present — !important defeats the active-template skin layer that ships `.header-background.mw-menu-skin-com .mw-big-header-logo a { display: flex }` at specificity 0,3,1.'
        );
    }

    #[Test]
    public function ai803_v2_change_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-17-5be57f / AI-803 CHANGE v2',
            $this->template,
            'AI-803 CHANGE v2: marker `task-2026-05-17-5be57f / AI-803 CHANGE v2` must be embedded in the migration docblock so future audits grep all three AI-803 cycles (fa5dc3 v0 + 5b0a92 v1 + 5be57f v2) in one pass.'
        );
    }

    #[Test]
    public function ai803_v2_cites_ai848_sister_pattern(): void
    {
        // The AI-803 v2 Slice B mirrors AI-848 Slice A (auth-header
        // `.mw-auth-logo` shrink-to-fit cycle). 3rd-instance recurrence
        // of Stage-2 sub-variant 4 (CSS-rules-mutual-dependency). The
        // docblock must cite AI-848 so future audits see the family
        // lineage in one pass.
        $this->assertStringContainsString(
            'AI-848',
            $this->template,
            'AI-803 CHANGE v2 docblock must cite AI-848 as sister-pattern (same Stage-2 sub-variant 4 — CSS-rules-mutual-dependency shrink-to-fit cycle, just at a different DOM level).'
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
    public function logo_link_display_inline_block_top_level_for_parent_flex_collapse_fix(): void
    {
        // task-2026-05-17-5b0a92 / AI-803 CHANGE — designer's tier-3 desktop
        // probe found the img still rendered at 0×0 post-AI-803 ship because
        // the parent template wrapper's `col-xl-4 w-auto` Bootstrap class
        // combo collapses parent width to content width. Without
        // `display: inline-block` on `.logo-link`, the anchor defaulted to
        // `display: inline` and collapsed to 0, cascading the img's
        // `max-width: 100%` to 0×0 despite natural 300×82.
        //
        // Designer's Option A fix: top-level `.logo-module .logo-link {
        // display: inline-block }` so the anchor wraps to img natural width
        // regardless of parent flex context. Mirrors the same declaration
        // already inside the ≤575px block from task-fa5dc3.
        //
        // Stage-2 cascade-loss family pattern: source change correct
        // (task-fa5dc3 @media gate), but a sibling rule needed runtime
        // adjustment. Sibling to AI-697 v3 + AI-786 v2.
        //
        // Pin the SHAPE: top-level rule exists + carries `display: inline-block`.
        // Pre-strip CSS comments first so the migration docblock's prose
        // (mentioning `.logo-link` + `display: inline-block` cross-references)
        // doesn't false-pass — selector-self-match guard family.
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $this->template);

        // Also strip the @media block so the @media-internal .logo-link
        // rule from task-fa5dc3 (which also has display: inline-block)
        // doesn't false-pass as top-level.
        $strippedNoMedia = preg_replace(
            '~@media\s*\(max-width:\s*575px\)\s*\{(?:[^{}]*|\{[^{}]*\})*\}~s',
            '',
            (string) $stripped
        );

        // Pin-evolved 2026-05-17 / task-5be57f / AI-803 CHANGE v2 —
        // the !important qualifier is required to defeat the active-
        // template skin layer (specificity 0,3,1 selector beats the v1
        // 0,2,0 selector). Updated in place per pin-evolution discipline.
        // The v2 shape is asserted in the dedicated Group D test
        // `ai803_v2_logo_link_carries_inline_block_important`; this
        // method's assertion is the cross-reference that pins the
        // pre-v2 shape NO LONGER exists (negative-pin counterpart).
        // The v1 shape (no `!important`) MUST be gone from top-level
        // scope — only the v2 shape (with `!important`) should match.
        $this->assertDoesNotMatchRegularExpression(
            '/\.logo-module\s+\.logo-link\s*\{\s*display:\s*inline-block;\s*\}/',
            (string) $strippedNoMedia,
            'AI-803 CHANGE v2: the v1 shape `.logo-module .logo-link { display: inline-block; }` (WITHOUT !important) must NOT exist at top-level scope — v2 requires `!important` to defeat active-template skin specificity. Pin-evolved per AI-770 v2 / AI-805 Path B discipline.'
        );

        // AI-803 CHANGE markers present in the source — enables future
        // audit grep across all 3 AI-803 cycles (fa5dc3 v0 + 5b0a92 v1 +
        // 5be57f v2).
        $this->assertStringContainsString(
            'task-2026-05-17-5b0a92 / AI-803 CHANGE',
            $this->template,
            'AI-803 CHANGE marker must be embedded in the migration docblock so a future audit can grep both the original task-fa5dc3 ship AND the CHANGE cycle in a single pass.'
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

    #[Test]
    public function task_id_and_ai803_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-fa5dc3', $this->template);
        $this->assertStringContainsString('AI-803', $this->template);
    }

    #[Test]
    public function docblock_cites_sibling_tickets_for_audit_trail(): void
    {
        // Designer's dispatch flagged AI-804 + AI-805 as same-template
        // siblings. Cite them in the docblock so future audits find
        // the full ticket-family via grep.
        $this->assertStringContainsString(
            'AI-804',
            $this->template,
            'Docblock must cite AI-804 (sibling alt="" WCAG ticket on the same template).'
        );
        $this->assertStringContainsString(
            'AI-805',
            $this->template,
            'Docblock must cite AI-805 (sibling dead-blade-branch ticket on the same template).'
        );
    }
}
