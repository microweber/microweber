<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Content\Services\ContentModuleEmptyState;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-07-pmprod
 *
 * Posts/Product Live-Edit empty-state parity + de-duplication.
 *
 * Two things are pinned here:
 *
 *  1. ContentModuleEmptyState::resolve() — the logic extracted out of six
 *     copy-pasted Blade `@php` blocks (default, skin-1, masonry, search,
 *     sidebar, dictionary). It must resolve every content-list module type
 *     to the right view-model, INCLUDING the product module whose registered
 *     type is the path-namespaced 'shop/products' (ProductsModule::$module),
 *     which the old inline `match` only checked as bare 'products' and so
 *     never matched — products silently fell through to the generic
 *     "No content yet" + /admin/content CTA instead of a product CTA.
 *
 *  2. The six list templates now @include the shared partial and no longer
 *     each carry the inline logic/markup — so the dedup can't silently
 *     regress back into per-template drift.
 *
 *  3. The copy is produced with Microweber's _e($s, true), not Laravel __():
 *     several bodies end in '.', and __() returns an EMPTY string for any key
 *     ending in '.' (it treats the trailing dot as a namespace separator —
 *     the AI-796 footgun). The body must actually render.
 */
class ContentEmptyStatePmprodContractTest extends TestCase
{
    /**
     * @return array<string, array{0: array<string,mixed>, 1: ?string, 2: string}>
     */
    public static function typeCases(): array
    {
        return [
            'posts module type'        => [['type' => 'posts'], 'post', 'No posts yet'],
            'pages module type'        => [['type' => 'pages'], 'page', 'No pages yet'],
            'products bare type'       => [['type' => 'products'], 'product', 'No products yet'],
            'products namespaced type' => [['type' => 'shop/products'], 'product', 'No products yet'],
            'explicit content_type'    => [['content_type' => 'product'], 'product', 'No products yet'],
            'unknown module type'      => [['type' => 'somethingelse'], null, 'No content yet'],
            'empty params'             => [[], null, 'No content yet'],
        ];
    }

    #[Test]
    #[DataProvider('typeCases')]
    public function resolve_returns_the_correct_view_model(array $params, ?string $expectedType, string $expectedTitle): void
    {
        $vm = ContentModuleEmptyState::resolve($params);

        $this->assertSame($expectedType, $vm['type']);
        $this->assertSame($expectedTitle, $vm['title']);

        // AI-796 trailing-period guard: body + CTA label must render non-empty.
        $this->assertNotSame('', trim($vm['body']), 'Empty-state body must render — use _e($s,true), not __().');
        $this->assertNotSame('', trim($vm['ctaLabel']));
        $this->assertStringContainsString('/admin/', $vm['ctaHref']);
    }

    #[Test]
    public function shop_products_namespaced_type_resolves_to_product(): void
    {
        // The headline bug: ProductsModule::$module === 'shop/products'.
        $this->assertSame('product', ContentModuleEmptyState::resolveType(['type' => 'shop/products']));
        $this->assertSame('product', ContentModuleEmptyState::resolveType(['type' => 'products']));
        $this->assertSame('post', ContentModuleEmptyState::resolveType(['type' => 'posts']));
        $this->assertNull(ContentModuleEmptyState::resolveType(['type' => 'unknown']));
        // explicit content_type wins over the type-attribute inference
        $this->assertSame('page', ContentModuleEmptyState::resolveType(['content_type' => 'page', 'type' => 'posts']));
    }

    #[Test]
    public function product_view_model_points_at_the_products_create_route(): void
    {
        $vm = ContentModuleEmptyState::resolve(['type' => 'shop/products']);
        $this->assertSame(route('filament.admin.resources.products.create'), $vm['ctaHref']);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function listTemplates(): array
    {
        $base = 'Modules/Content/resources/views/templates/';
        $out = [];
        foreach (['default', 'skin-1', 'masonry', 'search', 'sidebar', 'dictionary'] as $t) {
            $out[$t] = [$base . $t . '.blade.php'];
        }
        return $out;
    }

    #[Test]
    #[DataProvider('listTemplates')]
    public function list_template_uses_shared_partial_not_inline_logic(string $relativePath): void
    {
        $src = (string) file_get_contents(base_path($relativePath));

        $this->assertStringContainsString(
            "@include('modules.content::partials.module-empty-state'",
            $src,
            $relativePath . ' must render the shared empty-state partial.'
        );

        // De-dup guard: the inline logic + markup must be GONE from the template.
        $this->assertStringNotContainsString(
            'mwEmptyType = match',
            $src,
            $relativePath . ' must not carry the inline empty-state match block — '
            . 'it now lives in ContentModuleEmptyState::resolve().'
        );
        $this->assertStringNotContainsString(
            'mw-canvas-empty-state__title',
            $src,
            $relativePath . ' must not carry the inline empty-state markup — it now lives in the shared partial.'
        );
    }

    #[Test]
    public function service_uses_microweber_translate_not_laravel_for_trailing_period_copy(): void
    {
        $svc = (string) file_get_contents(base_path('Modules/Content/Services/ContentModuleEmptyState.php'));

        // The trailing-period body must use _e($s, true) ...
        $this->assertStringContainsString("_e('Products you add to your store appear here.', true)", $svc);
        // ... never Laravel __() which blanks trailing-period keys.
        $this->assertStringNotContainsString(
            "__('Products you add to your store appear here.'",
            $svc,
            'Trailing-period body must not use Laravel __() — it returns an empty string.'
        );
    }
}
