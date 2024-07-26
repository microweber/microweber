<?php

namespace MicroweberPackages\Modules\SiteStats\Filament;


use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Pages\Page;
use Filament\Pages\BasePage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use MicroweberPackages\Content\Models\Content;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

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

        $statsRepository = new \MicroweberPackages\Modules\SiteStats\Repositories\SiteStatsRepository();
        $periodRangesDatesIntervals = $statsRepository->getRangesPeriod($startDate, $endDate, $period);

        $records = $statsRepository->getSessionsForPeriod($startDate, $endDate, $period);
        $bounced_records = $statsRepository->getSessionsForPeriod($startDate, $endDate, $period, 'bounced');
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
            Stat::make('Average time on page', '3:12'),
            Stat::make('Conversion rate', '3.2%'),

        ];
    }
}
