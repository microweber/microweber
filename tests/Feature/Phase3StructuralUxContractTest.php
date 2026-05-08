<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-61 / TASK-021 / TICKET-J + N + O + R / AI-39 — Phase 3
 * structural UX sweep regression coverage.
 *
 * Pins:
 *   J: OrderStats widget aggregates by currency (no cross-currency
 *      sum/avg). OrderStatsService.getBestSellingProductsForPeriod
 *      groups by currency too.
 *   N: ListContents page no longer carries a CreateAction header
 *      button — duplicate of the topbar "+ Add New" dropdown is
 *      removed.
 *   O: OrderResource gains a non-empty filters() list (status +
 *      completed + paid) so it matches the Customer/Newsletter
 *      filter pattern.
 *   R: mobile-touch.css hides `.fi-global-search-field` on
 *      `.fi-resource-list-records-page` so list pages present
 *      exactly one search affordance (the per-table searchable()
 *      input).
 *
 * Style after the cycle-52..60 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class Phase3StructuralUxContractTest extends TestCase
{
    private string $orderStatsWidget;
    private string $orderStatsService;
    private string $orderResource;
    private string $listContents;
    private string $mobileTouchCss;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderStatsWidget = file_get_contents(base_path(
            'Modules/Order/Filament/Admin/Resources/OrderResource/Widgets/OrderStats.php'
        ));
        $this->orderStatsService = file_get_contents(base_path(
            'Modules/Order/Services/OrderStatsService.php'
        ));
        $this->orderResource = file_get_contents(base_path(
            'Modules/Order/Filament/Admin/Resources/OrderResource.php'
        ));
        $this->listContents = file_get_contents(base_path(
            'Modules/Content/Filament/Admin/ContentResource/Pages/ListContents.php'
        ));
        $this->mobileTouchCss = file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css'
        ));
    }

    #[Test]
    public function ticket_j_order_stats_widget_groups_avg_by_currency(): void
    {
        // Bare $this->getPageTableQuery()->avg('amount') is the broken
        // shape — sums across currencies. The fix routes through a
        // helper that group-bys currency; the test pins both shapes.
        $this->assertStringNotContainsString(
            "->avg('amount'), 2)",
            $this->orderStatsWidget,
            'OrderStats: bare ->avg(amount) cross-currency arithmetic must NOT remain'
        );
        $this->assertStringContainsString(
            'protected function formatAveragePriceByCurrency()',
            $this->orderStatsWidget,
            'OrderStats: must define formatAveragePriceByCurrency() helper'
        );
        $this->assertMatchesRegularExpression(
            "/selectRaw\\(['\"]currency,\\s*AVG\\(amount\\)\\s+as\\s+avg_amount['\"]\\)/i",
            $this->orderStatsWidget,
            'OrderStats: helper must group avg(amount) by currency via selectRaw'
        );
        $this->assertStringContainsString(
            "->groupBy('currency')",
            $this->orderStatsWidget,
            'OrderStats: helper must groupBy(currency)'
        );
    }

    #[Test]
    public function ticket_j_order_stats_service_groups_best_sellers_by_currency(): void
    {
        // getBestSellingProductsForPeriod() also did a cross-currency
        // sum on `cart_orders.amount`. Pin that the projection +
        // groupBy now both include currency.
        $this->assertMatchesRegularExpression(
            "/->select\\([^)]*currency\\s+as\\s+currency/",
            $this->orderStatsService,
            'OrderStatsService: best-sellers projection must include `currency as currency`'
        );
        $this->assertMatchesRegularExpression(
            "/->groupBy\\([^)]*'cart\\.rel_id'[^)]*currency/",
            $this->orderStatsService,
            'OrderStatsService: best-sellers groupBy must include currency alongside cart.rel_id'
        );
    }

    #[Test]
    public function ticket_n_list_contents_no_longer_has_create_action(): void
    {
        // The duplicate header CreateAction is gone — the topbar
        // "+ Add New" dropdown is the canonical entry point.
        $this->assertStringNotContainsString(
            'Actions\\CreateAction::make()',
            $this->listContents,
            'ListContents: header CreateAction must be removed (duplicates the topbar Add New dropdown)'
        );
        // Sanity: the getHeaderActions() method still exists (it is
        // empty but stays so the LocaleSwitcher can be wired later).
        $this->assertStringContainsString(
            'protected function getHeaderActions(): array',
            $this->listContents,
            'ListContents: getHeaderActions() must still exist as the extension point'
        );
    }

    #[Test]
    public function ticket_o_order_resource_carries_non_empty_filters(): void
    {
        // OrderResource was the only list resource without filters.
        // Pin the canonical 3-filter set: order_status SelectFilter,
        // order_completed TernaryFilter, is_paid TernaryFilter.
        $this->assertMatchesRegularExpression(
            "/Tables\\\\Filters\\\\SelectFilter::make\\('order_status'\\)/",
            $this->orderResource,
            'OrderResource: must carry SelectFilter on order_status'
        );
        $this->assertMatchesRegularExpression(
            "/Tables\\\\Filters\\\\TernaryFilter::make\\('order_completed'\\)/",
            $this->orderResource,
            'OrderResource: must carry TernaryFilter on order_completed'
        );
        $this->assertMatchesRegularExpression(
            "/Tables\\\\Filters\\\\TernaryFilter::make\\('is_paid'\\)/",
            $this->orderResource,
            'OrderResource: must carry TernaryFilter on is_paid'
        );

        // Negative: bare empty `->filters([\n            \n            ])`
        // pattern from before the fix must be gone.
        $this->assertDoesNotMatchRegularExpression(
            "/->filters\\(\\[\\s*\\n\\s*\\n\\s*\\]\\)/",
            $this->orderResource,
            'OrderResource: empty filters() block must be filled'
        );
    }

    #[Test]
    public function ticket_r_global_search_hidden_on_list_pages(): void
    {
        // Single search affordance per AC. Scoped to
        // .fi-resource-list-records-page so dashboards / edit /
        // view / settings pages keep their global search.
        $this->assertStringContainsString(
            '.fi-resource-list-records-page .fi-topbar .fi-global-search-field',
            $this->mobileTouchCss,
            'mobile-touch.css: must scope the global-search hide rule to .fi-resource-list-records-page'
        );
        $this->assertMatchesRegularExpression(
            '/\\.fi-resource-list-records-page\\s+\\.fi-topbar\\s+\\.fi-global-search-field\\s*\\{\\s*display:\\s*none/',
            $this->mobileTouchCss,
            'mobile-touch.css: scoped global-search hide rule must apply display: none'
        );

        // Negative: an UNSCOPED `.fi-global-search-field { display: none }`
        // would remove the global search EVERYWHERE — guard against that.
        $this->assertDoesNotMatchRegularExpression(
            '/^\\s*\\.fi-global-search-field\\s*\\{\\s*display:\\s*none/m',
            $this->mobileTouchCss,
            'mobile-touch.css: must NOT carry an unscoped `.fi-global-search-field { display: none }` — keep the rule scoped to list pages'
        );
    }
}
