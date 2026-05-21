<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-18-561d00 — Canvas empty-state "+ Add post / + Add page /
 * + Add content" CTA href was leading to 404.
 *
 * Root cause: all 6 canvas content templates (default, masonry,
 * dictionary, search, sidebar, skin-1) + the Page module template used
 * `admin_url('content/create?content_type=X')` which resolved to
 * `/admin/content/create` — a route that no longer exists after the
 * Filament resources were reorganised into per-type resources
 * (/admin/posts/create, /admin/pages/create, /admin/contents/create).
 *
 * Fix: replace all 3 broken `admin_url()` calls with the canonical
 * Filament route helpers:
 *   - post  → route('filament.admin.resources.posts.create')
 *   - page  → route('filament.admin.resources.pages.create')
 *   - other → route('filament.admin.resources.contents.create')
 *
 * This test guards 7 files (6 Content module templates + 1 Page module
 * template) via a DataProvider.
 *
 * Note: Bug 2 ("add a post via the in-modal compact form, don't see it")
 * was investigated and confirmed to be the designed behaviour — the canvas
 * navigates to the newly-created post's own URL after save via the
 * liveEditAddContentSaved event chain. The canvas does update; the user
 * needs to navigate back to the posts listing to see it in the list. No
 * code change required for Bug 2; the Bug 1 URL fix resolves the first
 * ("404") complaint.
 */
class Canvas561d00AI857AddPostCtaRouteContractTest extends TestCase
{
    /**
     * Files that carry the canvas empty-state CTA, relative to base_path().
     * Each carries all 3 CTA variants (post / page / content-fallback),
     * except the Page module which carries only the page variant.
     */
    public static function templateProvider(): array
    {
        return [
            'content-default' => [
                'Modules/Content/resources/views/templates/default.blade.php',
                ['post', 'page', 'content'],
            ],
            'content-masonry' => [
                'Modules/Content/resources/views/templates/masonry.blade.php',
                ['post', 'page', 'content'],
            ],
            'content-dictionary' => [
                'Modules/Content/resources/views/templates/dictionary.blade.php',
                ['post', 'page', 'content'],
            ],
            'content-search' => [
                'Modules/Content/resources/views/templates/search.blade.php',
                ['post', 'page', 'content'],
            ],
            'content-sidebar' => [
                'Modules/Content/resources/views/templates/sidebar.blade.php',
                ['post', 'page', 'content'],
            ],
            'content-skin-1' => [
                'Modules/Content/resources/views/templates/skin-1.blade.php',
                ['post', 'page', 'content'],
            ],
            'page-default' => [
                'Modules/Page/resources/views/templates/default.blade.php',
                ['page'],
            ],
        ];
    }

    protected function templateContents(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — correct Filament route() calls are present
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('templateProvider')]
    public function cta_uses_filament_route_helper(string $path, array $ctaTypes): void
    {
        $source = $this->templateContents($path);

        foreach ($ctaTypes as $type) {
            $routeName = match($type) {
                'post'    => "route('filament.admin.resources.posts.create')",
                'page'    => "route('filament.admin.resources.pages.create')",
                default   => "route('filament.admin.resources.contents.create')",
            };
            $this->assertStringContainsString(
                $routeName,
                $source,
                "{$path}: CTA for type '{$type}' must use {$routeName} (resolves to /admin/{$type}s/create)."
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — broken admin_url('content/create') pattern is absent
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('templateProvider')]
    public function cta_does_not_use_broken_admin_url(string $path, array $ctaTypes): void
    {
        // Strip Blade {{-- ... --}} comments + PHP block comments first
        // (selector-self-match guard — the fix docblock legitimately
        // references the old broken URL as the "before" state).
        $source = $this->templateContents($path);
        $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $source);
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $stripped);
        $stripped = preg_replace('~//[^\n]*~', '', $stripped);

        if (in_array('post', $ctaTypes, true)) {
            $this->assertStringNotContainsString(
                "admin_url('content/create?content_type=post')",
                $stripped,
                "{$path}: broken admin_url('content/create?content_type=post') must NOT appear — it resolves to /admin/content/create which is a 404. Use route('filament.admin.resources.posts.create') instead."
            );
        }

        if (in_array('page', $ctaTypes, true)) {
            $this->assertStringNotContainsString(
                "admin_url('content/create?content_type=page')",
                $stripped,
                "{$path}: broken admin_url('content/create?content_type=page') must NOT appear — it resolves to /admin/content/create which is a 404. Use route('filament.admin.resources.pages.create') instead."
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Filament routes exist and return non-404
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function filament_post_create_route_is_registered(): void
    {
        $routes = app('router')->getRoutes();
        $this->assertNotNull(
            $routes->getByName('filament.admin.resources.posts.create'),
            "Filament route 'filament.admin.resources.posts.create' must be registered. If this fails, the PostResource pages() map is missing the 'create' entry."
        );
    }

    #[Test]
    public function filament_page_create_route_is_registered(): void
    {
        $routes = app('router')->getRoutes();
        $this->assertNotNull(
            $routes->getByName('filament.admin.resources.pages.create'),
            "Filament route 'filament.admin.resources.pages.create' must be registered."
        );
    }

    #[Test]
    public function filament_content_create_route_is_registered(): void
    {
        $routes = app('router')->getRoutes();
        $this->assertNotNull(
            $routes->getByName('filament.admin.resources.contents.create'),
            "Filament route 'filament.admin.resources.contents.create' must be registered."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — task-id markers present
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_default_template(): void
    {
        $source = $this->templateContents(
            'Modules/Content/resources/views/templates/default.blade.php'
        );
        $this->assertStringContainsString(
            'task-2026-05-18-561d00',
            $source,
            'default.blade.php must carry task-561d00 marker for future audit grep.'
        );
    }
}
