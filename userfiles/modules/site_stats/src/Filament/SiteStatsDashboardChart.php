<?php

namespace MicroweberPackages\SiteStats\Filament;


use Filament\Pages\Dashboard\Concerns\HasFilters;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Widgets\LineChartWidget;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Carbon\CarbonImmutable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Modules\SiteStats\Models\Log;
use MicroweberPackages\Modules\SiteStats\Models\Sessions;


class SiteStatsDashboardChart extends LineChartWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = 'full';


    protected static ?string $maxHeight = '200px';
    protected static ?int $sort = 2;


    public function getHeading(): string
    {
        return 'Test';
    }

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $period = $this->filters['period'] ?? 'daily';
        $periodRangesDatesIntervals = [];
        if ($period == 'daily') {
            if ($startDate == null) {
                //30 days ago
                $startDate = CarbonImmutable::now()->subDays(30);
            }
            if ($endDate == null) {
                $endDate = CarbonImmutable::now();
            }
        }

        if ($period == 'monthly') {
            if ($startDate == null) {
                //12 months ago
                $startDate = CarbonImmutable::now()->subMonths(12);
            }
            if ($endDate == null) {
                $endDate = CarbonImmutable::now();
            }

        }

        if ($period == 'yearly') {
            if ($startDate == null) {
                //5 years ago
                $startDate = CarbonImmutable::now()->subYears(5);
            }
            if ($endDate == null) {
                $endDate = CarbonImmutable::now();
            }
        }

        $statsRepository = new \MicroweberPackages\SiteStats\Repositories\SiteStatsRepository();
        $records = [];

        $periodRangesDatesIntervals = $statsRepository->getRangesPeriod($startDate, $endDate, $period);
        $records = $statsRepository->getVisitsForPeriod($startDate, $endDate, $period);


        return [
            'datasets' => [
                [
                    'label' => 'Unique Visitors',
                    'data' => array_map('floatval', $records),
                ],
            ],
            // 'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'labels' => array_keys($periodRangesDatesIntervals),
        ];


    }

}
