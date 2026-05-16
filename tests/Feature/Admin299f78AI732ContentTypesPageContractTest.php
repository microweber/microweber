<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Content\Filament\Admin\Pages\ContentTypesPage;

/**
 * task-2026-05-16-299f78 / AI-732 Phase 1 — admin UI for custom
 * content models. Jira:
 *   https://microweber.atlassian.net/browse/AI-732
 *
 * Designer dispatch 2026-05-16T15:11:43 — Phase 1 scope:
 *   - route /admin/content-types
 *   - listing of distinct content_type values with counts
 *   - empty state
 *   - CTA deferred to Phase 2
 *
 * Phase 2 + Phase 3 are not in this slice. The contract here pins
 * Phase 1 surfaces (page class shape + view location + service-
 * provider registration + markers) without speculating on Phase
 * 2/3 internals.
 *
 * Access gated to admins (`is_admin()`) per the AI-133 / SEC-02
 * pattern used by ContentResource.
 */
class Admin299f78AI732ContentTypesPageContractTest extends TestCase
{
    private string $pageSrc;
    private string $providerSrc;
    private string $viewSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pageSrc = (string) file_get_contents(base_path(
            'Modules/Content/Filament/Admin/Pages/ContentTypesPage.php'
        ));
        $this->providerSrc = (string) file_get_contents(base_path(
            'Modules/Content/Providers/ContentServiceProvider.php'
        ));
        $this->viewSrc = (string) file_get_contents(base_path(
            'Modules/Content/resources/views/filament/admin/pages/content-types-page.blade.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — page class shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function page_class_extends_filament_page(): void
    {
        $this->assertTrue(
            class_exists(ContentTypesPage::class),
            'ContentTypesPage class must exist at Modules\Content\Filament\Admin\Pages\ContentTypesPage.'
        );
        $reflection = new \ReflectionClass(ContentTypesPage::class);
        $this->assertSame(
            \Filament\Pages\Page::class,
            $reflection->getParentClass()->getName(),
            'ContentTypesPage must extend Filament\\Pages\\Page.'
        );
    }

    #[Test]
    public function page_uses_content_types_slug(): void
    {
        // The AI-732 dispatch specced /admin/content-types as the
        // Phase 1 route. Filament resolves the URL via the page's
        // $slug static property.
        $slug = (new \ReflectionProperty(ContentTypesPage::class, 'slug'))->getDefaultValue();
        $this->assertSame(
            'content-types',
            $slug,
            'ContentTypesPage $slug must equal "content-types" → /admin/content-types per AI-732 Phase 1 dispatch.'
        );
    }

    #[Test]
    public function page_carries_website_nav_group_and_icon(): void
    {
        $group = (new \ReflectionProperty(ContentTypesPage::class, 'navigationGroup'))->getDefaultValue();
        $this->assertSame(
            'Website',
            $group,
            'ContentTypesPage must sit in the Website nav group alongside Pages / Posts (per AI-731 grouping + AI-732 dispatch).'
        );
        $icon = (new \ReflectionProperty(ContentTypesPage::class, 'navigationIcon'))->getDefaultValue();
        $this->assertNotNull(
            $icon,
            'ContentTypesPage must carry a non-null navigationIcon (lesson from AI-731 — Filament v5 suppresses null icons).'
        );
        $this->assertSame(
            'heroicon-o-rectangle-stack',
            $icon,
            'ContentTypesPage navigationIcon must be heroicon-o-rectangle-stack (stacked-shapes glyph fits "types of content").'
        );
    }

    #[Test]
    public function page_view_path_is_set(): void
    {
        $reflection = new \ReflectionProperty(ContentTypesPage::class, 'view');
        $reflection->setAccessible(true);
        // The $view property is non-static instance property.
        // Use reflection's getDefaultValue() against the declaration.
        $page = $reflection->getDeclaringClass()->newInstanceWithoutConstructor();
        $view = $reflection->getValue($page);
        $this->assertSame(
            'modules.content::filament.admin.pages.content-types-page',
            $view,
            'ContentTypesPage $view must point at modules.content::filament.admin.pages.content-types-page.'
        );
    }

    #[Test]
    public function page_can_access_gates_on_is_admin(): void
    {
        // AI-133 / SEC-02 pattern — access gate is `is_admin()`.
        // The class must declare canAccess() and the body must
        // contain `is_admin`.
        $reflection = new \ReflectionMethod(ContentTypesPage::class, 'canAccess');
        $this->assertTrue($reflection->isStatic(), 'canAccess() must be static (Filament contract).');
        $this->assertStringContainsString(
            'is_admin',
            $this->pageSrc,
            'ContentTypesPage::canAccess() must gate on is_admin() per the AI-133 / SEC-02 pattern.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — data loading shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function page_loads_content_types_aggregated_from_content_table(): void
    {
        // The page queries the content table grouped by content_type
        // with row counts. Pin the query shape so a future cleanup
        // doesn't accidentally drop the grouping or change the table.
        $this->assertMatchesRegularExpression(
            "/DB::table\\(['\"]content['\"]\\)/",
            $this->pageSrc,
            'ContentTypesPage must aggregate from the `content` table.'
        );
        $this->assertStringContainsString('groupBy', $this->pageSrc);
        $this->assertStringContainsString("content_type", $this->pageSrc);
        $this->assertMatchesRegularExpression(
            "/COUNT\\(\\*\\)\\s+as\\s+row_count/",
            $this->pageSrc,
            'Query must select COUNT(*) as row_count to drive the per-type counter column.'
        );
        $this->assertStringContainsString(
            'orderByDesc',
            $this->pageSrc,
            'Results must be ordered by count descending (most-used types first).'
        );
        // Soft-delete-aware: skips deleted_at non-null + is_deleted=1.
        $this->assertStringContainsString('whereNull', $this->pageSrc);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Blade view shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function view_uses_filament_page_component(): void
    {
        $this->assertStringContainsString(
            '<x-filament-panels::page>',
            $this->viewSrc,
            'View must wrap in <x-filament-panels::page> to inherit Filament chrome.'
        );
    }

    #[Test]
    public function view_renders_empty_state_when_no_types(): void
    {
        // Phase 1 AC: empty state present.
        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*empty\(\$contentTypes\)\s*\)/',
            $this->viewSrc,
            'View must branch on empty($contentTypes) for the empty state.'
        );
        $this->assertStringContainsString(
            'No content types yet',
            $this->viewSrc,
            'Empty-state heading must read "No content types yet" (matches AI-729 short-heading pattern).'
        );
        $this->assertStringContainsString(
            'mw-admin-empty-state-explainer',
            $this->viewSrc,
            'Empty-state explainer must use the .mw-admin-empty-state-explainer class shared with AI-729.'
        );
    }

    #[Test]
    public function view_renders_table_when_types_present(): void
    {
        // Per-type row table with count column.
        $this->assertStringContainsString('@foreach ($contentTypes as $row)', $this->viewSrc);
        $this->assertStringContainsString("\$row['type']", $this->viewSrc);
        $this->assertStringContainsString("\$row['count']", $this->viewSrc);
        $this->assertStringContainsString('number_format', $this->viewSrc);
        // Per Phase 1 scope, no `+ Add content type` CTA yet. The
        // Blade `{{-- … --}}` migration-rationale comment legit-
        // imately mentions the deferred-to-Phase-2 CTA label — strip
        // comments before the absence-assert so the docblock prose
        // doesn't false-match. Same LESSONS selector-self-match
        // family as the AI-728/AI-729 test fixes.
        $renderedView = preg_replace('/\{\{--.*?--\}\}/s', '', $this->viewSrc);
        $this->assertDoesNotMatchRegularExpression(
            '/\+\s*Add content type/i',
            $renderedView,
            'Phase 1 rendered output must NOT include the "+ Add content type" CTA — designer deferred to Phase 2.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — registration + markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function service_provider_registers_the_page(): void
    {
        $this->assertStringContainsString(
            'use Modules\\Content\\Filament\\Admin\\Pages\\ContentTypesPage;',
            $this->providerSrc,
            'ContentServiceProvider must `use` the ContentTypesPage class.'
        );
        $this->assertMatchesRegularExpression(
            '/FilamentRegistry::registerPage\(\s*ContentTypesPage::class\s*\)/',
            $this->providerSrc,
            'ContentServiceProvider::register() must call FilamentRegistry::registerPage(ContentTypesPage::class).'
        );
    }

    #[Test]
    public function task_id_and_ai732_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-16-299f78', $this->pageSrc);
        $this->assertStringContainsString('AI-732', $this->pageSrc);
        $this->assertStringContainsString('task-2026-05-16-299f78', $this->viewSrc);
        $this->assertStringContainsString('AI-732', $this->viewSrc);
        $this->assertStringContainsString('task-2026-05-16-299f78', $this->providerSrc);
        $this->assertStringContainsString('AI-732', $this->providerSrc);
    }
}
