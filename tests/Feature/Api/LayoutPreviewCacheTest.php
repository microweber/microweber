<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Perf regression coverage for the layout-preview cache.
 *
 * Each Insert Layout card without a screenshot renders a live iframe to
 * /api/module/layout-preview, which was a full render every time
 * (load_module('layouts') + parser->process rendering every nested module
 * live — 0.7–2.5s — plus the whole site head/footer). The picker opens dozens
 * at once (Plain ships 127 layouts, none with screenshots) and re-renders on
 * every scroll-back / reopen. The response is now cached by (skin, template,
 * layout-file mtime), so a repeat view is a cache read, not a re-render.
 *
 * Source checks are PRESENCE checks, so the selector-self-match guard does not
 * apply.
 */
final class LayoutPreviewCacheTest extends TestCase
{
    private function controllerSource(): string
    {
        return (string) file_get_contents(base_path(
            'packages/microweber-module/src/Http/Controllers/Api/ModulesApiLiveEdit.php'
        ));
    }

    #[Test]
    public function controller_caches_the_preview_keyed_by_layout_file_mtime(): void
    {
        $src = $this->controllerSource();

        // Reads the cache before rendering and writes it after.
        $this->assertStringContainsString('Cache::get($cacheKey)', $src, 'The preview must be served from cache when present.');
        $this->assertStringContainsString('Cache::put($cacheKey', $src, 'The rendered preview must be written to the cache.');

        // The key is scoped to (skin, template) and the layout file mtime so an
        // edited layout re-renders without a manual cache clear.
        $this->assertStringContainsString("'mw_layout_preview:'", $src);
        $this->assertStringContainsString('resolveLayoutFileMtime(', $src);
        $this->assertTrue(
            method_exists(\MicroweberPackages\Module\Http\Controllers\Api\ModulesApiLiveEdit::class, 'resolveLayoutFileMtime'),
            'The controller must declare resolveLayoutFileMtime().'
        );
    }

    #[Test]
    public function mtime_resolver_sanitises_the_template_name_against_traversal(): void
    {
        $src = $this->controllerSource();
        // The resolver only touches the filesystem under Templates/, after
        // stripping anything but slug characters from the folder name.
        $this->assertStringContainsString("preg_replace('#[^A-Za-z0-9_-]#'", $src, 'The template folder name must be sanitised before any is_file() probe.');
        $this->assertStringContainsString("base_path('Templates/'", $src, 'The resolver must only look under the Templates directory.');
    }

    #[Test]
    public function repeated_preview_requests_return_identical_bytes(): void
    {
        // Behavioural round-trip: the same (skin, template) yields byte-identical
        // HTML on the second request (served from the cache on a shared store;
        // the array test store short-circuits the re-render just the same).
        $admin = User::factory()->create([
            'email' => 'layout-preview-cache-' . uniqid() . '@example.com',
            'is_admin' => 1,
            'is_active' => 1,
        ]);
        $url = '/api/module/layout-preview?template=content__skin-1&active_site_template=Bootstrap';

        $first = $this->actingAs($admin)->get($url);
        $first->assertStatus(200);
        $second = $this->actingAs($admin)->get($url);
        $second->assertStatus(200);

        $this->assertSame($first->getContent(), $second->getContent(), 'Repeat preview requests must return identical HTML.');
    }
}
