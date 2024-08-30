<?php

namespace MicroweberPackages\Modules\SiteStats\Filament;


use Filament\Pages\Dashboard\Concerns\HasFilters;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Widgets\ChartWidget;
use Carbon\CarbonImmutable;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use MicroweberPackages\Modules\SiteStats\Repositories\SiteStatsRepository;


class SiteStatsDashboardChart extends ChartWidget
{
    use InteractsWithPageFilters;
    use SiteStatsDataTrait;

    protected int|string|array $columnSpan = 'full';


    protected static ?string $maxHeight = '200px';
    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    public function getHeading(): string
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $period = $this->filters['period'] ?? 'daily';


        $title = 'Visitors';
        if ($period == 'daily') {
            $title = 'Daily Visitors';
        }
        if ($period == 'weekly') {
            $title = 'Weekly Visitors';
        }
        if ($period == 'monthly') {
            $title = 'Monthly Visitors';
        }
        if ($period == 'yearly') {
            $title = 'Yearly Visitors';
        }


        return $title;
    }

    protected function getData(): array
    {


        $periodsDataFromFilter = $this->getPeriodsDataFromFilter();

        $startDate = $periodsDataFromFilter['startDate'];
        $endDate = $periodsDataFromFilter['endDate'];
        $period = $periodsDataFromFilter['period'];
        $title = $periodsDataFromFilter['title'];


        $statsRepository = new SiteStatsRepository();

        $periodRangesDatesIntervals = $statsRepository->getRangesPeriod($startDate, $endDate, $period);

        $records = $statsRepository->getSessionsForPeriod($startDate, $endDate, $period);


        return [
            'datasets' => [
                [
                    'label' => $title,
                    'data' => array_map('floatval', $records),
                ],
            ],
            // 'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'labels' => array_keys($periodRangesDatesIntervals),
        ];


    }

}
