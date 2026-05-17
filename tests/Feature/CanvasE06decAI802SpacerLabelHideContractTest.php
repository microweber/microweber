<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-e06dec / AI-802  spacer label chrome leak fix.
 * Jira: https://microweber.atlassian.net/browse/AI-802
 *
 * Pre-fix: every `.mw-le-spacer` on the live-edit canvas rendered
 * its height value ("20px") as visible text via the live-edit-spacer.js
 * DOM-injected `<span class="mw-le-spacer-info-content">` child.
 * Designer audit: home demo had 16 spacers -> 16 "20px" labels
 * scattered between content blocks (Heuristic-1 eye-flow failure).
 *
 * Frontend boundary confirmed CLEAN by designer probe — labels are
 * editor-only (live-edit-spacer.js doesn't run on the public surface).
 * Priority stayed Medium for that reason.
 *
 * Root cause: the existing `.mw-le-resizable .mw-le-spacer-info-content
 * { opacity: 0 }` rule didn't fire because (a) the Resizable instance
 * may not stamp `.mw-le-resizable` on every spacer node, AND (b) the
 * served minified CSS has a bare `.mw-le-spacer-info-content {
 * opacity: 1 }` rule (collapsed from `.mw-le-resizable:hover .mw-le
 * -spacer-info-content`) that wins over the scoped default-hide.
 *
 * Fix (Slice A): add an explicit unconditional `.mw-le-spacer-info-content
 * { opacity: 0 }` default that doesn't depend on `.mw-le-resizable`,
 * with three show-triggers:
 *   1. `.mw-le-spacer:hover` (editor hover discovery)
 *   2. `.mw-le-spacer.mw-le-spacer-resizing` (active resize feedback)
 *   3. `body.mw-editmode-show-spacer-labels` (designer-spec opt-in)
 * plus `prefers-reduced-motion: reduce` collapse.
 *
 * Slice B (deferred AI-802a): wire the `.mw-editmode-show-spacer-labels`
 * body-class toggle into Theme Settings UI -- new affordance ticket;
 * out of scope for this Medium chrome polish.
 */
class CanvasE06decAI802SpacerLabelHideContractTest extends TestCase
{
    private string $scss;
    private string $cssBundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scss = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/css/scss/liveedit.scss'
        ));
        $bundlePath = base_path('packages/frontend-assets/resources/dist/build/liveedit.css');
        $this->cssBundle = file_exists($bundlePath) ? (string) file_get_contents($bundlePath) : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  SCSS source carries the AI-802 hide-by-default rule
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function scss_declares_unconditional_opacity_zero_default_for_info_content(): void
    {
        // The fix relies on a BARE `.mw-le-spacer-info-content { opacity: 0 }`
        // that doesn't depend on `.mw-le-resizable` parent — overrides
        // the served-CSS collapsed `.mw-le-spacer-info-content { opacity: 1 }`
        // bug noted in the docblock.
        $this->assertMatchesRegularExpression(
            '/\.mw-le-spacer-info-content\s*\{\s*opacity:\s*0;\s*transition:\s*opacity\s*\.2s\s*ease;?\s*\}/',
            $this->scss,
            'liveedit.scss must declare an unconditional `.mw-le-spacer-info-content { opacity: 0; transition: opacity .2s ease }` rule that wins over any earlier opacity:1 leak.'
        );
    }

    #[Test]
    public function scss_show_triggers_include_hover_resize_and_body_class(): void
    {
        // Three independent show paths, comma-grouped in one rule for
        // single-source-of-truth.
        $this->assertStringContainsString(
            '.mw-le-spacer:hover .mw-le-spacer-info-content',
            $this->scss,
            'liveedit.scss must include `.mw-le-spacer:hover .mw-le-spacer-info-content` as a show-trigger.'
        );
        $this->assertStringContainsString(
            '.mw-le-spacer.mw-le-spacer-resizing .mw-le-spacer-info-content',
            $this->scss,
            'liveedit.scss must include `.mw-le-spacer.mw-le-spacer-resizing .mw-le-spacer-info-content` as a show-trigger.'
        );
        $this->assertStringContainsString(
            'body.mw-editmode-show-spacer-labels .mw-le-spacer-info-content',
            $this->scss,
            'liveedit.scss must include `body.mw-editmode-show-spacer-labels .mw-le-spacer-info-content` as a designer-spec opt-in show-trigger.'
        );
        // All three must resolve to opacity:1 in the same rule block.
        $this->assertMatchesRegularExpression(
            '/(?:\.mw-le-spacer:hover|\.mw-le-spacer\.mw-le-spacer-resizing|body\.mw-editmode-show-spacer-labels)\s+\.mw-le-spacer-info-content[\s\S]{0,400}\{\s*opacity:\s*1;?\s*\}/',
            $this->scss,
            'The grouped show-trigger rule must set `opacity: 1` for any of the three trigger selectors.'
        );
    }

    #[Test]
    public function scss_carries_reduced_motion_guard(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(prefers-reduced-motion:\s*reduce\)\s*\{\s*\.mw-le-spacer-info-content\s*\{\s*transition:\s*none;?\s*\}\s*\}/',
            $this->scss,
            'liveedit.scss must include a `@media (prefers-reduced-motion: reduce)` block that collapses the .mw-le-spacer-info-content transition.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  served CSS bundle carries the new rules
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_carries_unconditional_opacity_zero_default(): void
    {
        if ($this->cssBundle === '') {
            $this->markTestSkipped('Served liveedit.css bundle absent.');
        }
        // The new bare-selector hide rule must be present (minified shape).
        $this->assertStringContainsString(
            '.mw-le-spacer-info-content{opacity:0;transition:opacity .2s ease}',
            $this->cssBundle,
            'Served liveedit.css bundle must carry the AI-802 hide-by-default rule.'
        );
    }

    #[Test]
    public function bundle_carries_three_show_trigger_rule(): void
    {
        if ($this->cssBundle === '') {
            $this->markTestSkipped('Served liveedit.css bundle absent.');
        }
        // The 3-selector show rule, minified into a comma list.
        $this->assertStringContainsString(
            '.mw-le-spacer:hover .mw-le-spacer-info-content',
            $this->cssBundle
        );
        $this->assertStringContainsString(
            '.mw-le-spacer.mw-le-spacer-resizing .mw-le-spacer-info-content',
            $this->cssBundle
        );
        $this->assertStringContainsString(
            'body.mw-editmode-show-spacer-labels .mw-le-spacer-info-content',
            $this->cssBundle
        );
    }

    #[Test]
    public function bundle_ai802_rules_appear_after_legacy_opacity_one_leak(): void
    {
        // The AI-802 default-hide rule MUST appear AFTER the existing
        // `.mw-le-spacer-info-content,...{opacity:1}` leaked rule so it
        // wins the same-specificity tiebreak via source order. This is
        // the actual mechanism that fixes the visible-by-default bug.
        if ($this->cssBundle === '') {
            $this->markTestSkipped('Served liveedit.css bundle absent.');
        }
        $legacyLeakIdx = strpos(
            $this->cssBundle,
            '.mw-le-spacer-info-content,.mw-le-resizable.mw-le-spacer-resizing .mw-le-spacer-info-content{opacity:1}'
        );
        $newHideIdx = strpos(
            $this->cssBundle,
            '.mw-le-spacer-info-content{opacity:0;transition:opacity .2s ease}'
        );
        $this->assertNotFalse($newHideIdx, 'New AI-802 hide rule must be present in the bundle.');
        if ($legacyLeakIdx !== false) {
            $this->assertGreaterThan(
                $legacyLeakIdx,
                $newHideIdx,
                'AI-802 hide rule must appear AFTER the legacy opacity:1 leak so it wins source-order tiebreak (same single-class specificity).'
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  JS source contract (regression guard)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function spacer_js_still_emits_info_content_node_for_resize_feedback(): void
    {
        // The hide-by-default fix REQUIRES the JS-injected
        // `<span class="mw-le-spacer-info-content">` to stay in the DOM
        // — it's what holds the "{height}px" text shown during resize.
        // Pin both spacer-js files (sibling locations) as regression
        // guard so future refactors don't drop the node.
        foreach ([
            'packages/frontend-assets/resources/assets/api-core/services/components/live-edit/live-edit-spacer.js',
            'packages/frontend-assets/resources/assets/live-edit/live-edit-spacer.js',
        ] as $relativePath) {
            $src = (string) file_get_contents(base_path($relativePath));
            $this->assertStringContainsString(
                "nodeInfoContent.className = 'mw-le-spacer-info-content'",
                $src,
                basename($relativePath) . ' must still create the .mw-le-spacer-info-content child node (AI-802 CSS hides it; the DOM stays).'
            );
            $this->assertStringContainsString(
                "nodeInfoContent.textContent = data.height + 'px'",
                $src,
                basename($relativePath) . ' must still write the height value into .mw-le-spacer-info-content textContent (resize-feedback path).'
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  markers + AI-802a follow-up flag
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai802_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-e06dec', $this->scss);
        $this->assertStringContainsString('AI-802', $this->scss);
    }

    #[Test]
    public function scss_docblock_flags_ai802a_follow_up(): void
    {
        $this->assertStringContainsString(
            'AI-802a',
            $this->scss,
            'liveedit.scss docblock must flag AI-802a (wire Theme Settings toggle) as the follow-up so PM can dispatch when Theme Settings UI is ready.'
        );
    }
}
