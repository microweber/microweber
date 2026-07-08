<?php

declare(strict_types=1);

namespace MicroweberPackages\Pagination\Tests;

use MicroweberPackages\Pagination\Paginator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;

class RenderTest extends TestCase
{
    private function makePaginator(string $theme = 'bootstrap', array $extra = []): Paginator
    {
        return new Paginator(array_merge([
            'currentPage' => 5,
            'lastPage'    => 20,
            'baseUrl'     => 'https://example.com/items',
            'theme'       => $theme,
            'onEachSide'  => 3,
        ], $extra));
    }

    // ── Bootstrap Default ──────────────────────────────────────────

    #[Test]
    public function bootstrap_default_renders_nav_with_pagination_class(): void
    {
        $html = $this->makePaginator('bootstrap')->render();

        $this->assertStringContainsString('<nav', $html);
        $this->assertStringContainsString('pagination', $html);
        $this->assertStringContainsString('page-item', $html);
        $this->assertStringContainsString('page-link', $html);
    }

    #[Test]
    public function bootstrap_default_marks_active_page(): void
    {
        $html = $this->makePaginator('bootstrap')->render();

        $this->assertStringContainsString('active', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
    }

    #[Test]
    public function bootstrap_default_shows_prev_next(): void
    {
        $html = $this->makePaginator('bootstrap')->render();

        $this->assertStringContainsString('rel="prev"', $html);
        $this->assertStringContainsString('rel="next"', $html);
    }

    #[Test]
    public function bootstrap_default_shows_dots_for_large_lists(): void
    {
        $html = $this->makePaginator('bootstrap', ['lastPage' => 1000, 'currentPage' => 500])->render();

        $this->assertStringContainsString('&hellip;', $html);
    }

    // ── Bootstrap Flex ─────────────────────────────────────────────

    #[Test]
    public function bootstrap_flex_renders_with_flex_classes(): void
    {
        $html = $this->makePaginator('bootstrap-flex')->render();

        $this->assertStringContainsString('flex-wrap', $html);
        $this->assertStringContainsString('justify-content-center', $html);
    }

    #[Test]
    public function bootstrap_flex_shows_first_last_links(): void
    {
        $html = $this->makePaginator('bootstrap-flex')->render();

        $this->assertStringContainsString('aria-label="First"', $html);
        $this->assertStringContainsString('aria-label="Last"', $html);
    }

    // ── Bootstrap Dropdown ─────────────────────────────────────────

    #[Test]
    public function bootstrap_dropdown_renders_dropdown_structure(): void
    {
        $html = $this->makePaginator('bootstrap-dropdown')->render();

        $this->assertStringContainsString('dropdown', $html);
        $this->assertStringContainsString('Page 5 of 20', $html);
        $this->assertStringContainsString('dropdown-menu', $html);
    }

    // ── Tailwind Default ───────────────────────────────────────────

    #[Test]
    public function tailwind_default_renders_with_tailwind_classes(): void
    {
        $html = $this->makePaginator('tailwind')->render();

        $this->assertStringContainsString('flex', $html);
        $this->assertStringContainsString('items-center', $html);
    }

    #[Test]
    public function tailwind_default_marks_active_page(): void
    {
        $html = $this->makePaginator('tailwind')->render();

        $this->assertStringContainsString('text-blue-600', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
    }

    // ── Tailwind Flex ──────────────────────────────────────────────

    #[Test]
    public function tailwind_flex_renders_responsive_structure(): void
    {
        $html = $this->makePaginator('tailwind-flex')->render();

        $this->assertStringContainsString('sm:hidden', $html);
        $this->assertStringContainsString('sm:flex', $html);
    }

    // ── Tailwind Dropdown ──────────────────────────────────────────

    #[Test]
    public function tailwind_dropdown_renders_alpine_dropdown(): void
    {
        $html = $this->makePaginator('tailwind-dropdown')->render();

        $this->assertStringContainsString('x-data', $html);
        $this->assertStringContainsString('x-show', $html);
        $this->assertStringContainsString('Page 5 of 20', $html);
    }

    // ── Size Variants in Render ────────────────────────────────────

    #[Test]
    public function bootstrap_sm_size_renders_pagination_sm(): void
    {
        $html = $this->makePaginator('bootstrap', ['size' => 'sm'])->render();
        $this->assertStringContainsString('pagination-sm', $html);
    }

    #[Test]
    public function bootstrap_lg_size_renders_pagination_lg(): void
    {
        $html = $this->makePaginator('bootstrap', ['size' => 'lg'])->render();
        $this->assertStringContainsString('pagination-lg', $html);
    }

    #[Test]
    public function tailwind_sm_size_renders_text_xs(): void
    {
        $html = $this->makePaginator('tailwind', ['size' => 'sm'])->render();
        $this->assertStringContainsString('text-xs', $html);
    }

    // ── Custom Classes in Render ───────────────────────────────────

    #[Test]
    public function custom_active_class_in_bootstrap_render(): void
    {
        $html = $this->makePaginator('bootstrap', [
            'customClasses' => ['active' => 'custom-active-class'],
        ])->render();

        $this->assertStringContainsString('custom-active-class', $html);
    }

    // ── No Output for Single Page ──────────────────────────────────

    #[Test]
    public function render_returns_empty_for_single_page(): void
    {
        $p = new Paginator([
            'currentPage' => 1,
            'lastPage'    => 1,
            'theme'       => 'bootstrap',
        ]);

        $this->assertSame('', $p->render());
    }

    // ── View Override ──────────────────────────────────────────────

    #[Test]
    public function view_override_is_used_when_set(): void
    {
        // Register a test view
        $this->app['view']->addNamespace('test-pagination', __DIR__ . '/fixtures');

        $p = new Paginator([
            'currentPage' => 3,
            'lastPage'    => 10,
            'baseUrl'     => 'https://example.com',
            'view'        => 'test-pagination::custom',
        ]);

        // Create the fixture view
        $fixtureDir = __DIR__ . '/fixtures';
        if (!is_dir($fixtureDir)) {
            mkdir($fixtureDir, 0755, true);
        }
        file_put_contents($fixtureDir . '/custom.blade.php', '<div class="custom-pagination">Page {{ $paginator->getCurrentPage() }}</div>');

        $html = $p->render();

        $this->assertStringContainsString('custom-pagination', $html);
        $this->assertStringContainsString('Page 3', $html);

        // Cleanup
        unlink($fixtureDir . '/custom.blade.php');
        rmdir($fixtureDir);
    }

    // ── Htmlable / toString ────────────────────────────────────────

    #[Test]
    public function to_html_returns_same_as_render(): void
    {
        $p = $this->makePaginator('bootstrap');
        $this->assertSame($p->render(), $p->toHtml());
    }

    #[Test]
    public function to_string_returns_same_as_render(): void
    {
        $p = $this->makePaginator('bootstrap');
        $this->assertSame($p->render(), (string) $p);
    }

    // ── All themes produce valid output ────────────────────────────

    public static function themeProvider(): array
    {
        return [
            ['bootstrap'],
            ['bootstrap-flex'],
            ['bootstrap-dropdown'],
            ['tailwind'],
            ['tailwind-flex'],
            ['tailwind-dropdown'],
        ];
    }

    #[Test]
    #[DataProvider('themeProvider')]
    public function all_themes_render_without_errors(string $theme): void
    {
        $html = $this->makePaginator($theme)->render();

        $this->assertNotEmpty($html);
        $this->assertStringContainsString('nav', $html);
    }

    #[Test]
    #[DataProvider('themeProvider')]
    public function all_themes_show_current_page_number(string $theme): void
    {
        $html = $this->makePaginator($theme, ['currentPage' => 7])->render();

        $this->assertStringContainsString('7', $html);
    }
}