<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1092 / task-2026-05-28-2f5a6c Batch 2.
 *
 * Scope (dispatch verbatim): "/admin/categories empty state — no copy,
 * no CTA (add empty-state heading + CTA button via shared empty-state
 * blade pattern)."
 *
 * Failure family pinned by this test:
 *
 *  /admin/categories renders via a custom JS-rendered tree component
 *  (mw.admin.categoriesTree) inside a custom Blade view, NOT a standard
 *  Filament table — so a Resource-level ->emptyState() wiring would
 *  never render. When the category table is empty, the view rendered a
 *  bare empty <div> placeholder for the tree mount-point with no copy
 *  and no CTA, leaving the user stranded.
 *
 *  Fix: server-side category-count gate at the top of the custom view.
 *  When count > 0, render the existing tree-render block. When
 *  count === 0, render an empty-state chrome with heading, body, and
 *  a CTA pointing at the category-create route. Chrome shape mirrors
 *  the shared empty-state blade pattern used by Content / Order /
 *  Customer / Invoice / Product branches.
 *
 * The source file carries the task-id marker comment per the
 * per-task source-comment marker convention.
 */
class AdminCategories1092EmptyStateContractTest extends TestCase
{
    private string $categoriesList;

    private string $categoriesListStripped;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->categoriesList = (string) file_get_contents(
            self::projectRoot()
            . '/Modules/Category/resources/views/admin/filament/mw-categories-list.blade.php'
        );

        $stripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->categoriesList);
        $stripped = (string) preg_replace('~<!--.*?-->~s', '', $stripped);
        $this->categoriesListStripped = $stripped;
    }

    public function test_categories_list_carries_server_side_count_gate(): void
    {
        $this->assertMatchesRegularExpression(
            '/\$mwAi1092CategoryCount\s*=\s*\\\\Modules\\\\Category\\\\Models\\\\Category::count\(\)\s*;/',
            $this->categoriesList,
            'AI-1092: custom categories view must compute $mwAi1092CategoryCount via \\Modules\\Category\\Models\\Category::count() server-side so the gate can split tree-render vs empty-state branches'
        );
    }

    public function test_categories_list_renders_tree_only_when_count_gt_zero(): void
    {
        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*\$mwAi1092CategoryCount\s*>\s*0\s*\)/',
            $this->categoriesList,
            'AI-1092: tree-render block must be gated behind @if($mwAi1092CategoryCount > 0) so an empty categories table does not leak a bare tree mount-point with no copy'
        );
    }

    public function test_categories_list_has_else_branch_with_empty_state_chrome(): void
    {
        $this->assertMatchesRegularExpression(
            '/@else[\s\S]*?mw-table-empty-cta-wrap[\s\S]*?@endif/',
            $this->categoriesList,
            'AI-1092: @else branch must render an empty-state chrome carrying the canonical .mw-table-empty-cta-wrap class (matches Customer / Order / Invoice / Product branches in the shared empty-state blade)'
        );
    }

    public function test_categories_list_empty_state_heading_present(): void
    {
        $this->assertStringContainsString(
            'You do not have any categories yet.',
            $this->categoriesList,
            'AI-1092: empty-state must carry the canonical "You do not have any categories yet." heading (mirrors the shared empty-state blade family wording)'
        );
    }

    public function test_categories_list_empty_state_cta_points_at_categories_create_route(): void
    {
        $this->assertMatchesRegularExpression(
            '/route\(\s*[\'"]filament\.admin\.resources\.categories\.create[\'"]\s*\)/',
            $this->categoriesList,
            'AI-1092: empty-state CTA href must resolve via route(\'filament.admin.resources.categories.create\') so users can recover into the category-create form from the empty state'
        );
    }

    public function test_categories_list_empty_state_cta_label_uses_plus_add_category(): void
    {
        $this->assertMatchesRegularExpression(
            '/\+\s*Add category/',
            $this->categoriesList,
            'AI-1092: empty-state CTA label must read "+ Add category" (matches sibling resource branches like "+ Add customer", "+ Configure payment providers")'
        );
    }

    public function test_categories_list_empty_state_cta_carries_aria_label(): void
    {
        $this->assertMatchesRegularExpression(
            '/aria-label="Add category"/',
            $this->categoriesList,
            'AI-1092: empty-state CTA must carry aria-label="Add category" so screen-reader users get a clean accessible name (matches the AI-1098 aria-label discipline)'
        );
    }

    public function test_categories_list_empty_state_cta_uses_canonical_class(): void
    {
        $this->assertMatchesRegularExpression(
            '/class="mw-table-empty-cta"/',
            $this->categoriesList,
            'AI-1092: empty-state CTA anchor must carry the canonical .mw-table-empty-cta class (44px min-height + brand-blue #0d6efd defined in general-styles.css)'
        );
    }

    public function test_categories_list_tree_mount_sits_inside_count_gate(): void
    {
        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*\$mwAi1092CategoryCount\s*>\s*0\s*\)[\s\S]*?<div\s+wire:ignore\s+class="mw-edit-categories-list"[\s\S]*?@else[\s\S]*?@endif/',
            $this->categoriesListStripped,
            'AI-1092: tree mount-point div (.mw-edit-categories-list) must sit BETWEEN the @if($mwAi1092CategoryCount > 0) opener and the @else branch — pre-fix shape was a bare div with no count-gate, leaving empty installs with no copy'
        );
    }

    public function test_ai_1092_task_id_marker_in_categories_list(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-2f5a6c / AI-1092',
            $this->categoriesList,
            'AI-1092: task-id marker comment must be present adjacent to the count-gate + empty-state chrome in mw-categories-list.blade.php'
        );
    }
}
