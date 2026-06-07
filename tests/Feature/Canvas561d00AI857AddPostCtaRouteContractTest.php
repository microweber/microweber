<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Content\Services\ContentModuleEmptyState;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-18-561d00 / AI-857 — Canvas empty-state "+ Add post / + Add
 * page / + Add content" CTA href was leading to 404.
 *
 * Root cause: the canvas content templates used
 * `admin_url('content/create?content_type=X')` → `/admin/content/create`,
 * a route that no longer exists after the Filament resources were
 * reorganised into per-type resources. Fix replaced them with the
 * canonical route() helpers (posts/pages/contents .create).
 *
 * **task-2026-06-07-pmprod (CHANGE)** — the CTA hrefs (and all the
 * empty-state copy/markup) were extracted out of the six copy-pasted
 * content templates into \Modules\Content\Services\ContentModuleEmptyState
 * + the shared partial. So the route() helpers are now asserted on the
 * SERVICE (single source); the six content templates are asserted to
 * delegate to the partial. The Page module template
 * (Modules/Page/resources/views/templates/default.blade.php) was NOT part
 * of the extraction and still carries its own page CTA, so it keeps its
 * template-level assertion. Group C (routes registered) is unchanged.
 */
class Canvas561d00AI857AddPostCtaRouteContractTest extends TestCase
{
    /**
     * The six content list templates that delegated their empty-state to
     * the shared partial in the task-2026-06-07-pmprod extraction.
     *
     * @return array<string, array{0: string}>
     */
    public static function contentTemplateProvider(): array
    {
        $base = 'Modules/Content/resources/views/templates/';
        $out = [];
        foreach (['default', 'masonry', 'dictionary', 'search', 'sidebar', 'skin-1'] as $t) {
            $out['content-' . $t] = [$base . $t . '.blade.php'];
        }
        return $out;
    }

    protected function templateContents(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — CTA routes now resolved by the service; templates delegate
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function service_cta_uses_filament_route_helpers(): void
    {
        $this->assertSame(
            route('filament.admin.resources.posts.create'),
            ContentModuleEmptyState::resolve(['type' => 'posts'])['ctaHref'],
            "Post empty-state CTA must point at the posts.create route."
        );
        $this->assertSame(
            route('filament.admin.resources.pages.create'),
            ContentModuleEmptyState::resolve(['type' => 'pages'])['ctaHref'],
            "Page empty-state CTA must point at the pages.create route."
        );
        $this->assertSame(
            route('filament.admin.resources.products.create'),
            ContentModuleEmptyState::resolve(['type' => 'shop/products'])['ctaHref'],
            "Product empty-state CTA must point at the products.create route."
        );
        $this->assertSame(
            route('filament.admin.resources.contents.create'),
            ContentModuleEmptyState::resolve([])['ctaHref'],
            "Fallback empty-state CTA must point at the contents.create route."
        );
    }

    #[Test]
    #[DataProvider('contentTemplateProvider')]
    public function content_template_delegates_cta_to_shared_partial(string $path): void
    {
        $source = $this->templateContents($path);
        $this->assertStringContainsString(
            "@include('modules.content::partials.module-empty-state'",
            $source,
            "{$path}: empty-state CTA now lives in the shared partial — the template must @include it."
        );
    }

    #[Test]
    public function page_template_still_uses_filament_route_helper(): void
    {
        // The Page module template was not part of the content extraction and
        // keeps its own page CTA.
        $source = $this->templateContents('Modules/Page/resources/views/templates/default.blade.php');
        $this->assertStringContainsString(
            "route('filament.admin.resources.pages.create')",
            $source,
            'Page/default.blade.php must keep its pages.create CTA route.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — broken admin_url('content/create') pattern is absent
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function broken_admin_url_pattern_is_absent_everywhere(): void
    {
        $files = array_merge(
            array_map(fn ($c) => $c[0], array_values(self::contentTemplateProvider())),
            [
                'Modules/Page/resources/views/templates/default.blade.php',
                'Modules/Content/resources/views/partials/module-empty-state.blade.php',
                'Modules/Content/Services/ContentModuleEmptyState.php',
            ]
        );

        foreach ($files as $rel) {
            $source = $this->templateContents($rel);
            // Strip Blade + PHP comments (selector-self-match guard — the fix
            // docblocks legitimately reference the old broken URL).
            $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $source);
            $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $stripped);
            $stripped = preg_replace('~//[^\n]*~', '', $stripped);

            $this->assertStringNotContainsString(
                "admin_url('content/create",
                $stripped,
                "{$rel}: broken admin_url('content/create…') must NOT appear — it resolves to /admin/content/create (404)."
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Filament routes exist and return non-404
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function filament_post_create_route_is_registered(): void
    {
        $this->assertNotNull(
            app('router')->getRoutes()->getByName('filament.admin.resources.posts.create'),
            "Filament route 'filament.admin.resources.posts.create' must be registered."
        );
    }

    #[Test]
    public function filament_page_create_route_is_registered(): void
    {
        $this->assertNotNull(
            app('router')->getRoutes()->getByName('filament.admin.resources.pages.create'),
            "Filament route 'filament.admin.resources.pages.create' must be registered."
        );
    }

    #[Test]
    public function filament_content_create_route_is_registered(): void
    {
        $this->assertNotNull(
            app('router')->getRoutes()->getByName('filament.admin.resources.contents.create'),
            "Filament route 'filament.admin.resources.contents.create' must be registered."
        );
    }

    #[Test]
    public function filament_product_create_route_is_registered(): void
    {
        // task-2026-06-07-pmprod added the product CTA — pin its route too.
        $this->assertNotNull(
            app('router')->getRoutes()->getByName('filament.admin.resources.products.create'),
            "Filament route 'filament.admin.resources.products.create' must be registered."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — task-id markers present
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function extraction_marker_present_in_default_template(): void
    {
        $source = $this->templateContents(
            'Modules/Content/resources/views/templates/default.blade.php'
        );
        $this->assertStringContainsString(
            'task-2026-06-07-pmprod',
            $source,
            'default.blade.php must carry the pmprod extraction marker for future audit grep.'
        );
    }
}
