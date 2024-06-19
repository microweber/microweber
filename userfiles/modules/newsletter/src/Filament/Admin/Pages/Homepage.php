<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;

use Filament\Pages\Page;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;

class Homepage extends Page
{

    protected static ?string $slug = 'newsletter/homepage';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.homepage';

    protected function getViewData(): array
    {
        $campaignsCount = NewsletterCampaign::count();
        $listsCount = NewsletterList::count();
        $emailsSentCount = NewsletterCampaignsSendLog::count();
        $subscribersCount = NewsletterSubscriber::count();

        $tabLinks = [
            [
                'name' => 'Campaigns',
                'icon' => 'heroicon-o-envelope',
                'route' => 'filament.admin.pages.newsletter.homepage',
            ],
            [
                'name' => 'Lists',
                'icon' => 'heroicon-o-queue-list',
                'route' => '',
            ],
            [
                'name' => 'Subscribers',
                'icon' => 'heroicon-o-bell-alert',
                'route' => '',
            ],
            [
                'name' => 'Templates',
                'icon' => 'heroicon-o-paint-brush',
                'route' => '',
            ],
            [
                'name' => 'Sender Accounts',
                'icon' => 'heroicon-o-paper-airplane',
                'route' => 'filament.admin.pages.newsletter.sender-accounts',
            ],
            [
                'name' => 'Settings',
                'icon' => 'heroicon-o-cog',
                'route' => '',
            ],
        ];

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
                'icon' => 'heroicon-o-mail-opened',
            ],
        ];

        return [
            'tabLinks' => $tabLinks,
            'dashboardStats' => $dashboardStats,
        ];
    }
}
