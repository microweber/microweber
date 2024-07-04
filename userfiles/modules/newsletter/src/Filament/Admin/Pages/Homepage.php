<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;

class Homepage extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

//    protected static ?string $navigationGroup = 'Email Marketing';

//    protected static ?string $slug = 'newsletter/homepage';

   // protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.homepage-new';

//    protected static bool $shouldRegisterNavigation = false;

    protected function __getViewData(): array
    {
        $campaignsCount = NewsletterCampaign::count();
        $listsCount = NewsletterList::count();
        $emailsSentCount = NewsletterCampaignsSendLog::count();
        $subscribersCount = NewsletterSubscriber::count();

        $dashboardStats = [
            [
                'name' => 'Subscribers',
                'value' => $subscribersCount,
                'icon' => 'heroicon-o-bell-alert',
            ],
            [
                'name' => 'Lists',
                'value' => $listsCount,
                'icon' => 'heroicon-o-queue-list',
            ],
            [
                'name' => 'Campaigns',
                'value' => $campaignsCount,
                'icon' => 'heroicon-o-envelope',
            ],
            [
                'name' => 'Emails Sent',
                'value' => $emailsSentCount,
                'icon' => 'heroicon-o-envelope',
            ],
        ];

        return [
            'dashboardStats' => $dashboardStats,
        ];
    }
}
