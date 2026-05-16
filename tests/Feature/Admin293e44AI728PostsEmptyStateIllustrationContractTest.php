<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-293e44 / AI-728 — Posts empty-state illustration
 * is wrong subject (shopping/e-commerce). Jira:
 *   https://microweber.atlassian.net/browse/AI-728
 *
 * Designer dispatch 2026-05-16T15:10:42: the `/admin/posts` empty
 * state was rendering a shopping/e-commerce illustration (woman
 * with bags, dress rack — the SHOP module empty state inherited
 * from the Product branch above) paired with "You do not have any
 * posts yet." Wrong subject; dark-mode contrast hot spot from the
 * illustration's bright `currentColor != stroke` palette.
 *
 * Interim per designer direction: delete the illustration entirely
 * and let heading + CTA carry the empty state. Beats wrong-subject
 * in every test. Permanent posts-relevant illustration (writing /
 * notebook / article cards with currentColor strokes) tracked as
 * AI-728-followup.
 *
 * Ships with AI-729 (copy/hierarchy) + AI-730 (toolbar hide-when-
 * empty) per designer dispatch — one empty-state slice.
 *
 * Scope discipline: the change touches the Post branch ONLY in
 * Modules/Content/resources/views/filament/admin/empty-state.blade.php.
 * Other branches (Content, Order, Customer, Invoice, Product, Page,
 * Payment, Shipping, Tax) keep their illustrations untouched.
 */
class Admin293e44AI728PostsEmptyStateIllustrationContractTest extends TestCase
{
    private string $emptyState;
    private string $postBranch;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emptyState = (string) file_get_contents(base_path(
            'Modules/Content/resources/views/filament/admin/empty-state.blade.php'
        ));

        // Slice the Post branch — from the `@if($modelName ==
        // Modules\Post\Models\Post::class)` marker to its
        // matching `@endif`. Defensive against the file growing
        // (other branches added before or after).
        $start = strpos($this->emptyState, '@if($modelName == Modules\Post\Models\Post::class)');
        $this->assertNotFalse($start, 'Post-branch @if marker must be present.');
        $end = strpos($this->emptyState, '@endif', $start);
        $this->assertNotFalse($end, 'Post-branch @endif must follow.');
        $this->postBranch = substr($this->emptyState, $start, $end - $start);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Post-branch SVG removed
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function post_branch_has_no_svg_illustration(): void
    {
        // The wrong-subject illustration must be gone. Asserting
        // the absence of any `<svg` tag inside the Post branch is
        // the cleanest guard against an accidental re-introduction
        // of the wrong illustration during a future refactor.
        $this->assertDoesNotMatchRegularExpression(
            '/<svg[\s>]/i',
            $this->postBranch,
            'Post-branch must not render any <svg> illustration (AI-728 interim direction).'
        );
    }

