<?php

namespace Modules\Newsletter\Providers;

use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;
use Modules\Newsletter\Filament\Admin\Pages\CreateCampaign;
use Modules\Newsletter\Filament\Admin\Pages\EditCampaign;
use Modules\Newsletter\Filament\Admin\Pages\Homepage;
use Modules\Newsletter\Filament\Admin\Pages\ProcessCampaign;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource;
use Modules\Newsletter\Filament\Admin\Resources\ListResource;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource;
use Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;
use Modules\Newsletter\Filament\Admin\Resources\TemplatesResource;
use Modules\Newsletter\Filament\Widgets\CampaignsChart;
use Modules\Newsletter\Filament\Widgets\MailsOverviewWidget;
use Modules\Newsletter\Filament\Widgets\StatsOverviewWidget;
use Modules\Newsletter\Filament\Widgets\SubscribersChart;
use MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider;

class NewsletterFilamentAdminPanelProvider extends FilamentAdminPanelProvider
{
    public string $filamentId = 'admin-newsletter';
    public string $filamentPath = 'admin/newsletter';

    public function panel(Panel $panel): Panel
    {


        $panel
            ->id('admin-newsletter')
            ->path('admin/newsletter')
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->font('Inter')
            ->brandLogoHeight('34px')
            ->brandLogo(fn () => mw()->ui->admin_logo())
            ->unsavedChangesAlerts()
            ->sidebarWidth('15rem')
            ->databaseNotifications(true)
            ->colors([
                'primary' => Color::Blue,
            ]) ->pages([
                Homepage::class,
                CreateCampaign::class,
                EditCampaign::class,
                ProcessCampaign::class
            ])
            ->resources([
                SenderAccountsResource::class,
                SubscribersResource::class,
                TemplatesResource::class,
                CampaignResource::class,
                ListResource::class
            ])
            ->widgets([
                StatsOverviewWidget::class,
                MailsOverviewWidget::class,
                CampaignsChart::class,
                SubscribersChart::class,
            ])
            ->middleware($this->getPanelMiddlewares())
            ->authGuard('web')
            ->authMiddleware([
                //  Authenticate::class,
                \MicroweberPackages\Filament\Http\Middleware\AuthenticateAdmin::class,
                //  Admin::class,
            ]);

        $panel->renderHook(
            name: PanelsRenderHook::SIDEBAR_NAV_START,
            hook: fn () => view('microweber-module-newsletter::livewire.filament.admin.sidebar.create-new-campaign-btn')
        );

        $panel->plugin(new MicroweberFilamentTheme());

      //  MicroweberFilamentTheme::configure();


        return $panel;
    }
}
