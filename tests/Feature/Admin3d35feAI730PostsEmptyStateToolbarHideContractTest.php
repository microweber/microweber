<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-3d35fe / AI-730 — Posts table toolbar
 * (sort/filter/search/density) renders above empty list. Jira:
 *   https://microweber.atlassian.net/browse/AI-730
 *
 * Designer dispatch 2026-05-16T15:11:16: when `count === 0` the
 * full Filament toolbar still renders — sort arrows, Categories
 * pill, empty search, list-density, filter with bogus "0" badge.
 * All five controls operate on rows that don't exist.
 *
 * Fix (Option A per dispatch): hide the toolbar entirely when
 * empty. Filament's EmptyState pattern supports the empty-state
 * slot but the toolbar still renders by default.
 *
 * Implementation: marker class `.mw-admin-empty-state-posts` +
 * `data-mw-empty-state="posts"` attribute rendered ONLY by the
 * Post branch of `empty-state.blade.php`, picked up by a `:has()`
 * CSS rule in `general-styles.css` that hides the table toolbar
 * sibling under the same `.fi-ta` ancestor.
 *
 * Browser support: `:has()` is in Chrome 105+, Firefox 121+,
 * Safari 15.4+. Filament v5 requires modern browsers already.
 *
 * Closes the AI-728 + AI-729 + AI-730 empty-state slice — three
 * tickets, one cohesive empty state: no wrong illustration, clean
 * verb-led copy + hierarchy, no toolbar noise.
 */
class Admin3d35feAI730PostsEmptyStateToolbarHideContractTest extends TestCase
{
    private string $emptyState;
    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emptyState = (string) file_get_contents(base_path(
            'Modules/Content/resources/views/filament/admin/empty-state.blade.php'
        ));
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — marker class + data attribute in Post branch
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function post_branch_wraps_in_marker_class(): void
    {
        // Slice the Post branch only.
        $start = strpos($this->emptyState, '@if($modelName == Modules\Post\Models\Post::class)');
        $end = strpos($this->emptyState, '@endif', $start);
        $postBranch = substr($this->emptyState, $start, $end - $start);

        $this->assertMatchesRegularExpression(
            '/<div\s+class="mw-admin-empty-state-posts"\s+data-mw-empty-state="posts">/',
            $postBranch,
            'Post branch must wrap its heading+explainer+CTA cluster in <div class="mw-admin-empty-state-posts" data-mw-empty-state="posts"> — the AI-730 marker.'
        );
    }

    #[Test]
    public function marker_class_does_not_appear_in_other_branches(): void
    {
        // The marker is Posts-specific by design. It should only
        // appear in the Post branch (at least once for the div).
        $occurrences = substr_count($this->emptyState, 'mw-admin-empty-state-posts');
        $this->assertGreaterThanOrEqual(
            1,
            $occurrences,
            'Marker class mw-admin-empty-state-posts must appear at least once in the Post branch.'
        );
        // Must NOT appear in other branches — extract content
        // outside the Post branch and verify no spurious occurrences.
        $start = strpos($this->emptyState, '@if($modelName == Modules\Post\Models\Post::class)');
        $end = strpos($this->emptyState, '@endif', $start);
        $outsidePost = substr($this->emptyState, 0, $start) . substr($this->emptyState, $end);
        $this->assertStringNotContainsString(
            'mw-admin-empty-state-posts',
            $outsidePost,
            'Marker class mw-admin-empty-state-posts must not appear outside the Post branch.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — CSS rule shape + scope
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function css_rule_uses_has_selector_to_hide_toolbar(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta:has\(\.mw-admin-empty-state-posts\)\s+\.fi-ta-header-toolbar\s*\{[^}]*display:\s*none\s*!important/i',
            $this->css,
            'CSS must declare `body.fi-panel-admin .fi-ta:has(.mw-admin-empty-state-posts) .fi-ta-header-toolbar { display: none !important; }` per AI-730 Option A.'
        );
    }

    #[Test]
    public function css_rule_is_scoped_to_admin_panel(): void
    {
        // Defensive scope guard — the rule must NOT fire on the
        // checkout / profile panels even if a future page used the
        // marker class. body.fi-panel-admin prefix is the canonical
        // admin-scope handle.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta:has\(\.mw-admin-empty-state-posts\)/',
            $this->css,
            'AI-730 rule must be scoped to body.fi-panel-admin so it never fires on checkout/profile panels.'
        );
    }

