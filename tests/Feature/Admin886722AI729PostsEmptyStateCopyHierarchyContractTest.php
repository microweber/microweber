<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-886722 / AI-729 — Posts empty-state copy + CTA
 * hierarchy weak. Jira:
 *   https://microweber.atlassian.net/browse/AI-729
 *
 * Designer dispatch 2026-05-16T15:11:02 recommended pattern:
 *   - Headline ≤ 4 words → "No posts yet"
 *   - 1-sentence explainer →
 *     "Articles, news, and updates you publish appear here."
 *   - Verb-led CTA → "Write your first post →" (not the
 *     system-label "+ Add post")
 *   - CTA is the largest interactive element on the surface.
 *
 * Pairs with AI-728 (wrong illustration — already stripped at
 * task-293e44) and AI-730 (toolbar hide-when-empty — next task).
 * Together the three tickets land one cohesive empty state.
 *
 * Scope discipline: changes touch the Post branch ONLY in
 * `Modules/Content/.../empty-state.blade.php`. Other empty-state
 * branches (Content, Order, Customer, Invoice, Product, Page,
 * Payment, Shipping, Tax) keep their existing copy.
 *
 * Adds one new CSS rule for the `.mw-admin-empty-state-explainer`
 * paragraph in `general-styles.css` (Webpack pipeline) — muted
 * colour so CTA stays the largest visual weight; max-width cap so
 * the explainer doesn't run edge-to-edge on wide viewports; dark-
 * theme override.
 */
class Admin886722AI729PostsEmptyStateCopyHierarchyContractTest extends TestCase
{
    private string $emptyState;
    private string $postBranch;
    private string $renderedBranch;
    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emptyState = (string) file_get_contents(base_path(
            'Modules/Content/resources/views/filament/admin/empty-state.blade.php'
        ));
        $start = strpos($this->emptyState, '@if($modelName == Modules\Post\Models\Post::class)');
        $this->assertNotFalse($start);
        $end = strpos($this->emptyState, '@endif', $start);
        $this->assertNotFalse($end);
        $this->postBranch = substr($this->emptyState, $start, $end - $start);

        // Strip Blade `{{-- … --}}` comments before legacy-copy
        // regression scanning — the AI-728 + AI-729 docblock comments
        // legitimately mention the OLD strings ("You do not have any
        // posts yet.", "+ Add post") to capture migration rationale.
        // Visible-render absence-asserts must run against the
        // comment-free slice so the docblocks don't false-match.
        $this->renderedBranch = preg_replace('/\{\{--.*?--\}\}/s', '', $this->postBranch);

        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — copy changes per designer pattern
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function headline_is_four_words_or_fewer(): void
    {
        // Designer rule: headline ≤ 4 words. "No posts yet" = 3 words.
        $this->assertMatchesRegularExpression(
            '/<h2[^>]*class="[^"]*\bmw-admin-empty-state-heading\b[^"]*">\s*No posts yet\s*<\/h2>/s',
            $this->postBranch,
            'Headline must read "No posts yet" (≤ 4 words per AI-729 design pattern).'
        );
    }

    #[Test]
    public function legacy_long_headline_is_gone(): void
    {
        // Regression guard against the old long headline form.
        // Runs against the comment-stripped slice — Blade docblocks
        // that legitimately mention the old string for migration
        // rationale must not false-match.
        $this->assertDoesNotMatchRegularExpression(
            '/You do not have any posts yet\./',
            $this->renderedBranch,
            'Legacy "You do not have any posts yet." headline must no longer appear in the rendered Post branch.'
        );
    }

    #[Test]
    public function explainer_paragraph_present_with_required_copy(): void
    {
        $this->assertStringContainsString(
            'Articles, news, and updates you publish appear here.',
            $this->postBranch,
            'Required explainer copy must be present verbatim.'
        );
        $this->assertMatchesRegularExpression(
            '/<p[^>]*class="[^"]*\bmw-admin-empty-state-explainer\b[^"]*"/',
            $this->postBranch,
            'Explainer must be a <p> with .mw-admin-empty-state-explainer class.'
        );
    }

