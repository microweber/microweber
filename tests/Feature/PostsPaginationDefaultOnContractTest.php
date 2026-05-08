<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Content\Microweber\ContentModule;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-81 / AI-62 / TICKET-JJ — Posts pagination default-on
 * regression coverage.
 *
 * Pins the contract that:
 *   - ContentModule (parent of PostModule) defines a
 *     DEFAULT_ITEMS_PER_PAGE = 6 constant.
 *   - render() computes pages_count + paging_param + current_page +
 *     total_count + page_size and exposes them on the view data so
 *     skins' `@if (isset($pages_count) && $pages_count > 1)`
 *     blocks actually render.
 *   - The page-size limit prefers options['data-limit'] over the
 *     default constant (admin-configurable).
 *   - The paging_param is namespaced per-module via an md5 prefix
 *     so multiple Posts modules on one page paginate
 *     independently.
 *
 * Style after the cycle-52..80 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class PostsPaginationDefaultOnContractTest extends TestCase
{
    private string $contentModuleSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->contentModuleSrc = file_get_contents(base_path(
            'Modules/Content/Microweber/ContentModule.php'
        ));
    }

    #[Test]
    public function default_items_per_page_constant_is_six(): void
    {
        $this->assertSame(
            6,
            ContentModule::DEFAULT_ITEMS_PER_PAGE,
            'ContentModule::DEFAULT_ITEMS_PER_PAGE must equal 6 — matches legacy Posts skin default'
        );
    }

    #[Test]
    public function render_computes_page_size_from_data_limit_option_with_default_fallback(): void
    {
        // page-size selection logic must prefer options['data-limit']
        // when set (admin-configurable) and fall back to the default
        // constant when missing.
        $this->assertMatchesRegularExpression(
            "/\\\$pageSize\\s*=\\s*isset\\(\\\$options\\['data-limit'\\]\\)\\s*&&\\s*\\(int\\)\\s*\\\$options\\['data-limit'\\]\\s*>\\s*0/s",
            $this->contentModuleSrc,
            'ContentModule::render(): \$pageSize must guard `isset(options[data-limit]) && (int)... > 0`'
        );
        $this->assertStringContainsString(
            'static::DEFAULT_ITEMS_PER_PAGE',
            $this->contentModuleSrc,
            'ContentModule::render(): \$pageSize must fall back to static::DEFAULT_ITEMS_PER_PAGE'
        );
    }

    #[Test]
    public function paging_param_is_namespaced_per_module(): void
    {
        // Multiple Post modules on the same page need independent
        // paging URL state. Pin md5-prefix shape so the param looks
        // like `page-abc12345` per module.
        $this->assertMatchesRegularExpression(
            "/\\\$pagingParam\\s*=\\s*'page-'\\s*\\.\\s*substr\\(md5\\(\\(string\\)\\s*\\\$moduleId\\),\\s*0,\\s*8\\)/s",
            $this->contentModuleSrc,
            'ContentModule::render(): paging_param must be namespaced via `page-` + 8-char md5 prefix of moduleId'
        );
    }

    #[Test]
    public function render_runs_separate_count_query_without_data_limit(): void
    {
        // The total count must NOT carry the data-limit, otherwise
        // pages_count would always be 1. Pin the unset() call AND
        // the second invocation of getQueryBuilderFromOptions for
        // counting.
        $this->assertStringContainsString(
            "unset(\$countOptions['data-limit']);",
            $this->contentModuleSrc,
            'ContentModule::render(): must unset data-limit on the count-options array — otherwise pages_count is always 1'
        );
        $this->assertMatchesRegularExpression(
            "/\\\$totalCount\\s*=\\s*static::getQueryBuilderFromOptions\\(\\\$countOptions\\)->count\\(\\)/s",
            $this->contentModuleSrc,
            'ContentModule::render(): must compute totalCount via a separate getQueryBuilderFromOptions call'
        );
    }

    #[Test]
    public function render_offsets_main_query_for_current_page(): void
    {
        // The visible-page query must apply offset() + limit() so
        // page 2+ actually returns the next batch.
        $this->assertStringContainsString(
            '$query->offset(($currentPage - 1) * $pageSize)->limit($pageSize)',
            $this->contentModuleSrc,
            'ContentModule::render(): page-2+ must apply offset(($currentPage - 1) * $pageSize)->limit($pageSize)'
        );
        // currentPage clamped to >= 1 so a malformed `?page-xxx=0`
        // or negative number doesn't break the query.
        $this->assertStringContainsString(
            "max(1, (int) request()->get(\$pagingParam, 1))",
            $this->contentModuleSrc,
            'ContentModule::render(): currentPage must be clamped via max(1, ...)'
        );
    }

    #[Test]
    public function render_exposes_canonical_view_data_keys(): void
    {
        $required = [
            'pages_count',
            'paging_param',
            'current_page',
            'total_count',
            'page_size',
        ];
        foreach ($required as $key) {
            $this->assertMatchesRegularExpression(
                "/\\\$viewData\\['{$key}'\\]\\s*=/s",
                $this->contentModuleSrc,
                "ContentModule::render(): \$viewData['{$key}'] assignment must be present"
            );
        }
    }

    #[Test]
    public function pages_count_uses_ceiling_division_to_round_up(): void
    {
        // ceil(21 / 6) = 4 — the last page has only 3 items but
        // must still be reachable. Pin the ceil() shape (a bare
        // intdiv / floor would drop the last page).
        $this->assertStringContainsString(
            '$pagesCount = (int) ceil($totalCount / max(1, $pageSize))',
            $this->contentModuleSrc,
            'ContentModule::render(): pagesCount must use ceil($totalCount / max(1, $pageSize)) so the partial last page is reachable'
        );
    }
}