    #[Test]
    public function css_rule_does_not_target_other_table_elements(): void
    {
        // Bounded scope guard — the rule must affect ONLY
        // .fi-ta-header-toolbar, not the entire .fi-ta or any
        // other Filament element. Slice the rule + assert only
        // header-toolbar appears as the target.
        $start = strpos($this->css, 'task-2026-05-16-3d35fe');
        $this->assertNotFalse($start, 'AI-730 docblock marker must be present in general-styles.css.');
        // Walk forward to the closing brace of the rule (next `}`
        // after the rule opens; we look for `display: none`).
        $end = strpos($this->css, '}', $start);
        $this->assertNotFalse($end);
        $slice = substr($this->css, $start, $end - $start);

        // Slice must contain the toolbar selector + display:none
        // but NOT any other table-element hiding.
        $this->assertStringContainsString('.fi-ta-header-toolbar', $slice);
        $this->assertDoesNotMatchRegularExpression(
            '/\.fi-ta-content\s*\{[^}]*display:\s*none/i',
            $slice,
            'AI-730 must NOT hide the .fi-ta-content table area — only the toolbar.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/\.fi-ta-empty-state\s*\{[^}]*display:\s*none/i',
            $slice,
            'AI-730 must NOT hide the .fi-ta-empty-state slot — only the toolbar.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — non-empty state unchanged + AI-728/AI-729 integrity
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function rule_does_not_affect_toolbar_when_marker_absent(): void
    {
        // The :has() predicate evaluates false when no
        // .mw-admin-empty-state-posts is mounted, so the default
        // toolbar visibility for non-empty Posts list is unchanged.
        // We assert this indirectly: no global `body.fi-panel-admin
        // .fi-ta-header-toolbar { display: none }` rule should exist.
        // (Filament's default toolbar visibility on non-empty
        // tables stays untouched.)
        $this->assertDoesNotMatchRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-header-toolbar\s*\{[^}]*display:\s*none/i',
            $this->css,
            'AI-730 must not introduce an unconditional admin toolbar-hide rule. The :has() predicate is the gate.'
        );
    }

    #[Test]
    public function ai728_and_ai729_state_intact(): void
    {
        // Defensive guards that AI-728 + AI-729 surface state didn't
        // regress as AI-730 added its wrapper div. Post-branch must
        // still have: no <svg>, "No posts yet" heading, explainer,
        // and verb-led CTA.
        $start = strpos($this->emptyState, '@if($modelName == Modules\Post\Models\Post::class)');
        $end = strpos($this->emptyState, '@endif', $start);
        $postBranch = substr($this->emptyState, $start, $end - $start);

        $this->assertDoesNotMatchRegularExpression('/<svg[\s>]/i', $postBranch, 'AI-728: no <svg> in Post branch.');
        $this->assertMatchesRegularExpression('/>\s*No posts yet\s*</', $postBranch, 'AI-729: 3-word headline.');
        $this->assertStringContainsString('Articles, news, and updates you publish appear here.', $postBranch, 'AI-729: explainer copy.');
        $this->assertStringContainsString('Write your first post →', $postBranch, 'AI-729: verb-led CTA label.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai730_markers_pinned(): void
    {
        // The AI-730 task-id marker lives in the CSS file (not necessarily
        // in the blade template, where the blade evolved at later tickets).
        $this->assertStringContainsString(
            'task-2026-05-16-3d35fe',
            $this->css,
            'AI-730 task-id marker must be in general-styles.css source.'
        );
        $this->assertStringContainsString('AI-730', $this->css);
    }
}
