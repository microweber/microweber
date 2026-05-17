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
        // The new shape: one anchor tag with href + class on the same line.
        $this->assertMatchesRegularExpression(
            '/<a\s+href="\{\{\s*site_url\(\)\s*\}\}"\s+class="logo-link">/',
            $this->template,
            'Logo anchor must render as a single tag with href + class on the same line (AI-805 Path A collapse).'
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

        // The literal `@if(is_live_edit())` directive must be gone
        // from executable Blade. (NOT just from prose -- the docblock
        // mentions it, but the stripped source must not.)
        $this->assertStringNotContainsString(
            '@if(is_live_edit())',
            $stripped,
            'Legacy `@if(is_live_edit())` directive must be gone from the executable Blade template (was the dead-conditional defect).'
        );
        // The other bare `@else` in the template is legitimate -- it's
        // the logoimage/text fallback chain's else-arm that emits the
        // "Click to add logo" placeholder. Don't assert against that.
        // Assert the SPECIFIC dead conditional shape: a `@if(...)`
        // containing `site_url()` followed by `@else` containing
        // `site_url()` (the signature of the dead branch -- both
        // arms returning the same value). After collapse, no @if
        // anywhere in the template should contain site_url() at all.
        $this->assertDoesNotMatchRegularExpression(
            '/@if\([^)]*\)[\s\S]*?site_url\(\)[\s\S]*?@else[\s\S]*?site_url\(\)[\s\S]*?@endif/',
            $stripped,
            'The specific dead-conditional shape (`@if(...) site_url() @else site_url() @endif`) must be gone from executable Blade. (The image/text fallback chain\'s legitimate `@else` is allowed.)'
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
    public function href_resolves_to_site_url(): void
    {
        // The collapsed anchor must point to site_url() (the
        // surviving arm's URL  pre-fix both arms returned this).
        $this->assertStringContainsString(
            'href="{{ site_url() }}"',
            $this->template,
            'Anchor href must point to site_url() (the surviving arm of the collapsed conditional).'
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
