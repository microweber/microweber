<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-31-leaud Slice H — public-template paginator giant-chevron defect.
 *
 * Root cause:
 *   Laravel's Paginator->links() defaults to the pagination::tailwind view
 *   which renders previous/next chevron SVGs with Tailwind sizing classes
 *   (w-5 h-5). Microweber public templates load Bootstrap, not Tailwind,
 *   so the SVG dimension classes never match and the chevron balloons to
 *   the SVG's intrinsic viewBox ratio (observed ~600px tall in the Live
 *   Edit canvas at page_id=7).
 *
 * Fix: pin every public-template paginator call site to the bootstrap-5
 * paginator view via ->links('pagination::bootstrap-5'). 5 surfaces:
 *   1. Comments user-comment-list livewire blade
 *   2. Shop default livewire blade
 *   3. Shop skin-1 livewire blade
 *   4. Blog default livewire blade
 *   5. Content PagingNav Laravel-pagination fallback path (PHP, not blade)
 *
 * Out-of-scope (intentional NOT a defect):
 *   - Filament admin paginators (admin loads Tailwind, default view is correct).
 *   - Global Paginator default override — would break admin surfaces; per-call
 *     pinning is the safer fix.
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: strip Blade
 * {{-- --}} + PHP // + PHP /* * / comments before the negative regression
 * assertion so a future docblock mentioning "$x->links()" cannot false-fail
 * the assertion.
 */
class AdminLeaudSliceHTailwindPaginatorContractTest extends TestCase
{
    /**
     * @return array<string, array{0:string, 1:string}>
     *   key: human-readable surface label
     *   [0]: repo-relative path
     *   [1]: paginator variable name (used in the explicit pin assertion regex)
     */
    public static function paginatorSurfaceProvider(): array
    {
        return [
            'comments user-comment-list' => [
                'Modules/Comments/resources/views/livewire/user-comment-list-component.blade.php',
                'comments',
            ],
            'shop default' => [
                'Modules/Shop/resources/views/livewire/shop/default.blade.php',
                'products',
            ],
            'shop skin-1' => [
                'Modules/Shop/resources/views/livewire/shop/skin-1.blade.php',
                'products',
            ],
            'blog default' => [
                'Modules/Blog/resources/views/livewire/blog/default.blade.php',
                'posts',
            ],
        ];
    }

    private function fileContents(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    private function stripComments(string $source): string
    {
        $stripped = preg_replace('~\{\{--.*?--\}\}~s', '', $source);
        $stripped = preg_replace('~/\*.*?\*/~s', '', (string) $stripped);
        $stripped = preg_replace('~//[^\n]*~', '', (string) $stripped);

        return (string) $stripped;
    }

    #[Test]
    #[DataProvider('paginatorSurfaceProvider')]
    public function public_livewire_paginator_pins_bootstrap_5_view(string $relativePath, string $paginatorVar): void
    {
        $source = $this->fileContents($relativePath);
        $this->assertNotSame('', $source, "Source file missing or empty: {$relativePath}");

        $this->assertStringContainsString(
            "\${$paginatorVar}->links('pagination::bootstrap-5')",
            $source,
            "{$relativePath}: paginator call must pass the explicit 'pagination::bootstrap-5' view name. Bare ->links() defaults to pagination::tailwind which has no CSS on this surface and produces a balloon-sized chevron SVG."
        );
    }

    #[Test]
    #[DataProvider('paginatorSurfaceProvider')]
    public function public_livewire_paginator_does_not_use_bare_links_call(string $relativePath, string $paginatorVar): void
    {
        $source = $this->fileContents($relativePath);
        $sourceWithoutComments = $this->stripComments($source);

        $pattern = '/\$' . preg_quote($paginatorVar, '/') . '->links\(\s*\)/';

        $this->assertDoesNotMatchRegularExpression(
            $pattern,
            $sourceWithoutComments,
            "Regression: {$relativePath} reverted to bare \${$paginatorVar}->links() which defaults to pagination::tailwind and renders the giant-chevron defect on Bootstrap public templates."
        );
    }

    #[Test]
    public function paging_nav_laravel_branch_pins_bootstrap_5_view(): void
    {
        $source = $this->fileContents('Modules/Content/Support/PagingNav.php');
        $this->assertNotSame('', $source, 'PagingNav.php missing or empty.');

        // The Laravel-pagination branch lives inside the
        // if (isset($params['laravel_pagination'])) { ... } block. Anchor
        // on the branch entry and slice forward to bound the assertion.
        $anchor = "if (isset(\$params['laravel_pagination']))";
        $pos = strpos($source, $anchor);
        $this->assertNotFalse($pos, "Anchor missing: {$anchor} — file shape changed.");

        // Fixed-length lookahead per AI-816 lesson; the branch is ~50 LOC.
        $slice = substr($source, $pos, 3000);

        $this->assertStringContainsString(
            "\$paginate->links('pagination::bootstrap-5')",
            $slice,
            "PagingNav Laravel-pagination branch must pin the bootstrap-5 view explicitly. Bare \$paginate->links() defaults to pagination::tailwind."
        );

        $sliceWithoutComments = $this->stripComments($slice);

        $this->assertDoesNotMatchRegularExpression(
            '/\$paginate->links\(\s*\)/',
            $sliceWithoutComments,
            'Regression: PagingNav Laravel-pagination branch reverted to bare $paginate->links() which renders giant-chevron defect on public surfaces.'
        );
    }

    #[Test]
    public function laravel_bootstrap_5_paginator_view_ships_with_framework(): void
    {
        // Sanity check: the view name we pin exists on disk in the
        // vendored Laravel framework. Catches a future Laravel upgrade
        // that renames or removes the bootstrap-5 paginator template.
        $viewPath = base_path('vendor/laravel/framework/src/Illuminate/Pagination/resources/views/bootstrap-5.blade.php');
        $this->assertFileExists(
            $viewPath,
            'Laravel framework no longer ships bootstrap-5.blade.php — Slice H pin needs a new target view.'
        );
    }
}