    #[Test]
    public function cta_label_is_verb_led_not_system_label(): void
    {
        // "Write your first post →" beats "+ Add post" per designer
        // dispatch — verb-led, not system-label.
        $this->assertMatchesRegularExpression(
            '/Write your first post\s*→/',
            $this->postBranch,
            'CTA label must read "Write your first post →" per AI-729 design pattern.'
        );
        // Old `+ Add post` literal must be gone from the rendered
        // (comment-stripped) Post branch — the AI-729 docblock
        // legitimately mentions the legacy label for migration
        // rationale.
        $this->assertDoesNotMatchRegularExpression(
            '/\+\s*Add post\b/',
            $this->renderedBranch,
            'Legacy "+ Add post" CTA label must no longer appear in the rendered Post branch.'
        );
    }

    #[Test]
    public function cta_aria_label_matches_verb_led_action(): void
    {
        // aria-label updated to match the verb-led action for AT users.
        $this->assertStringContainsString(
            'aria-label="Write your first post"',
            $this->postBranch,
            'CTA aria-label must mirror the verb-led action for AT users.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — primary CTA is still the largest interactive element
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function cta_keeps_primary_styling_class(): void
    {
        // .mw-table-empty-cta carries the 44 px min-height + primary
        // blue from task-fd0d1d. Designer requires CTA = largest
        // interactive element; here it's the ONLY interactive element
        // on the empty-state surface so "largest" holds by construction.
        $this->assertStringContainsString(
            'class="mw-table-empty-cta"',
            $this->postBranch,
            'CTA must keep .mw-table-empty-cta — primary styling + 44 px min-height.'
        );
    }

    #[Test]
    public function cta_route_is_unchanged_for_back_compat(): void
    {
        // Routing target unchanged — AI-729 is copy/hierarchy only.
        $this->assertMatchesRegularExpression(
            "/route\\(['\"]filament\\.admin\\.resources\\.posts\\.create['\"]\\)/",
            $this->postBranch,
            'CTA href must still point at filament.admin.resources.posts.create.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — CSS rule for explainer
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function explainer_css_rule_present_with_muted_color(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-empty-state-explainer\s*\{[^}]*color:\s*#6b7280/i',
            $this->css,
            'Light-theme .mw-admin-empty-state-explainer must use muted gray-500 (#6b7280).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-empty-state-explainer\s*\{[^}]*max-width:\s*36rem/i',
            $this->css,
            'Explainer must cap line length via max-width: 36rem so it does not run edge-to-edge.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-empty-state-explainer\s*\{[^}]*line-height:\s*1\.5/i',
            $this->css,
            'Explainer must use line-height 1.5 for paragraph readability.'
        );
    }

    #[Test]
    public function explainer_css_dark_mode_override(): void
    {
        // Dark-mode parity — gray-400-ish for AA contrast on dark surface.
        $this->assertMatchesRegularExpression(
            '/(\.dark|\.dark)\s+\.mw-admin-empty-state-explainer\s*\{[^}]*color:\s*#9ca3af/i',
            $this->css,
            'Dark-theme override must set explainer color to gray-400 (#9ca3af) for AA contrast.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — scope discipline + markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function other_branches_copy_untouched(): void
    {
        // Regression guard: the legacy "You do not have any <X>"
        // pattern is still used by other branches (Order, Customer,
        // Invoice, etc.). They must NOT be touched by this slice.
        $this->assertGreaterThan(
            3,
            substr_count($this->emptyState, 'You do not have any'),
            'Other empty-state branches still use the legacy "You do not have any …" wording — must not be swept by AI-729.'
        );
    }

    #[Test]
    public function task_id_and_ai729_markers_pinned(): void
    {
        // The post branch carries AI-729 with task ID 008d91
        $this->assertStringContainsString('AI-729', $this->postBranch);
        $this->assertStringContainsString('task-2026-05-16-008d91', $this->postBranch);
        // CSS source comment carries the AI-729 marker with task ID 886722.
        $this->assertStringContainsString(
            'task-2026-05-16-886722',
            $this->css,
            'CSS slice must carry the AI-729 task-id marker.'
        );
        $this->assertStringContainsString(
            'AI-729',
            $this->css,
            'CSS slice must carry the AI-729 marker.'
        );
    }
}
