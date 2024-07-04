<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;

class StatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    private function getSubscribersChart($datesArray)
    {
        $subscribersCount = NewsletterSubscriber::count();

        $subscribersCountByDates = [];
        foreach ($datesArray as $date) {
            $subscribersCountByDates[$date] = NewsletterSubscriber::whereDate('created_at', $date)->count();
        }

        $subscribersChart = Stat::make('Subscribers', $subscribersCount)
            ->description('Newsletter subscribers')
            ->chart($subscribersCountByDates);
        if ($subscribersCount === 0) {
            $subscribersChart->color('gray');
            $subscribersChart->descriptionIcon('heroicon-m-arrow-trending-down');
        } elseif (end($subscribersCountByDates) > 0) {
            $subscribersChart->descriptionIcon('heroicon-m-arrow-trending-up');
            $subscribersChart->color('success');
        }

        return $subscribersChart;
    }
    private function getCampaignsChart($datesArray)
    {
        $campaignsCount = NewsletterCampaign::count();

        $campaignsCountByDates = [];
        foreach ($datesArray as $date) {
            $campaignsCountByDates[$date] = NewsletterCampaign::whereDate('created_at', $date)->count();
        }

        $campaignsChart = Stat::make('Campaigns', $campaignsCount)
            ->description('E-mail marketing campaigns')
            ->chart($campaignsCountByDates);
        if ($campaignsCount === 0) {
            $campaignsChart->color('gray');
            $campaignsChart->descriptionIcon('heroicon-m-arrow-trending-down');
        } elseif (end($campaignsCountByDates) > 0) {
            $campaignsChart->descriptionIcon('heroicon-m-arrow-trending-up');
            $campaignsChart->color('success');
        }
        return $campaignsChart;
    }

    protected function getStats(): array
    {
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now()->endOfDay();
        $datesArray = [];
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $datesArray[] = $date->format('Y-m-d');
        }

        $listsCount = NewsletterList::count();
        $emailsSentCount = NewsletterCampaignsSendLog::count();


        $charts = [];

        // Camapigns
        $charts[] = $this->getCampaignsChart($datesArray);

        // Subscribers
        $charts[] = $this->getSubscribersChart($datesArray);

        return $charts;


        return [
            Stat::make('Lists', $listsCount)
                ->description('3% decrease')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart([17, 16, 14, 15, 14, 13, 12])
                ->color('danger'),
            Stat::make('Emails sent', $emailsSentCount)
                ->description('7% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),
            Stat::make('Subscribers', $subscribersCount)
                ->description('7% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),
        ];
    }
}
