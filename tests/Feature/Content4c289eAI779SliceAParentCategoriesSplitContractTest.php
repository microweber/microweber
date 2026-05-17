<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-4c289e / AI-779 Slice A — Posts admin "Where to put
 * it" rail splits into Parent page + Categories sub-sections.
 * Jira: https://microweber.atlassian.net/browse/AI-779
 *
 * Designer's R10-5 audit caught the "Where to put it" rail mixing
 * pages and categories in a single mw-tree — IA confusion ("only
 * Home listed, user has no mental model"). My SHIP report on
 * task-6d65de proposed Slice A: split into TWO labeled sub-sections
 * (Parent page single-select + Categories multi-select) feeding the
 * existing `parent` + `categoryIds` form fields, no schema change.
 * Designer greenlit Slice A in their ACK (task-4c289e dispatch).
 *
 * Slice A implementation:
 *
 *   - parentPageSection() in ContentResource detects content_type ===
 *     'post' (via $get('content_type') OR $record->content_type) and
 *     returns TWO sub-Sections inside the outer "Where to put it"
 *     wrapper:
 *
 *       1. "Parent page" Section — mw-tree with skipCategories=true +
 *          singleSelect=true → pages-only single-select tree for
 *          hierarchy placement.
 *       2. "Categories" Section — mw-tree with skipCategories=false →
 *          categories visible for multi-select tagging.
 *
 *   - Both sub-sections still feed the existing `parent` Hidden +
 *     `categoryIds` Hidden form fields via the mw-tree state binding.
 *     No new form fields, no schema migration.
 *
 *   - Non-post content (pages, products, default) — original single-
 *     view shape preserved verbatim. Behaviour-parity regression
 *     guard.
 *
 * Deferred to AI-779b: mw-tree skipPages flag so the Categories
 * sub-section shows category nodes ONLY (currently shows both pages
 * AND categories — Categories section labels intent correctly but
 * the visual still shows pages in the tree). Backend mw-tree
 * renderer change needed.
 */
class Content4c289eAI779SliceAParentCategoriesSplitContractTest extends TestCase
{
    private string $contentResource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->contentResource = (string) file_get_contents(base_path(
            'Modules/Content/Filament/Admin/ContentResource.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — parentPageSection branches on isPost
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function parent_page_section_branches_on_is_post(): void
    {
        // The closure must compute $isPost from BOTH the existing
        // record AND the live $get('content_type') so the split
        // fires on the Create form too (before record exists).
        $this->assertMatchesRegularExpression(
            "/\\\$isPost\\s*=\\s*\\(\\\$record\\s*&&\\s*\\\$record->content_type\\s*===\\s*'post'\\)\\s*\\|\\|\\s*\\\$get\\('content_type'\\)\\s*===\\s*'post'/",
            $this->contentResource,
            "parentPageSection must compute \$isPost from BOTH record + live \$get('content_type')."
        );
    }

    #[Test]
    public function post_branch_returns_two_sub_sections(): void
    {
        // Source pin: the if($isPost) branch must return an array
        // of TWO Section components labeled "Parent page" + "Categories".
        $this->assertStringContainsString(
            "Schemas\\Components\\Section::make('Parent page')",
            $this->contentResource,
            'Post branch must render a sub-Section labeled "Parent page".'
        );
        $this->assertStringContainsString(
            "Schemas\\Components\\Section::make('Categories')",
            $this->contentResource,
            'Post branch must render a sub-Section labeled "Categories".'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Parent page sub-section uses pages-only single-select
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function parent_page_subsection_uses_skip_categories_and_single_select(): void
    {
        // Slice the $parentViewData block via strpos then assert
        // each key independently (order-agnostic — viewData key
        // ordering is a stylistic choice, not a contract).
        $start = strpos($this->contentResource, '$parentViewData = [');
        $this->assertNotFalse($start, '$parentViewData assignment must exist.');
        $end = strpos($this->contentResource, '];', $start);
        $this->assertNotFalse($end);
        $slice = substr($this->contentResource, $start, $end - $start);

        $this->assertStringContainsString("'skipCategories' => true", $slice, 'Parent page must skip category nodes (pages-only).');
        $this->assertStringContainsString("'singleSelect' => true", $slice, 'Parent page must be single-select (one parent only).');
        $this->assertStringContainsString("'contentType' => 'page'", $slice, 'Parent page must filter contentType=page.');
    }

    #[Test]
    public function categories_subsection_uses_skip_categories_false_and_multi_select(): void
    {
        $start = strpos($this->contentResource, '$categoriesViewData = [');
        $this->assertNotFalse($start, '$categoriesViewData assignment must exist.');
        $end = strpos($this->contentResource, '];', $start);
        $this->assertNotFalse($end);
        $slice = substr($this->contentResource, $start, $end - $start);

        $this->assertStringContainsString("'skipCategories' => false", $slice, 'Categories sub-section must show category nodes (skipCategories=false).');
        $this->assertStringContainsString("'singleSelect' => false", $slice, 'Categories sub-section must be multi-select.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Both sub-sections feed existing form fields (no schema change)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function existing_parent_hidden_form_field_preserved(): void
    {
        // The split must not require a new form field — the existing
        // `parent` Hidden continues to be the state holder.
        $this->assertStringContainsString(
            "Forms\\Components\\Hidden::make('parent')",
            $this->contentResource,
            'Existing parent Hidden form field must still exist after AI-779 Slice A (no schema change).'
        );
    }

    #[Test]
    public function existing_category_ids_hidden_form_field_preserved(): void
    {
        $this->assertStringContainsString(
            "Forms\\Components\\Hidden::make('categoryIds')",
            $this->contentResource,
            'Existing categoryIds Hidden form field must still exist after AI-779 Slice A (no schema change).'
        );
    }

    #[Test]
    public function both_subsections_use_mw_tree_view(): void
    {
        // Both sub-sections render via the same mw-tree view — the
        // existing state binding pipe is reused. No new view
        // component invented.
        $occurrences = preg_match_all(
            "/View::make\\('mw-filament::admin\\.mw-tree'\\)/",
            $this->contentResource
        );
        $this->assertGreaterThanOrEqual(
            3,
            $occurrences,
            'parentPageSection must reference mw-tree at least 3 times (Parent page sub, Categories sub, non-post default). Got ' . $occurrences . '.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — non-post content preserves original single-view shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function non_post_branch_preserves_original_single_view_shape(): void
    {
        // Behaviour-parity guard: the non-post path (pages, products,
        // default) must still return a SINGLE View component with
        // the merged viewData shape from the pre-AI-779 code.
        $stripped = preg_replace('!/\*.*?\*/!s', '', $this->contentResource);
        $stripped = preg_replace('!//.*$!m', '', $stripped);
        // The non-post return is the LAST `return [View::make(...)]`
        // in parentPageSection — slice from the closing of the
        // if($isPost) block forward.
        $this->assertMatchesRegularExpression(
            "/\\\$viewData\\s*=\\s*\\[[\\s\\S]*?'selectedPage'\\s*=>\\s*\\\$parent[\\s\\S]*?'selectedCategories'\\s*=>\\s*\\\$categoryIds/s",
            $stripped,
            'Non-post path must still build $viewData merging selectedPage + selectedCategories together (original single-tree shape).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — outer "Where to put it" + AI-779b flag + markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function outer_section_label_remains_where_to_put_it(): void
    {
        // The novice-renaming "Where to put it" wrapper label (per
        // task-2026-05-04-novice) must be preserved — Slice A only
        // splits the INSIDE of the section, not the outer label.
        $this->assertStringContainsString(
            "Schemas\\Components\\Section::make('Where to put it')",
            $this->contentResource,
            'Outer "Where to put it" wrapper label must remain after AI-779 Slice A.'
        );
    }

    #[Test]
    public function ai779b_deferred_flag_documented_in_source(): void
    {
        // The Categories sub-section still shows page nodes because
        // mw-tree doesn't yet support a skipPages flag. Slice A
        // ships the LABELED separation; AI-779b would add the
        // backend mw-tree renderer change. The deferral must be
        // documented in source so the next agent knows.
        $this->assertStringContainsString(
            'AI-779b',
            $this->contentResource,
            'Source must reference AI-779b follow-up for the mw-tree skipPages flag refinement.'
        );
        $this->assertStringContainsString(
            'skipPages flag',
            $this->contentResource,
            'Source must explain WHY AI-779b is needed (mw-tree skipPages flag).'
        );
    }

    #[Test]
    public function task_id_and_ai779_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-4c289e', $this->contentResource);
        $this->assertStringContainsString('AI-779 Slice A', $this->contentResource);
    }
}
