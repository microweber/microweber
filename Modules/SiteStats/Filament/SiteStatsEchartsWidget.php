<?php

namespace Modules\SiteStats\Filament;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;
use Modules\SiteStats\Repositories\SiteStatsRepository;

class SiteStatsEchartsWidget extends Widget
{
    use InteractsWithPageFilters;
    use SiteStatsDataTrait;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'modules.site_stats::filament.echarts-widget';

    protected static bool $isLazy = false;

    protected ?array $cachedChartData = null;

    public function getChartData(): array
    {
        if ($this->cachedChartData !== null) {
            return $this->cachedChartData;
        }

        $periodsDataFromFilter = $this->getPeriodsDataFromFilter();

        $startDate = $periodsDataFromFilter['startDate'];
        $endDate = $periodsDataFromFilter['endDate'];
        $period = $periodsDataFromFilter['period'];
        $title = $periodsDataFromFilter['title'];

        $statsRepository = new SiteStatsRepository();
        $periodRangesDatesIntervals = $statsRepository->getRangesPeriod($startDate, $endDate, $period);
        $records = $statsRepository->getSessionsForPeriod($startDate, $endDate, $period);
        $bounced = $statsRepository->getBouncedSessionsForPeriod($startDate, $endDate, $period);

        $labels = array_keys($periodRangesDatesIntervals);
        $visitors = array_map('floatval', $records);
        $bouncedData = array_map('floatval', $bounced);

        $totalVisitors = array_sum($visitors);
        $totalBounced = array_sum($bouncedData);
        $bouncePercent = $totalVisitors > 0 ? intval(($totalBounced / $totalVisitors) * 100) : 0;

        $this->cachedChartData = [
            'labels' => array_values($labels),
            'visitors' => array_values($visitors),
            'bounced' => array_values($bouncedData),
            'title' => $title,
            'totalVisitors' => $totalVisitors,
            'totalBounced' => $totalBounced,
            'bouncePercent' => $bouncePercent,
        ];

        return $this->cachedChartData;
    }

    public function getHeading(): string
    {
        $period = $this->filters['period'] ?? 'daily';

        return match ($period) {
            'weekly' => 'Weekly Visitors',
            'monthly' => 'Monthly Visitors',
            'yearly' => 'Yearly Visitors',
            default => 'Daily Visitors',
        };
    }

    public function getOnlineCount(): int
    {
        return Cache::remember('site_stats_online_count', 60, function () {
            try {
                return \Modules\SiteStats\Models\Sessions::query()
                    ->where('updated_at', '>=', now()->subMinutes(5))
                    ->distinct('session_id')
                    ->count('session_id');
            } catch (\Throwable $e) {
                return 0;
            }
        });
    }
}
