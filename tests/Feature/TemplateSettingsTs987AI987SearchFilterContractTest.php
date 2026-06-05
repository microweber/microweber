<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-ts987 / AI-987 — Live-edit Template Settings panel search.
 * Jira: https://microweber.atlassian.net/browse/AI-987
 *
 * The Template Settings right-rail panel lists all theme variables in a long
 * top-level list (showStyleSettings == '/'). For themes with 30+ variables
 * users had to scroll to find one. A search/filter input was added that
 * filters items by name as the user types.
 *
 * Implementation (Alpine, no Livewire round-trip):
 *   - `filterQuery` added to the panel x-data.
 *   - a `type="search"` input bound via x-model, shown only at the top level.
 *   - each filterable item (style-group links + named settings) carries its
 *     lowercased name in a data-mw-ts-name attribute and ANDs a match check
 *     into its existing x-show. The label is read via $el.dataset — it never
 *     enters the Alpine expression string (avoids the Stage-5 attribute
 *     escape-leak family). Empty query shows everything (no behaviour change).
 */
class TemplateSettingsTs987AI987SearchFilterContractTest extends TestCase
{
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Template/resources/views/livewire/live-edit/template-settings-sidebar.blade.php'
        ));
    }

    #[Test]
    public function xdata_declares_filter_query_state(): void
    {
        $this->assertStringContainsString("filterQuery: ''", $this->blade,
            'Panel x-data must declare filterQuery state for the search filter.');
    }

    #[Test]
    public function search_input_present_and_bound(): void
    {
        $this->assertStringContainsString('type="search"', $this->blade,
            'Template Settings panel must render a type="search" input.');
        $this->assertStringContainsString('x-model="filterQuery"', $this->blade,
            'Search input must be bound to filterQuery via x-model.');
        $this->assertStringContainsString('aria-label="Search template settings"', $this->blade,
            'Search input must carry an accessible label.');
        // Shown only at the top level so it does not appear inside drill-downs.
        $this->assertMatchesRegularExpression(
            '/mw-ts-search-wrap[^>]*x-show="showStyleSettings == \'\/\'"/s',
            $this->blade,
            'Search wrapper must be gated on the top-level view (showStyleSettings == \'/\').'
        );
    }

    #[Test]
    public function filterable_items_carry_name_data_attribute(): void
    {
        // Both filterable surfaces (style-group links + named settings) expose
        // their lowercased label through data-mw-ts-name. Expect at least two
        // occurrences (one per surface).
        $count = substr_count($this->blade, 'data-mw-ts-name=');
        $this->assertGreaterThanOrEqual(2, $count,
            'Both the style-group links and the named-setting links must carry data-mw-ts-name.');
    }

    #[Test]
    public function filter_match_reads_dataset_not_label_literal(): void
    {
        // The match expression must read the name from the element dataset, so
        // the (user/theme-controlled) label never lands inside the Alpine
        // expression string — Stage-5 attribute-escape-leak guard.
        $this->assertStringContainsString(
            "(\$el.dataset.mwTsName || '').includes(filterQuery.toLowerCase())",
            $this->blade,
            'Filter must match via $el.dataset.mwTsName, not by interpolating the label into the expression.'
        );
    }

    #[Test]
    public function empty_query_shows_everything(): void
    {
        // The filter clause is OR-guarded on `filterQuery === ''` so the
        // default (empty) state renders every item exactly as before.
        $this->assertStringContainsString(
            "filterQuery === '' ||",
            $this->blade,
            'Empty query must short-circuit to show all items (no behaviour change at rest).'
        );
    }
}
