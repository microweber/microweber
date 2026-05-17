<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-1ffb35 / AI-815 — Category module content_type-
 * aware heading derivation across all 4 templates.
 *
 * Jira: https://microweber.atlassian.net/browse/AI-815
 * Priority: Medium
 * Lineage: AI-780/AI-780a/AI-801 — same content_type-aware pattern
 *          (those covered EMPTY-STATE copy; AI-815 covers the
 *          visually-hidden category landmark <h2> heading).
 *
 * Pre-fix (all 4 templates):
 *   <h2 ... class="visually-hidden">{{ __('Product categories') }}</h2>
 *
 * The Category module is content-type-agnostic (used on blogs,
 * portfolios, galleries, AND shops) but the heading hardcoded the
 * product slug. Screen readers announced "Product categories
 * navigation" on EVERY site — even non-shop sites. Convention
 * violation vs the AI-780a content-type-aware family.
 *
 * Post-fix per template — designer recipe applied verbatim:
 *
 *   @php
 *       $mwCatHeading = $params['heading'] ?? match ($params['content_type'] ?? 'content') {
 *           'post'    => __('Post categories'),
 *           'page'    => __('Page categories'),
 *           'product' => __('Product categories'),
 *           'picture' => __('Picture categories'),
 *           default   => __('Categories'),
 *       };
 *   @endphp
 *   <nav ...>
 *       <h2 ... class="visually-hidden">{{ $mwCatHeading }}</h2>
 *
 * Plus the manual override path via `$params['heading']` so editors
 * can hand-pick a custom heading per module instance.
 *
 * Affected templates (designer named all 4 accurately — no recon
 * delta this dispatch):
 *   default / images / skin-1 / horizontal-list-1
 */
class Category1ffb35AI815ContentTypeAwareHeadingContractTest extends TestCase
{
    public static function allCategoryTemplatesProvider(): array
    {
        return [
            'default'           => ['Modules/Category/resources/views/templates/default.blade.php'],
            'images'            => ['Modules/Category/resources/views/templates/images.blade.php'],
            'skin-1'            => ['Modules/Category/resources/views/templates/skin-1.blade.php'],
            'horizontal-list-1' => ['Modules/Category/resources/views/templates/horizontal-list-1.blade.php'],
        ];
    }

