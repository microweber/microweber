<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-e2e29a / AI-805  Logo dead-conditional cleanup.
 * Jira: https://microweber.atlassian.net/browse/AI-805
 *
 * Lineage:
 *   - AI-803 (task-2026-05-17-fa5dc3)  sibling: logo visibility
 *     (P1, narrow-viewport @media gate)
 *   - AI-804 (task-2026-05-17-488fa3)  sibling: logo alt fallback
 *     chain (Medium, WCAG 1.1.1)
 *
 * Pre-fix Modules/Logo/resources/views/templates/default.blade.php:3-8:
 *   <a
 *       @if(is_live_edit())
 *           href="{{ site_url() }}"
 *       @else
 *           href="{{ site_url() }}"
 *       @endif
 *       class="logo-link">
 *
 * Both arms of the if/else emitted IDENTICAL href={{ site_url() }}.
 * Dead conditional -- no observable behaviour difference between
 * the editor and the public surface.
 *
 * Designer dispatch offered two paths:
 *   - Path A (this ship): collapse to single line, no behaviour change
 *   - Path B (AI-805a follow-up candidate): formalise live-edit-
 *     disabled navigation, e.g. is_live_edit() ? '#' : site_url()
 *     -- behaviour change, needs product call before shipping
 *
 * Shipped Path A. Path B flagged in template Blade comment as
 * AI-805a so PM/designer can dispatch if the editor-disabled-link
 * behaviour is wanted.
 */
