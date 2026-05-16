<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-0b912e / AI-736 — Pages admin: no "+ Add page"
 * CTA + paginator-for-1-row noise. Jira:
 *   https://microweber.atlassian.net/browse/AI-736
 *
 * Designer dispatch 2026-05-16T15:41:02 (High priority — Pages is
 * the primary content type in a website builder). Three compounding
 * issues:
 *   1. No "+ Add page" CTA anywhere on /admin/pages.
 *   2. Paginator shows "Per page 250" dropdown for 1 row.
 *   3. 6 controls per row on Home page (no visible labels).
 *
 * This ship addresses surfaces 1 + 2. Surface 3 (row-action
 * collapse) is tracked as AI-736b — that lives in ContentResource's
 * `->actions()` block which is shared across Pages/Posts/Products/
 * Content. Changing it without a Posts/Products dispatch would
 * inflate scope across resources designer hasn't audited yet.
 *
 * Surface 1 reverses the cycle-61 / AI-39 decision to remove the
 * in-resource CreateAction in favour of the global "+ Add New"
 * topbar dropdown. Designer's authority + Pages-specificity:
 * CreateAction restored on ListPages only, other resources keep
 * the global-dropdown-only behaviour until designer dispatches
 * them individually.
 */
class Admin0b912eAI736PagesListCtaContractTest extends TestCase
{
    private string $listPagesSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->listPagesSrc = (string) file_get_contents(base_path(
            'Modules/Page/Filament/Resources/PageResource/Pages/ListPages.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — surface 1: "+ Add page" CTA via CreateAction
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function list_pages_registers_create_action_header(): void
    {
        $this->assertMatchesRegularExpression(
            '/Actions\\\\CreateAction::make\(\)/',
            $this->listPagesSrc,
            'ListPages::getHeaderActions() must register a Filament CreateAction for the "+ Add page" CTA.'
        );
    }

    #[Test]
    public function create_action_has_plus_add_page_label(): void
    {
        $this->assertMatchesRegularExpression(
            "/->label\\(\\s*['\"]\\+\\s*Add page['\"]\\s*\\)/",
            $this->listPagesSrc,
            'CreateAction must use "+ Add page" label per designer dispatch.'
        );
    }

    #[Test]
    public function create_action_is_primary_styled(): void
    {
        // Color::primary keeps the CTA visually weighty so it
        // reads as the primary action top-right.
        $this->assertMatchesRegularExpression(
            "/->color\\(\\s*['\"]primary['\"]\\s*\\)/",
            $this->listPagesSrc,
            'CreateAction must use primary color so the CTA reads as the page-level primary action.'
        );
        $this->assertMatchesRegularExpression(
            "/->icon\\(\\s*['\"]heroicon-o-plus['\"]\\s*\\)/",
            $this->listPagesSrc,
            'CreateAction must carry the heroicon-o-plus icon (consistent with the "+ Add" label and global topbar dropdown).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — surface 2: paginator hidden when count ≤ 10
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function table_method_overridden_in_list_pages(): void
    {
        // Override is required to apply the conditional pagination
        // — ContentResource's parent ::table() always sets
        // ->paginated([10, 25, 50, 100, 250, 'all']).
        $this->assertMatchesRegularExpression(
            '/public\s+function\s+table\s*\(\s*Table\s+\$table\s*\)\s*:\s*Table/',
            $this->listPagesSrc,
            'ListPages must override the public table(Table $table): Table method to apply AI-736 pagination logic.'
        );
        $this->assertStringContainsString(
            'use Filament\Tables\Table;',
            $this->listPagesSrc,
            'ListPages must `use Filament\\Tables\\Table` for the override signature.'
        );
    }

    #[Test]
    public function table_method_disables_pagination_when_count_le_10(): void
    {
        // Count query → conditional ->paginated(false).
        $this->assertMatchesRegularExpression(
            "/Content::where\\(\\s*['\"]content_type['\"]\\s*,\\s*['\"]page['\"]\\s*\\)/",
            $this->listPagesSrc,
            'table() must count Content rows where content_type = page.'
        );
        $this->assertMatchesRegularExpression(
            '/->count\(\)/',
            $this->listPagesSrc,
            'table() must call ->count() to materialise the row count.'
        );
        $this->assertMatchesRegularExpression(
            '/\$pageCount\s*<=\s*10/',
            $this->listPagesSrc,
            'table() must branch on $pageCount <= 10 per the AI-736 dispatch threshold.'
        );
        $this->assertMatchesRegularExpression(
            '/\$table->paginated\(false\)/',
            $this->listPagesSrc,
            'table() must call $table->paginated(false) inside the ≤ 10 branch to hide paginator chrome.'
        );
    }

    #[Test]
    public function table_count_query_is_soft_delete_aware(): void
    {
        // Mirror the soft-delete-awareness pattern from AI-732 +
        // ContentResource (whereNull('deleted_at') + is_deleted
        // gate). Stops soft-deleted rows inflating the count past
        // 10 and re-introducing the pagination chrome falsely.
        $this->assertMatchesRegularExpression(
            "/->whereNull\\(\\s*['\"]deleted_at['\"]\\s*\\)/",
            $this->listPagesSrc,
            'table() count query must skip soft-deleted rows via whereNull(\'deleted_at\').'
        );
        $this->assertStringContainsString(
            "->where('is_deleted', 0)",
            $this->listPagesSrc,
            'table() count query must skip is_deleted = 1 rows.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — scope discipline + markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function homepage_setup_action_preserved_for_backcompat(): void
    {
        // ListPages already had a Setup-Homepage action — must not
        // be removed by the AI-736 changes. Regression guard.
        $this->assertStringContainsString(
            "'setup_homepage'",
            $this->listPagesSrc,
            'Existing Setup-Homepage action must be preserved.'
        );
    }

    #[Test]
    public function task_id_and_ai736_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-17-0b912e', $this->listPagesSrc);
        $this->assertStringContainsString('AI-736', $this->listPagesSrc);
        // Surface-3 follow-up hint discoverable in source.
        $this->assertStringContainsString(
            'AI-736b',
            $this->listPagesSrc,
            'Source must hint at AI-736b — surface 3 row-action collapse follow-up.'
        );
    }
}
