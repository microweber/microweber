<?php

namespace Modules\Newsletter\Filament\Admin\Pages;

use Filament\Actions;
use Filament\Pages\Dashboard as BaseDashboard;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriber;

class Homepage extends BaseDashboard
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';

//    protected static string | \UnitEnum | null $navigationGroup = 'Email Marketing';

//    protected static ?string $slug = 'newsletter/homepage';

   // protected string $view = 'microweber-module-newsletter::livewire.filament.admin.homepage-new';

//    protected static bool $shouldRegisterNavigation = false;

    public function getBreadcrumbs(): array
    {
        // task-2026-05-30-nlbread — panel id is `admin-newsletter`, so the
        // Filament-generated route name is `filament.admin-newsletter.pages.homepage`.
        // The old `filament.admin-newsletter.pages.homepage` threw
        // RouteNotFoundException and 500'd /admin/newsletter.
        return [
            route('filament.admin-newsletter.pages.homepage') => 'Newsletter',
            'Dashboard',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create_campaign')
                ->label('Create Campaign')
                ->icon('heroicon-o-rocket-launch')
                ->url(route('filament.admin-newsletter.pages.create-campaign')),
            Actions\Action::make('import_subscribers')
                ->label('Import Subscribers')
                ->icon('heroicon-o-cloud-arrow-up')
                ->url(route('filament.admin-newsletter.resources.subscribers.index'))
                ->color('gray'),
            Actions\Action::make('new_template')
                ->label('New Template')
                ->icon('heroicon-o-paint-brush')
                ->url(route('filament.admin-newsletter.resources.templates.index'))
                ->color('gray'),
        ];
    }

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
