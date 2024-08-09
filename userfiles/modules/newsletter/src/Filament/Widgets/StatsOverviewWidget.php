<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignPixel;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;

class StatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    private function getEmailsSentChart($datesArray)
    {
        $emailsSentCount = NewsletterCampaignsSendLog::count();

        $emailsSentCountByDates = [];
        foreach ($datesArray as $date) {
            $emailsSentCountByDates[$date] = NewsletterCampaignsSendLog::whereDate('created_at', $date)->count();
        }

        $emailsSentChart = Stat::make('Emails Sent', $emailsSentCount)
            ->description('E-mails sent')
            ->chart($emailsSentCountByDates);
        if ($emailsSentCount === 0) {
            $emailsSentChart->color('gray');
            $emailsSentChart->descriptionIcon('heroicon-m-arrow-trending-down');
        } elseif (end($emailsSentCountByDates) > 0) {
            $emailsSentChart->descriptionIcon('heroicon-m-arrow-trending-up');
            $emailsSentChart->color('success');
        }

        return $emailsSentChart;

    }

    private function getEmailsOpenedChart($datesArray)
    {
        $emailsOpenedCount = NewsletterCampaignPixel::count();

        $emailsOpenedCountByDates = [];
        foreach ($datesArray as $date) {
            $emailsOpenedCountByDates[$date] = NewsletterCampaignPixel::whereDate('created_at', $date)->count();
        }

        $emailsOpenedChart = Stat::make('Emails opened', $emailsOpenedCount)
            ->description('E-mails opened')
            ->chart($emailsOpenedCountByDates);
        if ($emailsOpenedCount === 0) {
            $emailsOpenedChart->color('gray');
            $emailsOpenedChart->descriptionIcon('heroicon-m-arrow-trending-down');
        } elseif (end($emailsOpenedCountByDates) > 0) {
            $emailsOpenedChart->descriptionIcon('heroicon-m-arrow-trending-up');
            $emailsOpenedChart->color('success');
        }

        return $emailsOpenedChart;

    }

    private function getEmailsClickedChart($datesArray)
    {
        $emailsclickedCount = NewsletterCampaignClickedLink::count();

        $emailsClickedCountByDates = [];
        foreach ($datesArray as $date) {
            $emailsClickedCountByDates[$date] = NewsletterCampaignClickedLink::whereDate('created_at', $date)->count();
        }

        $emailsClickedChart = Stat::make('Emails clicked', $emailsclickedCount)
            ->description('E-mails clicked')
            ->chart($emailsClickedCountByDates);
        if ($emailsclickedCount === 0) {
            $emailsClickedChart->color('gray');
            $emailsClickedChart->descriptionIcon('heroicon-m-arrow-trending-down');
        } elseif (end($emailsClickedCountByDates) > 0) {
            $emailsClickedChart->descriptionIcon('heroicon-m-arrow-trending-up');
            $emailsClickedChart->color('success');
        }

        return $emailsClickedChart;

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

        // Emails Sent
        $charts[] = $this->getEmailsSentChart($datesArray);

        $charts[] = $this->getEmailsOpenedChart($datesArray);
        $charts[] = $this->getEmailsClickedChart($datesArray);

        return $charts;

    }
}
