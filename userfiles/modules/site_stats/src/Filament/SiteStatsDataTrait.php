<?php

namespace MicroweberPackages\Modules\SiteStats\Filament;

use Carbon\CarbonImmutable;

trait SiteStatsDataTrait
{

    public function getPeriodsDataFromFilter()
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $period = $this->filters['period'] ?? 'daily';
        $periodRangesDatesIntervals = [];
        $title = 'Visitors';
        if ($period == 'daily') {
            if ($startDate == null) {
                //30 days ago
                $startDate = CarbonImmutable::now()->subDays(30);
            }
            if ($endDate == null) {
                $endDate = CarbonImmutable::now();
            }
            $title = 'Visitors per day';
        }

        if ($period == 'weekly') {
            if ($startDate == null) {
                //12 weeks ago
                $startDate = CarbonImmutable::now()->subWeeks(12);
            }
            if ($endDate == null) {
                $endDate = CarbonImmutable::now();
            }
            $title = 'Visitors per week';
        }

        if ($period == 'monthly') {
            if ($startDate == null) {
                //12 months ago
                $startDate = CarbonImmutable::now()->subMonths(12);
            }
            if ($endDate == null) {
                $endDate = CarbonImmutable::now();
            }
            $title = 'Visitors per month';
        }

        if ($period == 'yearly') {
            if ($startDate == null) {
                //5 years ago
                $startDate = CarbonImmutable::now()->subYears(5);
            }
            if ($endDate == null) {
                $endDate = CarbonImmutable::now();
            }
            $title = 'Visitors per year';
        }


        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'period' => $period,
            'title' => $title
        ];
    }


}
