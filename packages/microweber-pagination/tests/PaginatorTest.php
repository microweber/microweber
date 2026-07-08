<?php

declare(strict_types=1);

namespace MicroweberPackages\Pagination\Tests;

use MicroweberPackages\Pagination\Paginator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;

class PaginatorTest extends TestCase
{
    // ── Construction & Defaults ────────────────────────────────────

    #[Test]
    public function it_creates_with_defaults(): void
    {
        $p = new Paginator();

        $this->assertSame(1, $p->getCurrentPage());
        $this->assertSame(1, $p->getLastPage());
        $this->assertSame('', $p->getBaseUrl());
        $this->assertSame('page', $p->getPageName());
        $this->assertSame('bootstrap', $p->getTheme());
        $this->assertSame('md', $p->getSize());
        $this->assertSame(5, $p->getOnEachSide());
    }

    #[Test]
    public function it_accepts_options_array(): void
    {
        $p = new Paginator([
            'currentPage'  => 50,
            'lastPage'     => 200,
            'baseUrl'      => 'https://example.com/items',
            'pageName'     => 'p',
            'theme'        => 'tailwind',
            'size'         => 'lg',
            'onEachSide'   => 3,
            'queryParams'  => ['sort' => 'name'],
            'customClasses' => ['active' => 'my-active'],
        ]);

        $this->assertSame(50, $p->getCurrentPage());
        $this->assertSame(200, $p->getLastPage());
        $this->assertSame('https://example.com/items', $p->getBaseUrl());
        $this->assertSame('p', $p->getPageName());
        $this->assertSame('tailwind', $p->getTheme());
        $this->assertSame('lg', $p->getSize());
        $this->assertSame(3, $p->getOnEachSide());
        $this->assertSame(['sort' => 'name'], $p->getQueryParams());
        $this->assertSame(['active' => 'my-active'], $p->getCustomClasses());
    }

    // ── Fluent Setters ─────────────────────────────────────────────

    #[Test]
    public function fluent_setters_return_self_and_update(): void
    {
        $p = new Paginator();

        $result = $p->currentPage(5)
            ->lastPage(100)
            ->baseUrl('https://test.com')
            ->pageName('pg')
            ->theme('tailwind-flex')
            ->size('xl')
            ->onEachSide(7)
            ->queryParams(['q' => 'test'])
            ->customClasses(['wrapper' => 'custom-wrap'])
            ->view('my.custom.view');

        $this->assertSame($p, $result);
        $this->assertSame(5, $p->getCurrentPage());
        $this->assertSame(100, $p->getLastPage());
        $this->assertSame('https://test.com', $p->getBaseUrl());
        $this->assertSame('pg', $p->getPageName());
        $this->assertSame('tailwind-flex', $p->getTheme());
        $this->assertSame('xl', $p->getSize());
        $this->assertSame(7, $p->getOnEachSide());
    }

    // ── Page State Helpers ─────────────────────────────────────────

    #[Test]
    public function has_pages_is_false_for_single_page(): void
    {
        $p = new Paginator(['currentPage' => 1, 'lastPage' => 1]);
        $this->assertFalse($p->hasPages());
    }

    #[Test]
    public function has_pages_is_true_for_multiple_pages(): void
    {
        $p = new Paginator(['currentPage' => 1, 'lastPage' => 10]);
        $this->assertTrue($p->hasPages());
    }

    #[Test]
    public function on_first_page(): void
    {
        $this->assertTrue((new Paginator(['currentPage' => 1, 'lastPage' => 10]))->onFirstPage());
        $this->assertFalse((new Paginator(['currentPage' => 5, 'lastPage' => 10]))->onFirstPage());
    }

    #[Test]
    public function has_more_pages(): void
    {
        $this->assertTrue((new Paginator(['currentPage' => 5, 'lastPage' => 10]))->hasMorePages());
        $this->assertFalse((new Paginator(['currentPage' => 10, 'lastPage' => 10]))->hasMorePages());
    }

    // ── URL Building ───────────────────────────────────────────────

    #[Test]
    public function url_builds_correct_query_string(): void
    {
        $p = new Paginator([
            'baseUrl'  => 'https://example.com/items',
            'pageName' => 'page',
            'lastPage' => 100,
        ]);

        $url = $p->url(5);
        $this->assertSame('https://example.com/items?page=5', $url);
    }