    /**
     * Read file + pre-strip Blade `{{-- ... --}}` comments so
     * docblock prose mentioning legacy strings cannot false-fail
     * absence assertions (selector-self-match guard, 20+
     * session-recurrences).
     */
    private function executableTemplate(string $relativePath): string
    {
        $src = (string) file_get_contents(base_path($relativePath));
        return preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $src);
    }

    private function rawTemplate(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  legacy hardcoded heading shape is GONE
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('allCategoryTemplatesProvider')]
    public function legacy_hardcoded_product_categories_h2_is_gone(string $relativePath): void
    {
        $exec = $this->executableTemplate($relativePath);
        // The pre-fix shape: <h2 ...>{{ __('Product categories') }}</h2>.
        // Post-fix uses $mwCatHeading. The `__('Product categories')`
        // string STILL appears inside the match-arm of the new
        // @php block — that's correct (only fires for content_type
        // = 'product'). So negate ONLY the hardcoded <h2> shape.
        $this->assertDoesNotMatchRegularExpression(
            "/<h2\\s+id=\"cat-[^\"]+-h\"\\s+class=\"visually-hidden\">\\s*\\{\\{\\s*__\\(\\s*'Product categories'\\s*\\)\\s*\\}\\}\\s*<\\/h2>/",
            $exec,
            "AI-815: {$relativePath} MUST NOT carry the hardcoded `<h2 class=visually-hidden>{{ __('Product categories') }}</h2>` shape."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  $mwCatHeading match expression is present + complete
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('allCategoryTemplatesProvider')]
    public function mw_cat_heading_php_block_present(string $relativePath): void
    {
        $exec = $this->executableTemplate($relativePath);
        // The @php block MUST assign $mwCatHeading via the
        // params['heading'] override OR a match() expression on
        // params['content_type'].
        $this->assertMatchesRegularExpression(
            "/@php[\\s\\S]+?\\\$mwCatHeading\\s*=\\s*\\\$params\\['heading'\\]\\s*\\?\\?\\s*match\\s*\\(\\s*\\\$params\\['content_type'\\][\\s\\S]+?@endphp/",
            $exec,
            "AI-815: {$relativePath} MUST carry a @php block assigning \$mwCatHeading via params['heading'] override + match(params['content_type'])."
        );
    }

    #[Test]
    #[DataProvider('allCategoryTemplatesProvider')]
    public function mw_cat_heading_match_covers_all_5_branches(string $relativePath): void
    {
        $exec = $this->executableTemplate($relativePath);
        // The match() expression MUST cover post / page / product /
        // picture / default branches per designer spec.
        $expectedArms = [
            "'post'\\s*=>\\s*__\\(\\s*'Post categories'\\s*\\)",
            "'page'\\s*=>\\s*__\\(\\s*'Page categories'\\s*\\)",
            "'product'\\s*=>\\s*__\\(\\s*'Product categories'\\s*\\)",
            "'picture'\\s*=>\\s*__\\(\\s*'Picture categories'\\s*\\)",
            "default\\s*=>\\s*__\\(\\s*'Categories'\\s*\\)",
        ];
        foreach ($expectedArms as $arm) {
            $this->assertMatchesRegularExpression(
                "/{$arm}/",
                $exec,
                "AI-815: {$relativePath} match() MUST include arm matching `{$arm}` per designer spec."
            );
        }
    }

    #[Test]
    #[DataProvider('allCategoryTemplatesProvider')]
    public function visually_hidden_h2_now_uses_mw_cat_heading_var(string $relativePath): void
    {
        $exec = $this->executableTemplate($relativePath);
        // Positive: the visually-hidden <h2> MUST interpolate
        // $mwCatHeading (not a hardcoded string).
        $this->assertMatchesRegularExpression(
            "/<h2\\s+id=\"cat-[^\"]+-h\"\\s+class=\"visually-hidden\">\\s*\\{\\{\\s*\\\$mwCatHeading\\s*\\}\\}\\s*<\\/h2>/",
            $exec,
            "AI-815: {$relativePath} visually-hidden <h2> MUST interpolate {{ \$mwCatHeading }}."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  params['heading'] manual-override path is wired
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('allCategoryTemplatesProvider')]
    public function params_heading_manual_override_is_first_choice(string $relativePath): void
    {
        $exec = $this->executableTemplate($relativePath);
        // The ?? operator MUST put params['heading'] BEFORE the
        // match() — operator-set heading wins over auto-derived.
        $this->assertMatchesRegularExpression(
            "/\\\$mwCatHeading\\s*=\\s*\\\$params\\['heading'\\]\\s*\\?\\?/",
            $exec,
            "AI-815: {$relativePath} \$mwCatHeading MUST use \$params['heading'] ?? match() — operator-set heading takes precedence."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  task-id markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('allCategoryTemplatesProvider')]
    public function task_id_marker_present(string $relativePath): void
    {
        $raw = $this->rawTemplate($relativePath);
        $this->assertStringContainsString(
            'task-2026-05-17-1ffb35',
            $raw,
            "AI-815: {$relativePath} MUST carry the task-id marker for audit grep."
        );
        $this->assertStringContainsString(
            'AI-815',
            $raw,
            "AI-815: {$relativePath} MUST cite the ticket ID for audit grep."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E  AI-780a lineage citation in source comment
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('allCategoryTemplatesProvider')]
    public function ai780a_lineage_cited(string $relativePath): void
    {
        $raw = $this->rawTemplate($relativePath);
        // Source-side comment MUST cite the AI-780/AI-780a pattern
        // lineage so future audits find the family via grep.
        $this->assertMatchesRegularExpression(
            '/AI-780|AI-780a|AI-801/',
            $raw,
            "AI-815: {$relativePath} MUST cite the AI-780/AI-780a/AI-801 content-type-aware pattern lineage in source-side comment."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group F  recon-surface guard
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function no_other_category_template_carries_hardcoded_h2_heading(): void
    {
        $templatesDir = base_path('Modules/Category/resources/views/templates');
        $allBlades = glob($templatesDir . '/*.blade.php');
        $covered = array_map(
            fn ($p) => base_path($p[0]),
            array_values(static::allCategoryTemplatesProvider())
        );
        $uncovered = array_diff($allBlades, $covered);

        foreach ($uncovered as $file) {
            $raw = (string) file_get_contents($file);
            $exec = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $raw);
            if (preg_match(
                "/<h2\\s+[^>]*class=\"visually-hidden\">\\s*\\{\\{\\s*__\\(\\s*'Product categories'\\s*\\)\\s*\\}\\}\\s*<\\/h2>/",
                $exec
            )) {
                $this->fail(sprintf(
                    'AI-815: uncovered Category template %s carries the hardcoded `<h2 class=visually-hidden>{{ __(\'Product categories\') }}</h2>`. ' .
                    'Add to Category1ffb35AI815ContentTypeAwareHeadingContractTest::allCategoryTemplatesProvider() AND apply the content_type-aware heading.',
                    basename($file)
                ));
            }
        }
        $this->addToAssertionCount(1);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group G  count sanity
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function data_provider_contains_4_templates(): void
    {
        $this->assertCount(
            4,
            static::allCategoryTemplatesProvider(),
            'AI-815: data provider MUST contain exactly 4 Category templates (default / images / skin-1 / horizontal-list-1).'
        );
    }
}
