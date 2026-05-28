<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1090 / task-2026-05-28-2f5a6c Batch 2.
 *
 * Scope (dispatch verbatim): "/admin/orders Average price stat card
 * has no currency symbol."
 *
 * Failure family pinned by this test:
 *
 *  OrderStats::formatAveragePriceByCurrency() returned a bare "0.00"
 *  string when no orders carried an `amount` (fresh install / clean
 *  table). The populated branches already prefixed each row with the
 *  resolved per-currency symbol (or shop-default via currency_symbol())
 *  so the discrepancy was empty-state-only: empty installs saw a
 *  symbol-less number on the stat card while populated installs saw
 *  "$ 12.34" / "€ 12.34" / etc.
 *
 *  Fix: resolve $defaultSymbol BEFORE the isEmpty() branch and prefix
 *  the empty-state return with it ("$ 0.00" / "€ 0.00" / etc.) so the
 *  stat card never renders a symbol-less price. Same shop-default
 *  resolution pattern as AI-818 (currency_symbol() ?: '$').
 *
 * The source file carries the task-id marker comment per the
 * per-task source-comment marker convention.
 */
class AdminOrders1090AveragePriceCurrencyContractTest extends TestCase
{
    private string $orderStats;

    private string $orderStatsStripped;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderStats = (string) file_get_contents(
            self::projectRoot()
            . '/Modules/Order/Filament/Admin/Resources/OrderResource/Widgets/OrderStats.php'
        );

        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->orderStats);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);
        $this->orderStatsStripped = $stripped;
    }

    public function test_empty_branch_returns_default_symbol_prefixed_zero(): void
    {
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*\$rows->isEmpty\(\)\s*\)\s*\{\s*return\s+\$defaultSymbol\s*\.\s*[\'"]\s*0\.00[\'"]\s*;/',
            $this->orderStatsStripped,
            'AI-1090: empty-rows branch must return $defaultSymbol . " 0.00" so the Average-price stat card never renders a symbol-less number on a fresh install'
        );
    }

    public function test_empty_branch_no_longer_returns_bare_zero_string(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/if\s*\(\s*\$rows->isEmpty\(\)\s*\)\s*\{\s*return\s+[\'"]0\.00[\'"]\s*;/',
            $this->orderStatsStripped,
            'AI-1090: legacy bare return "0.00" (no currency symbol) must be gone from the empty-rows branch — pre-fix shape was the empty-state defect'
        );
    }

    public function test_default_symbol_resolved_before_empty_check(): void
    {
        $defaultSymbolPos = strpos($this->orderStatsStripped, '$defaultSymbol = function_exists');
        $emptyCheckPos = strpos($this->orderStatsStripped, '$rows->isEmpty()');

        $this->assertNotFalse(
            $defaultSymbolPos,
            'AI-1090: $defaultSymbol must be initialised via function_exists(\'currency_symbol\') guard'
        );

        $this->assertNotFalse(
            $emptyCheckPos,
            'AI-1090: $rows->isEmpty() guard must remain in the source'
        );

        $this->assertLessThan(
            $emptyCheckPos,
            $defaultSymbolPos,
            'AI-1090: $defaultSymbol must be resolved BEFORE the isEmpty() check so the empty-state branch can prefix the symbol — pre-fix shape resolved it AFTER, which is why the empty branch returned a bare number'
        );
    }

    public function test_default_symbol_uses_currency_symbol_helper(): void
    {
        $this->assertMatchesRegularExpression(
            '/\$defaultSymbol\s*=\s*function_exists\(\s*[\'"]currency_symbol[\'"]\s*\)\s*\?\s*\(\s*currency_symbol\(\)\s*\?:\s*[\'"]\$[\'"]\s*\)\s*:\s*[\'"]\$[\'"]\s*;/',
            $this->orderStatsStripped,
            'AI-1090: $defaultSymbol must resolve via currency_symbol() with $ fallback (AI-818 shop-default pattern)'
        );
    }

    public function test_populated_branches_still_emit_currency_symbol(): void
    {
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*\$rows->count\(\)\s*===\s*1\s*\)\s*\{[\s\S]*?return\s+\$symbol\s*\.\s*[\'"][^\'"]*[\'"]\s*\.\s*number_format/',
            $this->orderStatsStripped,
            'AI-1090: single-currency branch must still prefix the value with $symbol (regression guard — empty-state fix must not collapse the populated branches)'
        );
    }

    public function test_ai_1090_task_id_marker_in_order_stats(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-2f5a6c / AI-1090',
            $this->orderStats,
            'AI-1090: task-id marker comment must be present adjacent to the currency-symbol fix in OrderStats.php'
        );
    }
}
