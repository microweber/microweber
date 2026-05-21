<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * task-2026-05-22-3b90dd / AI-876 P1
 * Jira: https://microweber.atlassian.net/browse/AI-876
 *
 * /admin/pages, /admin/products, and /admin/content-types threw HTTP 500
 * "Unknown column 'deleted_at'" because three count queries called
 * ->whereNull('deleted_at') on the content table, which uses is_deleted
 * instead of deleted_at for soft-deletion.
 *
 * Fix: remove ->whereNull('deleted_at') from all three count subqueries.
 * The is_deleted clause alone is correct and sufficient.
 *
 * Same defect class as AI-732 and AI-736.
 */
class Admin3b90ddAI876DeletedAtRegressionContractTest extends TestCase
{
    private string $listProducts;
    private string $listPages;
    private string $contentTypesPage;

    protected function setUp(): void
    {
        parent::setUp();
        $this->listProducts = (string) file_get_contents(base_path(
            'Modules/Product/Filament/Admin/Resources/ProductResource/Pages/ListProducts.php'
        ));
        $this->listPages = (string) file_get_contents(base_path(
            'Modules/Page/Filament/Resources/PageResource/Pages/ListPages.php'
        ));
        $this->contentTypesPage = (string) file_get_contents(base_path(
            'Modules/Content/Filament/Admin/Pages/ContentTypesPage.php'
        ));
    }

    // ─── Source-level: deleted_at absent ─────────────────────────────────────

    #[Test]
    public function list_products_has_no_deleted_at(): void
    {
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->listProducts) ?? $this->listProducts;
        $stripped = preg_replace('~//[^\n]*~', '', $stripped) ?? $stripped;
        $this->assertStringNotContainsString('deleted_at', $stripped,
            'ListProducts.php must not query deleted_at — use is_deleted.'
        );
    }

    #[Test]
    public function list_pages_has_no_deleted_at(): void
    {
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->listPages) ?? $this->listPages;
        $stripped = preg_replace('~//[^\n]*~', '', $stripped) ?? $stripped;
        $this->assertStringNotContainsString('deleted_at', $stripped,
            'ListPages.php must not query deleted_at — use is_deleted.'
        );
    }

    #[Test]
    public function content_types_page_has_no_deleted_at(): void
    {
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->contentTypesPage) ?? $this->contentTypesPage;
        $stripped = preg_replace('~//[^\n]*~', '', $stripped) ?? $stripped;
        $this->assertStringNotContainsString('deleted_at', $stripped,
            'ContentTypesPage.php must not query deleted_at — use is_deleted.'
        );
    }

    // ─── Source-level: is_deleted still present ───────────────────────────────

    #[Test]
    public function list_products_still_filters_by_is_deleted(): void
    {
        $this->assertStringContainsString(
            'is_deleted',
            $this->listProducts,
            'ListProducts.php must still filter by is_deleted to exclude soft-deleted rows.'
        );
    }

    #[Test]
    public function list_pages_still_filters_by_is_deleted(): void
    {
        $this->assertStringContainsString(
            'is_deleted',
            $this->listPages,
            'ListPages.php must still filter by is_deleted to exclude soft-deleted rows.'
        );
    }

    #[Test]
    public function content_types_page_still_filters_by_is_deleted(): void
    {
        $this->assertStringContainsString(
            'is_deleted',
            $this->contentTypesPage,
            'ContentTypesPage.php must still filter by is_deleted to exclude soft-deleted rows.'
        );
    }

    // ─── Task marker ─────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_in_list_products(): void
    {
        $this->assertStringContainsString('task-2026-05-22-3b90dd', $this->listProducts,
            'ListProducts.php must carry the AI-876 task-id marker.'
        );
    }

    #[Test]
    public function task_marker_in_list_pages(): void
    {
        $this->assertStringContainsString('task-2026-05-22-3b90dd', $this->listPages,
            'ListPages.php must carry the AI-876 task-id marker.'
        );
    }

    #[Test]
    public function task_marker_in_content_types_page(): void
    {
        $this->assertStringContainsString('task-2026-05-22-3b90dd', $this->contentTypesPage,
            'ContentTypesPage.php must carry the AI-876 task-id marker.'
        );
    }
}