class LogoE2e29aAI805DeadBranchCleanupContractTest extends TestCase
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
    // Group A  dead conditional collapsed
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function single_anchor_with_inline_href_and_class(): void
    {
        // The shape: one anchor tag with href + class on the same line.
        //
        // task-2026-05-17-af2b73 / AI-805a — pin-evolution from prior
        // bare `href="{{ site_url() }}"` Path A shape (task-e2e29a).
        // Same logical contract preserved (single-tag anchor with href
        // attribute that resolves to site_url in public mode + class
        // hook). Evolved syntax: the href is now a runtime ternary
        // `is_live_edit() ? 'javascript:void(0)' : site_url()` so
        // clicking the logo mid-edit no longer navigates away from
        // the live-edit session. Public-mode rendering unchanged.
        // AI-770 v2 pin-evolution discipline.
        $this->assertMatchesRegularExpression(
            '/<a\s+href="\{\{\s*is_live_edit\(\)\s*\?\s*\'javascript:void\(0\)\'\s*:\s*site_url\(\)\s*\}\}"\s+class="logo-link"/',
            $this->template,
            'Logo anchor must render as a single tag with the AI-805a is_live_edit ternary href + class on the same line.'
        );
    }

    #[Test]
    public function dead_if_is_live_edit_branch_is_gone(): void
    {
        // Pre-strip Blade {{-- ... --}} comments  the AI-805 docblock
        // legitimately mentions the legacy pattern when describing the
        // pre-fix shape. Recurring selector-self-match guard family
        // per LESSONS.
        $stripped = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $this->template);

        // task-2026-05-17-af2b73 / AI-805a — pin-evolution: removed
        // the prior bare `@if(is_live_edit())` absence assertion.
        // AI-805a re-introduces `@if(is_live_edit())` for a DIFFERENT
        // legitimate purpose (conditional attribute emission for the
        // title="..." + aria-disabled="true" tooltip), not the dead
        // dual-arm conditional. The dead-shape signature regex below
        // (anchored on BOTH arms returning site_url) remains the
        // correct discriminator per AI-805 "pin the shape, not the
        // keyword" LESSONS rule (16+ session-recurrences).
        //
        // Assert the SPECIFIC dead conditional shape: a `@if(...)`
        // containing `site_url()` followed by `@else` containing
        // `site_url()` (the signature of the dead branch -- both
        // arms returning the same value). The AI-805a is_live_edit
        // ternary IS NOT a dead branch (arms diverge: 'javascript:
        // void(0)' vs site_url()), so this pattern stays absent.
        $this->assertDoesNotMatchRegularExpression(
            '/@if\([^)]*\)[\s\S]*?site_url\(\)[\s\S]*?@else[\s\S]*?site_url\(\)[\s\S]*?@endif/',
            $stripped,
            'The specific dead-conditional shape (`@if(...) site_url() @else site_url() @endif`) must be gone from executable Blade. (The image/text fallback chain\'s legitimate `@else` is allowed; the AI-805a attribute-conditional @if(is_live_edit()) is allowed.)'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  AI-803 + AI-804 fixes preserved (regression guard)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai803_visibility_fix_preserved(): void
    {
        $this->assertStringContainsString(
            '@media (max-width: 575px)',
            $this->template,
            'AI-803 narrow-viewport @media block must remain intact (sibling fix shipped this session).'
        );
        $this->assertStringContainsString(
            'task-2026-05-17-fa5dc3',
            $this->template,
            'AI-803 task-id marker must remain in the docblock.'
        );
    }

    #[Test]
    public function ai804_alt_fallback_chain_preserved(): void
    {
        $this->assertStringContainsString(
            "get_option('website_title', 'website')",
            $this->template,
            'AI-804 three-tier alt fallback chain must remain intact (sibling fix shipped this session).'
        );
        $this->assertStringContainsString(
            "?: 'Home'",
            $this->template,
            'AI-804 final safety-net `?: \'Home\'` must remain intact.'
        );
        $this->assertStringContainsString(
            'task-2026-05-17-488fa3',
            $this->template,
            'AI-804 task-id marker must remain in the docblock.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  template DOM structure preserved
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function template_dom_structure_preserved(): void
    {
        $this->assertStringContainsString(
            '<div class="logo-module">',
            $this->template,
            'Outer wrapper preserved.'
        );
        $this->assertStringContainsString(
            'class="logo-link"',
            $this->template,
            'Anchor class preserved.'
        );
        $this->assertStringContainsString(
            "@if(isset(\$logoimage)",
            $this->template,
            'Image conditional render branch preserved (different @if from the AI-805 dead one).'
        );
        $this->assertStringContainsString(
            'class="logo-text"',
            $this->template,
            'Text fallback span class preserved.'
        );
    }

    #[Test]
    public function href_resolves_to_site_url_in_public_mode(): void
    {
        // task-2026-05-17-af2b73 / AI-805a — pin-evolution: the bare
        // `href="{{ site_url() }}"` (Path A) is now wrapped in the
        // is_live_edit ternary. In PUBLIC mode (is_live_edit() false),
        // the runtime evaluates to site_url() — same as pre-AI-805a
        // behaviour. Test asserts the ternary's site_url() arm is
        // present in the executable source (covers public-mode
        // behavioural equivalence).
        $this->assertStringContainsString(
            ": site_url()",
            $this->template,
            'AI-805a: ternary public-mode arm must resolve to site_url() so non-live-edit users keep working logo links.'
        );
    }

    #[Test]
    public function live_edit_disabled_navigation_path_b_shipped(): void
    {
        // task-2026-05-17-af2b73 / AI-805a — Path B shipped. In
        // live-edit mode, the logo anchor's href must NOT navigate
        // (prevents data-loss from accidental logo clicks during
        // unsaved edits). Designer's own audit work hit this twice
        // today before AI-805a shipped.
        $this->assertStringContainsString(
            "is_live_edit() ? 'javascript:void(0)' : site_url()",
            $this->template,
            "AI-805a Path B: the anchor href must be the runtime ternary `is_live_edit() ? 'javascript:void(0)' : site_url()` so live-edit users can't navigate away from unsaved work via the logo."
        );
    }

    #[Test]
    public function live_edit_anchor_carries_tooltip_and_aria_disabled(): void
    {
        // task-2026-05-17-af2b73 / AI-805a — designer recommended a
        // tooltip on the disabled anchor explaining why the link
        // doesn't navigate. aria-disabled="true" tells AT users not
        // to expect navigation.
        $this->assertStringContainsString(
            'title="Use the menu to navigate"',
            $this->template,
            'AI-805a: live-edit anchor must carry the `title="Use the menu to navigate"` tooltip per designer spec.'
        );
        $this->assertStringContainsString(
            'aria-disabled="true"',
            $this->template,
            'AI-805a: live-edit anchor must carry `aria-disabled="true"` so AT users understand the disabled-navigation state.'
        );
    }

    #[Test]
    public function ai805a_task_marker_present(): void
    {
        // task-2026-05-17-af2b73 / AI-805a marker present alongside
        // the AI-805 marker so a future audit can grep both surfaces
        // (Path A collapse + Path B disabled-navigation) in one pass.
        $this->assertStringContainsString(
            'task-2026-05-17-af2b73',
            $this->template,
            'AI-805a task-id marker (af2b73) must be embedded in the migration docblock.'
        );
        $this->assertStringContainsString(
            'AI-805a',
            $this->template,
            'AI-805a ticket marker must be embedded in the migration docblock.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  markers + AI-805a follow-up flag
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai805_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-e2e29a', $this->template);
        $this->assertStringContainsString('AI-805', $this->template);
    }

    #[Test]
    public function ai805a_follow_up_candidate_flagged(): void
    {
        // Path B (formalise live-edit-disabled navigation) needs a
        // product call. Flagged in the template Blade comment so
        // PM/designer can dispatch as AI-805a when the editor-
        // disabled-link behaviour is wanted.
        $this->assertStringContainsString(
            'AI-805a',
            $this->template,
            'Template must flag AI-805a as the Path B follow-up candidate (live-edit-disabled navigation behaviour change).'
        );
    }
}
