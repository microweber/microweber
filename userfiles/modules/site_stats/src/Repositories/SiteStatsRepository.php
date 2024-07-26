<?php

namespace MicroweberPackages\SiteStats\Repositories;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\Modules\SiteStats\Models\Log;
use MicroweberPackages\Modules\SiteStats\Models\Sessions;

class SiteStatsRepository
{

    public function getRangesPeriod($startDate = null, $endDate = null, $period = 'daily')
    {
        $periodRangesDatesIntervals = [];
        if ($period == 'daily') {
            $periodRanges = CarbonImmutable::parse($startDate)->daysUntil($endDate);
            foreach ($periodRanges as $periodRange) {
                $periodRangesDatesIntervals[$periodRange->format('Y-m-d')] = $periodRange->format('Y-m-d');
            }

        }

        if ($period == 'weekly') {
            $periodRanges = CarbonImmutable::parse($startDate)->weeksUntil($endDate);
            foreach ($periodRanges as $periodRange) {
                $periodRangesDatesIntervals[$periodRange->format('Y-W')] = $periodRange->format('Y-W');
            }
        }

        if ($period == 'monthly') {
            $periodRanges = CarbonImmutable::parse($startDate)->monthsUntil($endDate);
            foreach ($periodRanges as $periodRange) {
                $periodRangesDatesIntervals[$periodRange->format('Y-m')] = $periodRange->format('Y-m');
            }
        }
        if ($period == 'yearly') {
            $periodRanges = CarbonImmutable::parse($startDate)->yearsUntil($endDate);
            foreach ($periodRanges as $periodRange) {
                $periodRangesDatesIntervals[$periodRange->format('Y')] = $periodRange->format('Y');
            }

        }

        return $periodRangesDatesIntervals;
    }

    public function getVisitsForPeriod($startDate = null, $endDate = null, $period = 'daily')
    {

        $periodRangesDatesIntervals = $this->getRangesPeriod($startDate, $endDate, $period);
        $records = [];

        if ($periodRangesDatesIntervals) {
            foreach ($periodRangesDatesIntervals as $periodRangesDatesInterval) {

                $query = Sessions::query();

                if ($period == 'weekly') {

                    $query->whereYear('updated_at', '>=', date('Y', strtotime($startDate )));
                    $query->whereYear('updated_at', '<=', date('Y', strtotime($endDate )));

                } else {
                    $query->when($startDate, fn(Builder $query) => $query->whereDate('updated_at', '>=', $startDate));
                    $query->when($endDate, fn(Builder $query) => $query->whereDate('updated_at', '<=', $endDate));
                }

                if ($period == 'daily') {
                    $query->whereDate('updated_at', $periodRangesDatesInterval);
                }

                if ($period == 'weekly') {
                    $query->whereYear('updated_at', date('Y', strtotime($periodRangesDatesInterval . ' week')));
                    $query->whereBetween('updated_at', [
                        date('Y-m-d', strtotime($periodRangesDatesInterval . ' week')),
                        date('Y-m-d', strtotime($periodRangesDatesInterval . ' week +6 days')),
                    ]);

                }

                if ($period == 'monthly') {
                    $query->whereYear('updated_at', date('Y', strtotime($periodRangesDatesInterval)));
                    $query->whereMonth('updated_at', date('m', strtotime($periodRangesDatesInterval)));
                }

                if ($period == 'yearly') {
                    $query->whereYear('updated_at', $periodRangesDatesInterval);
                    $query->whereYear('updated_at', '<=', $periodRangesDatesInterval);
                    $query->whereYear('updated_at', '>=', $periodRangesDatesInterval);
                }

                $records[] = $query->count('id');
            }
        }

        return $records;

    }


}
