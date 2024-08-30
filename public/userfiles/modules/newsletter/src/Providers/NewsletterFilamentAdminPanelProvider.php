<?php

namespace MicroweberPackages\Modules\Newsletter\Providers;

use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Providers\Filament\FilamentAdminPanelProvider;
use MicroweberPackages\Filament\MicroweberTheme;
use MicroweberPackages\Marketplace\Filament\MarketplaceFilamentPlugin;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages\CreateCampaign;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages\EditCampaign;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages\Homepage;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages\ProcessCampaign;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\CampaignResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\ListResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\TemplatesResource;
use MicroweberPackages\Modules\Newsletter\Filament\Widgets\CampaignsChart;
use MicroweberPackages\Modules\Newsletter\Filament\Widgets\MailsOverviewWidget;
use MicroweberPackages\Modules\Newsletter\Filament\Widgets\StatsOverviewWidget;
use MicroweberPackages\Modules\Newsletter\Filament\Widgets\SubscribersChart;
use MicroweberPackages\Multilanguage\MultilanguageFilamentPlugin;
use MicroweberPackages\User\Filament\UsersFilamentPlugin;

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
            ->brandLogo(fn () => view('microweber-module-newsletter::livewire.filament.admin.logo'))
            ->unsavedChangesAlerts()
            ->sidebarWidth('15rem')
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
                \MicroweberPackages\Filament\Http\Middleware\Authenticate::class,
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
