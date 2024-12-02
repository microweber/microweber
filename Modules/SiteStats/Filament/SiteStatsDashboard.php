<?php

namespace Modules\SiteStats\Filament;


use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\SiteStats\Repositories\SiteStatsRepository;

class SiteStatsDashboard extends BaseWidget
{
    use InteractsWithPageFilters;
    use SiteStatsDataTrait;


    protected function getStats(): array
    {

        $periodsDataFromFilter = $this->getPeriodsDataFromFilter();

        $startDate = $periodsDataFromFilter['startDate'];
        $endDate = $periodsDataFromFilter['endDate'];
        $period = $periodsDataFromFilter['period'];

        $statsRepository = new SiteStatsRepository();
        $periodRangesDatesIntervals = $statsRepository->getRangesPeriod($startDate, $endDate, $period);

        $records = $statsRepository->getSessionsForPeriod($startDate, $endDate, $period);
        $bounced_records = $statsRepository->getBouncedSessionsForPeriod($startDate, $endDate, $period);
       // $time_on_site = $statsRepository->getAvgTimeOnSiteForSessionsForPeriod($startDate, $endDate, $period);
      //  dd($time_on_site);

        $totalVisitors = 0;
        $totalBounced = 0;

        if ($records) {
            foreach ($records as $record) {
                $totalVisitors += $record;
            }
        }

        if ($bounced_records) {
            foreach ($bounced_records as $record) {
                $totalBounced += $record;
            }
        }

        if (!$totalVisitors) {
            return [];
        }

        $bouncePercent = 0;
        if ($totalBounced) {
            $bouncePercent = ($totalBounced / $totalVisitors) * 100;
        }

        return [
            Stat::make('Total visitors', $totalVisitors),
            Stat::make('Bounce rate', intval($bouncePercent) . '%'),
          //  Stat::make('Average time on page', '3:12'),
          //  Stat::make('Conversion rate', '3.2%'),

        ];
    }
}
