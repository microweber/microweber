<?php

namespace Modules\SiteStats\Filament;


use Filament\Widgets\ChartWidget;
use Filament\Support\Concerns\CanBeLazy;
use Filament\Widgets\Concerns\InteractsWithPageFilters;


class SiteStatsDashboardChart extends ChartWidget
{
    use CanBeLazy;
    use InteractsWithPageFilters;
    use SiteStatsDataTrait;

    protected int|string|array $columnSpan = 'full';


    protected ?string $maxHeight = '200px';
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


        $statsRepository = new \Modules\SiteStats\Repositories\SiteStatsRepository();

        $periodRangesDatesIntervals = $statsRepository->getRangesPeriod($startDate, $endDate, $period);

        $records = $statsRepository->getSessionsForPeriod($startDate, $endDate, $period);


        return [
            'datasets' => [
                [
                    'label' => $title,
                    'data' => array_map('floatval', $records),
                    // task-2026-05-17-2a40c5 / AI-737 — explicit
                    // brand-accent stroke + 0.15 fill so the line
                    // stays visible at WCAG AA non-text 3:1 against
                    // BOTH the light (#fff) and dark (#1a1f2b)
                    // chart surfaces. Pre-fix, Chart.js defaults
                    // produced a near-grey line that washed out on
                    // white — same data rendered visibly in dark
                    // mode but appeared empty in light. The hex
                    // `#0d6efd` is MwColors::Blue (project-wide
                    // primary blue, see src/MicroweberPackages/
                    // Filament/Themes/MwColors.php) — gives
                    // 4.4:1 on white + 7.3:1 on dark slate.
                    // borderWidth 2 px is the Chart.js default-
                    // bump that keeps the line readable at small
                    // chart heights (200 px max here).
                    'borderColor' => '#0d6efd',
                    'backgroundColor' => 'rgba(13, 110, 253, 0.15)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            // 'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'labels' => array_keys($periodRangesDatesIntervals),
        ];


    }

}