    #[Test]
    public function url_preserves_existing_query_params(): void
    {
        $p = new Paginator([
            'baseUrl'     => 'https://example.com/items',
            'pageName'    => 'page',
            'lastPage'    => 100,
            'queryParams' => ['sort' => 'name', 'dir' => 'asc'],
        ]);

        $url = $p->url(3);
        $this->assertStringContainsString('sort=name', $url);
        $this->assertStringContainsString('dir=asc', $url);
        $this->assertStringContainsString('page=3', $url);
    }

    #[Test]
    public function url_appends_with_ampersand_when_base_has_query(): void
    {
        $p = new Paginator([
            'baseUrl'  => 'https://example.com/items?category=1',
            'pageName' => 'page',
            'lastPage' => 100,
        ]);

        $url = $p->url(2);
        $this->assertStringContainsString('&page=2', $url);
    }

    #[Test]
    public function url_clamps_page_number(): void
    {
        $p = new Paginator([
            'baseUrl'  => 'https://example.com',
            'lastPage' => 10,
        ]);

        $this->assertStringContainsString('page=1', $p->url(0));
        $this->assertStringContainsString('page=1', $p->url(-5));
        $this->assertStringContainsString('page=10', $p->url(999));
    }

    #[Test]
    public function previous_and_next_urls(): void
    {
        $p = new Paginator([
            'currentPage' => 5,
            'lastPage'    => 10,
            'baseUrl'     => 'https://example.com',
        ]);

        $this->assertStringContainsString('page=4', $p->previousPageUrl());
        $this->assertStringContainsString('page=6', $p->nextPageUrl());
    }

    #[Test]
    public function first_and_last_page_urls(): void
    {
        $p = new Paginator([
            'currentPage' => 5,
            'lastPage'    => 100,
            'baseUrl'     => 'https://example.com',
        ]);

        $this->assertStringContainsString('page=1', $p->firstPageUrl());
        $this->assertStringContainsString('page=100', $p->lastPageUrl());
    }

    // ── Window / Elements ──────────────────────────────────────────

    #[Test]
    public function elements_returns_empty_for_single_page(): void
    {
        $p = new Paginator(['currentPage' => 1, 'lastPage' => 1]);
        $this->assertSame([], $p->elements());
    }

    #[Test]
    public function elements_shows_all_pages_when_less_than_window(): void
    {
        $p = new Paginator([
            'currentPage' => 3,
            'lastPage'    => 5,
            'onEachSide'  => 5,
            'baseUrl'     => 'https://example.com',
        ]);

        $elements = $p->elements();
        $pages = array_filter($elements, fn($e) => $e['type'] === 'page');
        $pageNumbers = array_column($pages, 'page');

        $this->assertSame([1, 2, 3, 4, 5], array_values($pageNumbers));
    }

    #[Test]
    public function elements_shows_windowed_pages_for_large_list(): void
    {
        $p = new Paginator([
            'currentPage' => 100,
            'lastPage'    => 1000,
            'onEachSide'  => 5,
            'baseUrl'     => 'https://example.com',
        ]);

        $elements = $p->elements();

        // Should have dots
        $dots = array_filter($elements, fn($e) => $e['type'] === 'dots');
        $this->assertGreaterThanOrEqual(1, count($dots));

        // Current page should be marked active
        $activePage = array_filter($elements, fn($e) => $e['type'] === 'page' && ($e['active'] ?? false));
        $activePageNumbers = array_column($activePage, 'page');
        $this->assertContains(100, $activePageNumbers);

        // Should show pages around current: 95-105
        $pages = array_filter($elements, fn($e) => $e['type'] === 'page');
        $pageNumbers = array_column($pages, 'page');

        foreach (range(95, 105) as $expected) {
            $this->assertContains($expected, $pageNumbers, "Window should contain page $expected");
        }

        // First page (1) and last page (1000) should be present
        $this->assertContains(1, $pageNumbers);
        $this->assertContains(1000, $pageNumbers);
    }

