<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Carbon\CarbonImmutable;
use Modules\SiteStats\Repositories\SiteStatsRepository;
use Modules\SiteStats\Support\Stats;

abstract class AbstractAnalyticsTool extends BaseTool
{
    protected string $domain = 'analytics';

    protected array $requiredPermissions = ['view analytics'];

    protected function normalizePeriod(mixed $period): string
    {
        $period = is_string($period) ? trim($period) : '';

        return in_array($period, ['daily', 'weekly', 'monthly', 'yearly'], true) ? $period : 'daily';
    }

    /**
     * @return array{startDate: CarbonImmutable, endDate: CarbonImmutable}
     */
    protected function defaultDateRange(string $period): array
    {
        $endDate = CarbonImmutable::now();

        $startDate = match ($period) {
            'weekly' => $endDate->subWeeks(12),
            'monthly' => $endDate->subMonths(12),
            'yearly' => $endDate->subYears(5),
            default => $endDate->subDays(30),
        };

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

    protected function safeLimit(mixed $limit, int $default = 10, int $max = 50): int
    {
        return max(1, min($max, (int) ($limit ?? $default)));
    }

    protected function statsService(): Stats
    {
        $stats = new Stats();
        $stats->cache = false;
        $stats->cache_exp = 0;

        return $stats;
    }

    protected function statsRepository(): SiteStatsRepository
    {
        return new SiteStatsRepository();
    }

    protected function percent(int|float $value, int|float $total): string
    {
        if ($total <= 0) {
            return '0.0%';
        }

        return number_format(($value / $total) * 100, 1) . '%';
    }
}
