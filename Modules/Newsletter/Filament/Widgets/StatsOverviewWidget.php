<?php

namespace Modules\Newsletter\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use Modules\Newsletter\Models\NewsletterCampaignPixel;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriber;

class StatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getColumns(): int
    {
        return 2;
    }

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

        $charts = [];

        // Camapigns
        $charts[] = $this->getCampaignsChart($datesArray);

        // Subscribers
        $charts[] = $this->getSubscribersChart($datesArray);

        return $charts;

    }
}