    #[Test]
    public function elements_near_beginning(): void
    {
        $p = new Paginator([
            'currentPage' => 3,
            'lastPage'    => 1000,
            'onEachSide'  => 5,
            'baseUrl'     => 'https://example.com',
        ]);

        $elements = $p->elements();
        $pages = array_filter($elements, fn($e) => $e['type'] === 'page');
        $pageNumbers = array_values(array_column($pages, 'page'));

        // Should start with page 1
        $this->assertSame(1, $pageNumbers[0]);

        // Should contain current page 3
        $this->assertContains(3, $pageNumbers);

        // Last page should be present
        $this->assertContains(1000, $pageNumbers);
    }

    #[Test]
    public function elements_near_end(): void
    {
        $p = new Paginator([
            'currentPage' => 998,
            'lastPage'    => 1000,
            'onEachSide'  => 5,
            'baseUrl'     => 'https://example.com',
        ]);

        $elements = $p->elements();
        $pages = array_filter($elements, fn($e) => $e['type'] === 'page');
        $pageNumbers = array_values(array_column($pages, 'page'));

        // Should end with page 1000
        $this->assertContains(1000, $pageNumbers);

        // Should contain first page
        $this->assertContains(1, $pageNumbers);

        // Current page should be active
        $active = array_filter($elements, fn($e) => $e['type'] === 'page' && ($e['active'] ?? false));
        $this->assertContains(998, array_column($active, 'page'));
    }

    #[Test]
    public function on_each_side_controls_window_size(): void
    {
        $p = new Paginator([
            'currentPage' => 50,
            'lastPage'    => 100,
            'onEachSide'  => 2,
            'baseUrl'     => 'https://example.com',
        ]);

        $elements = $p->elements();
        $pages = array_filter($elements, fn($e) => $e['type'] === 'page');
        $pageNumbers = array_values(array_column($pages, 'page'));

        // Should have pages 48, 49, 50, 51, 52 plus first (1) and last (100)
        $this->assertContains(48, $pageNumbers);
        $this->assertContains(49, $pageNumbers);
        $this->assertContains(50, $pageNumbers);
        $this->assertContains(51, $pageNumbers);
        $this->assertContains(52, $pageNumbers);
    }

    // ── Legacy Array ───────────────────────────────────────────────

    #[Test]
    public function to_legacy_array_format(): void
    {
        $p = new Paginator([
            'currentPage' => 3,
            'lastPage'    => 5,
            'onEachSide'  => 5,
            'baseUrl'     => 'https://example.com',
        ]);

        $links = $p->toLegacyArray();

        // Should have prev, pages 1-5, next
        $this->assertGreaterThanOrEqual(5, count($links));

        // Check structure
        foreach ($links as $link) {
            $this->assertArrayHasKey('attributes', $link);
            $this->assertArrayHasKey('title', $link);
            $this->assertArrayHasKey('class', $link['attributes']);
            $this->assertArrayHasKey('current', $link['attributes']);
            $this->assertArrayHasKey('data-page-number', $link['attributes']);
            $this->assertArrayHasKey('href', $link['attributes']);
        }

        // Active page should be marked
        $activePage = array_filter($links, fn($l) => $l['attributes']['current'] === true);
        $this->assertCount(1, $activePage);
        $activeLink = array_values($activePage)[0];
        $this->assertSame('3', $activeLink['title']);
    }

    #[Test]
    public function to_legacy_array_has_prev_next_when_applicable(): void
    {
        // Middle page — both prev and next
        $p = new Paginator([
            'currentPage' => 3,
            'lastPage'    => 5,
            'onEachSide'  => 5,
            'baseUrl'     => 'https://example.com',
        ]);

        $links = $p->toLegacyArray();
        $titles = array_column($links, 'title');

        $this->assertSame('‹', $titles[0], 'First item should be prev arrow');
        $this->assertSame('›', end($titles), 'Last item should be next arrow');
    }

    #[Test]
    public function to_legacy_array_no_prev_on_first_page(): void
    {
        $p = new Paginator([
            'currentPage' => 1,
            'lastPage'    => 5,
            'onEachSide'  => 5,
            'baseUrl'     => 'https://example.com',
        ]);

        $links = $p->toLegacyArray();
        $titles = array_column($links, 'title');

        $this->assertSame('1', $titles[0], 'No prev arrow on first page');
    }

    #[Test]
    public function to_legacy_array_no_next_on_last_page(): void
    {
        $p = new Paginator([
            'currentPage' => 5,
            'lastPage'    => 5,
            'onEachSide'  => 5,
            'baseUrl'     => 'https://example.com',
        ]);

        $links = $p->toLegacyArray();
        $titles = array_column($links, 'title');

        $this->assertSame('5', end($titles), 'No next arrow on last page');
    }