    #[Test]
    public function post_branch_size_collapsed_to_under_5kb(): void
    {
        // Sanity guard — the pre-AI-728 branch was ~124KB of inline
        // SVG paths. After the strip it should be well under 5KB
        // (heading + CTA + docblock comment ~= 1.3KB measured).
        $this->assertLessThan(
            5000,
            strlen($this->postBranch),
            'Post-branch byte size must collapse well below 5KB after AI-728 SVG strip.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — heading + CTA still carry the empty state
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function post_branch_keeps_heading(): void
    {
        $this->assertStringContainsString(
            'You do not have any posts yet.',
            $this->postBranch,
            'Empty-state heading must remain — the heading + CTA now carry the empty state per designer interim direction.'
        );
        $this->assertMatchesRegularExpression(
            '/<h2[^>]*class="[^"]*\bmw-admin-empty-state-heading\b[^"]*"/',
            $this->postBranch,
            'Heading must keep the .mw-admin-empty-state-heading class.'
        );
    }

    #[Test]
    public function post_branch_keeps_primary_cta(): void
    {
        $this->assertStringContainsString(
            'mw-table-empty-cta',
            $this->postBranch,
            'Primary `+ Add post` CTA must keep its .mw-table-empty-cta class (from task-2026-05-16-fd0d1d).'
        );
        $this->assertMatchesRegularExpression(
            "/route\\(['\"]filament\\.admin\\.resources\\.posts\\.create['\"]\\)/",
            $this->postBranch,
            'CTA must point at filament.admin.resources.posts.create route.'
        );
        // CTA aria-label + visible label evolved at AI-729
        // (task-886722): "Add post" → "Write your first post". AI-728
        // is illustration-strip scope; the verb-led-label requirement
        // is AI-729's contract — so AI-728 here only asserts that
        // SOME aria-label exists, not a verbatim value (else AI-729
        // ships would false-fail this test against itself).
        $this->assertMatchesRegularExpression(
            '/<a[^>]*aria-label="[^"]+"/',
            $this->postBranch,
            'CTA must carry some aria-label for AT users (verbatim text owned by AI-729 contract).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — scope discipline (other branches untouched)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function other_branches_still_carry_their_svg_illustrations(): void
    {
        // Regression guard: the surgical removal must NOT have
        // collapsed any other empty-state branch. Test by counting
        // `<svg` tags in the full file — we expect at least one per
        // non-Post branch (Content, Order, Customer, Invoice,
        // Product, Page, Payment, Shipping, Tax = 9 branches × ≥1 svg).
        // Lower bound 7 svgs is conservative (some branches may
        // share SVGs or be empty).
        $svgCount = preg_match_all('/<svg[\s>]/i', $this->emptyState);
        $this->assertGreaterThanOrEqual(
            7,
            $svgCount,
            'Other empty-state branches must keep their SVG illustrations — at least 7 should remain after AI-728 strips only the Post-branch SVG.'
        );
    }

    #[Test]
    public function content_branch_svg_preserved(): void
    {
        // Concretely pin one neighbouring branch's SVG so a sloppy
        // future cleanup that removes ALL empty-state SVGs gets
        // flagged. Content (the parent class) branch starts the file.
        $contentBranchStart = strpos($this->emptyState, '@if($modelName == Modules\Content\Models\Content::class)');
        $this->assertNotFalse($contentBranchStart);
        $contentBranchEnd = strpos($this->emptyState, '@endif', $contentBranchStart);
        $contentBranch = substr($this->emptyState, $contentBranchStart, $contentBranchEnd - $contentBranchStart);
        $this->assertMatchesRegularExpression(
            '/<svg[\s>]/i',
            $contentBranch,
            'Content branch SVG must remain — AI-728 only touches Post.'
        );
    }

    #[Test]
    public function product_branch_svg_preserved(): void
    {
        // The Product branch was the visual source of the wrong
        // illustration that ended up rendered in Post (the Product
        // SHOP empty-state). It still needs to remain in the
        // Product branch — only the duplication into Post is wrong.
        $productBranchStart = strpos($this->emptyState, '@if($modelName == Modules\Product\Models\Product::class)');
        $this->assertNotFalse($productBranchStart);
        $productBranchEnd = strpos($this->emptyState, '@endif', $productBranchStart);
        $productBranch = substr($this->emptyState, $productBranchStart, $productBranchEnd - $productBranchStart);
        $this->assertMatchesRegularExpression(
            '/<svg[\s>]/i',
            $productBranch,
            'Product branch SVG must remain — only the Post-branch duplication was wrong.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — markers + AI-728-followup hint
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai728_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-16-293e44', $this->postBranch);
        $this->assertStringContainsString('AI-728', $this->postBranch);
        // Permanent-illustration follow-up hint discoverable in source.
        $this->assertStringContainsString(
            'AI-728-followup',
            $this->postBranch,
            'Source comment must hint at AI-728-followup so a future audit grep for the permanent-illustration work lands here.'
        );
    }
}
