<?php

declare(strict_types=1);

namespace Modules\SiteStats\Tests\Unit;

use Modules\SiteStats\Tools\AnalyticsAudienceBreakdownTool;
use Modules\SiteStats\Tools\AnalyticsTopPagesTool;
use Modules\SiteStats\Tools\AnalyticsTrafficReferrersTool;
use Modules\SiteStats\Tools\AnalyticsTrafficSummaryTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * SQL-portability guard for the MCP analytics tools.
 *
 * These tools wrap their query logic in try/catch and, on any DB exception,
 * return a "Error building …" string which the MCP layer surfaces as
 * result.isError=true. A MySQL-only SQL construct (a raw date function, an
 * ONLY_FULL_GROUP_BY-violating select, …) therefore shows up not as a thrown
 * error but as that error STRING — and only on the engine that rejects it. The
 * McpControllerTest covers the `daily` period on the MySQL test DB; this pins
 * every tool across EVERY period and whatever engine the suite runs on
 * (MySQL in CI, sqlite locally), so a non-portable query can't silently return
 * isError again (the stale "fails on sqlite" incident, now resolved).
 *
 * Deliberately data-free: an empty result renders a "No data" message, which is
 * fine — a SQL-portability fault fires from the query compiling/running, not
 * from the row count, so no fixtures are needed to catch it. authorize() is a
 * no-op in BaseTool today, so no auth setup is required either.
 */
final class AnalyticsToolsPortabilityTest extends TestCase
{
    /**
     * @return array<string, array{class-string, string}>
     */
    public static function toolPeriodProvider(): array
    {
        $tools = [
            'audience_breakdown' => AnalyticsAudienceBreakdownTool::class,
            'traffic_summary' => AnalyticsTrafficSummaryTool::class,
            'top_pages' => AnalyticsTopPagesTool::class,
            'traffic_referrers' => AnalyticsTrafficReferrersTool::class,
        ];

        $cases = [];
        foreach ($tools as $name => $class) {
            foreach (['daily', 'weekly', 'monthly', 'yearly'] as $period) {
                $cases["{$name} / {$period}"] = [$class, $period];
            }
        }

        return $cases;
    }

    #[Test]
    #[DataProvider('toolPeriodProvider')]
    public function analytics_tool_runs_without_a_db_error_on_this_engine(string $toolClass, string $period): void
    {
        /** @var \Modules\SiteStats\Tools\AbstractAnalyticsTool $tool */
        $tool = new $toolClass();

        $output = $tool->__invoke(period: $period);

        $this->assertIsString($output);
        $this->assertStringNotContainsString(
            'Error building',
            $output,
            "{$toolClass} returned a DB-error result for period '{$period}' on engine '"
                . \DB::connection()->getDriverName()
                . "' — a non-portable (likely MySQL-only) query. Make the query engine-agnostic."
        );
    }
}
