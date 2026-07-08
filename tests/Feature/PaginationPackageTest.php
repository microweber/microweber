<?php

namespace Tests\Feature;

use MicroweberPackages\Pagination\Paginator;
use MicroweberPackages\Pagination\PaginationFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * App-integration tests for the microweber-pagination package
 * (MicroweberPackages\Pagination). The package's own unit suite lives in
 * packages/microweber-pagination/tests (Testbench); these verify it works
 * inside the booted Microweber app — views registered, Paginator + factory
 * usable.
 */
class PaginationPackageTest extends TestCase
{
    #[Test]
    public function it_basic(): void
    {
        $this->assertTrue(true);
    }

    #[Test]
    public function service_provider_registers_views(): void
    {
        $this->assertTrue(view()->exists('mw-pagination::bootstrap.default'));
        $this->assertTrue(view()->exists('mw-pagination::bootstrap.flex'));
        $this->assertTrue(view()->exists('mw-pagination::bootstrap.dropdown'));
        $this->assertTrue(view()->exists('mw-pagination::tailwind.default'));
        $this->assertTrue(view()->exists('mw-pagination::tailwind.flex'));
        $this->assertTrue(view()->exists('mw-pagination::tailwind.dropdown'));
    }

    #[Test]
    public function paginator_renders_bootstrap(): void
    {
        $paginator = new Paginator([
            'currentPage' => 5,
            'lastPage'    => 20,
            'baseUrl'     => 'https://example.com/items',
            'theme'       => 'bootstrap',
            'onEachSide'  => 3,
        ]);

        $html = $paginator->render();

        $this->assertStringContainsString('<nav', $html);
        $this->assertStringContainsString('pagination', $html);
        $this->assertStringContainsString('page-item', $html);
        $this->assertStringContainsString('active', $html);
    }

    #[Test]
    public function paginator_renders_tailwind(): void
    {
        $paginator = new Paginator([
            'currentPage' => 5,
            'lastPage'    => 20,
            'baseUrl'     => 'https://example.com/items',
            'theme'       => 'tailwind',
            'onEachSide'  => 3,
        ]);

        $html = $paginator->render();

        $this->assertStringContainsString('<nav', $html);
        $this->assertStringContainsString('flex', $html);
    }

    #[Test]
    public function paginator_windowed_pages_for_large_list(): void
    {
        $paginator = new Paginator([
            'currentPage' => 100,
            'lastPage'    => 1000,
            'onEachSide'  => 5,
            'baseUrl'     => 'https://example.com',
        ]);

        $elements = $paginator->elements();
        $pages = array_filter($elements, fn($e) => $e['type'] === 'page');
        $pageNumbers = array_column($pages, 'page');

        // Window should contain pages 95-105 around current page 100
        foreach (range(95, 105) as $expected) {
            $this->assertContains($expected, $pageNumbers, "Window should contain page $expected");
        }

        // First and last pages should always be present
        $this->assertContains(1, $pageNumbers);
        $this->assertContains(1000, $pageNumbers);
    }

    #[Test]
    public function paginator_size_variants_work(): void
    {
        $sm = new Paginator(['theme' => 'bootstrap', 'size' => 'sm']);
        $this->assertSame('pagination-sm', $sm->sizeClass());

        $lg = new Paginator(['theme' => 'bootstrap', 'size' => 'lg']);
        $this->assertSame('pagination-lg', $lg->sizeClass());

        $twSm = new Paginator(['theme' => 'tailwind', 'size' => 'sm']);
        $this->assertSame('text-xs', $twSm->sizeClass());
    }

    #[Test]
    public function paginator_custom_classes(): void
    {
        $paginator = new Paginator([
            'theme'        => 'bootstrap',
            'customClasses' => ['active' => 'my-active'],
        ]);

        $this->assertSame('my-active', $paginator->resolveClass('active'));
        // Non-overridden keeps default
        $this->assertSame('page-link', $paginator->resolveClass('link'));
    }

    #[Test]
    public function paginator_legacy_array(): void
    {
        $paginator = new Paginator([
            'currentPage' => 3,
            'lastPage'    => 5,
            'onEachSide'  => 5,
            'baseUrl'     => 'https://example.com',
        ]);

        $links = $paginator->toLegacyArray();
        $this->assertNotEmpty($links);

        foreach ($links as $link) {
            $this->assertArrayHasKey('attributes', $link);
            $this->assertArrayHasKey('title', $link);
        }
    }

    #[Test]
    public function pagination_factory_creates_paginator(): void
    {
        $factory = new PaginationFactory();
        $paginator = $factory->make([
            'currentPage' => 5,
            'lastPage'    => 100,
            'baseUrl'     => 'https://example.com',
        ]);

        $this->assertInstanceOf(Paginator::class, $paginator);
        $this->assertSame(5, $paginator->getCurrentPage());
        $this->assertSame(100, $paginator->getLastPage());
    }

    #[Test]
    public function from_laravel_paginator(): void
    {
        $items = collect(range(1, 50));
        $laravelPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items->forPage(3, 10),
            50,
            10,
            3,
            ['path' => 'https://example.com/items']
        );

        $paginator = Paginator::fromLaravel($laravelPaginator);

        $this->assertSame(3, $paginator->getCurrentPage());
        $this->assertSame(5, $paginator->getLastPage());
    }

    #[Test]
    public function all_themes_render(): void
    {
        $themes = [
            'bootstrap',
            'bootstrap-flex',
            'bootstrap-dropdown',
            'tailwind',
            'tailwind-flex',
            'tailwind-dropdown',
        ];

        foreach ($themes as $theme) {
            $paginator = new Paginator([
                'currentPage' => 5,
                'lastPage'    => 20,
                'baseUrl'     => 'https://example.com',
                'theme'       => $theme,
                'onEachSide'  => 3,
            ]);

            $html = $paginator->render();
            $this->assertNotEmpty($html, "Theme '$theme' should render non-empty HTML");
            $this->assertStringContainsString('nav', $html, "Theme '$theme' should contain nav element");
        }
    }
}