    // ── CSS Class Resolution ───────────────────────────────────────

    #[Test]
    public function resolve_class_returns_bootstrap_defaults(): void
    {
        $p = new Paginator(['theme' => 'bootstrap']);

        $this->assertSame('pagination', $p->resolveClass('list'));
        $this->assertSame('page-item', $p->resolveClass('item'));
        $this->assertSame('page-link', $p->resolveClass('link'));
        $this->assertSame('active', $p->resolveClass('active'));
        $this->assertSame('disabled', $p->resolveClass('disabled'));
    }

    #[Test]
    public function resolve_class_returns_tailwind_defaults(): void
    {
        $p = new Paginator(['theme' => 'tailwind']);

        $this->assertStringContainsString('inline-flex', $p->resolveClass('list'));
        $this->assertStringContainsString('text-gray-500', $p->resolveClass('link'));
        $this->assertStringContainsString('text-blue-600', $p->resolveClass('active'));
    }

    #[Test]
    public function custom_classes_override_defaults(): void
    {
        $p = new Paginator([
            'theme'        => 'bootstrap',
            'customClasses' => [
                'active'   => 'my-active-class',
                'item'     => 'my-item-class',
            ],
        ]);

        $this->assertSame('my-active-class', $p->resolveClass('active'));
        $this->assertSame('my-item-class', $p->resolveClass('item'));
        // Non-overridden should keep default
        $this->assertSame('page-link', $p->resolveClass('link'));
    }

    // ── Size Variants ──────────────────────────────────────────────

    #[Test]
    public function bootstrap_size_classes(): void
    {
        $this->assertSame('pagination-sm', (new Paginator(['theme' => 'bootstrap', 'size' => 'sm']))->sizeClass());
        $this->assertSame('', (new Paginator(['theme' => 'bootstrap', 'size' => 'md']))->sizeClass());
        $this->assertSame('pagination-lg', (new Paginator(['theme' => 'bootstrap', 'size' => 'lg']))->sizeClass());
        $this->assertSame('pagination-lg', (new Paginator(['theme' => 'bootstrap', 'size' => 'xl']))->sizeClass());
    }

    #[Test]
    public function tailwind_size_classes(): void
    {
        $this->assertSame('text-xs', (new Paginator(['theme' => 'tailwind', 'size' => 'sm']))->sizeClass());
        $this->assertSame('text-sm', (new Paginator(['theme' => 'tailwind', 'size' => 'md']))->sizeClass());
        $this->assertSame('text-base', (new Paginator(['theme' => 'tailwind', 'size' => 'lg']))->sizeClass());
        $this->assertSame('text-lg', (new Paginator(['theme' => 'tailwind', 'size' => 'xl']))->sizeClass());
    }

    #[Test]
    public function custom_size_class_overrides(): void
    {
        $p = new Paginator([
            'theme' => 'bootstrap',
            'size'  => 'lg',
            'customClasses' => ['sizeClass' => 'my-custom-size'],
        ]);

        $this->assertSame('my-custom-size', $p->sizeClass());
    }

    // ── Edge Cases ─────────────────────────────────────────────────

    #[Test]
    public function minimum_current_page_is_1(): void
    {
        $p = new Paginator(['currentPage' => -5]);
        $this->assertSame(1, $p->getCurrentPage());
    }

    #[Test]
    public function minimum_last_page_is_1(): void
    {
        $p = new Paginator(['lastPage' => 0]);
        $this->assertSame(1, $p->getLastPage());
    }

    #[Test]
    public function minimum_on_each_side_is_1(): void
    {
        $p = new Paginator(['onEachSide' => -3]);
        $this->assertSame(1, $p->getOnEachSide());
    }

    #[Test]
    public function two_pages_produces_correct_elements(): void
    {
        $p = new Paginator([
            'currentPage' => 1,
            'lastPage'    => 2,
            'baseUrl'     => 'https://example.com',
        ]);

        $elements = $p->elements();
        $pages = array_filter($elements, fn($e) => $e['type'] === 'page');
        $pageNumbers = array_values(array_column($pages, 'page'));

        $this->assertSame([1, 2], $pageNumbers);
    }
}