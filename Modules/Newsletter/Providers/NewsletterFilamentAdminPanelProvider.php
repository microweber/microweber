<?php

namespace Modules\Newsletter\Providers;

use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;
use Modules\Newsletter\Filament\Admin\Pages\CreateCampaign;
use Modules\Newsletter\Filament\Admin\Pages\EditCampaign;
use Modules\Newsletter\Filament\Admin\Pages\Homepage;
use Modules\Newsletter\Filament\Admin\Pages\ProcessCampaign;
use Modules\Newsletter\Filament\Admin\Pages\TemplateEditor;
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

    public function panel(Panel $panel): Panel
    {


        $panel
            ->id('admin-newsletter')
            ->path(mw_admin_prefix_url() . '/newsletter')
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->font('Inter')
            ->unsavedChangesAlerts()
            ->sidebarWidth('15rem')
            ->databaseNotifications(true)

            ->brandLogoHeight('34px')

            ->brandLogo(function () {
                return mw()->ui->admin_logo();
            })
            ->brandName(function () {
                return mw()->ui->brand_name();
            })

            ->colors([
                'primary' => Color::Blue,
            ])->pages([
                Homepage::class,
                CreateCampaign::class,
                EditCampaign::class,
                ProcessCampaign::class,
                TemplateEditor::class,
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
            ->navigationItems([
                NavigationItem::make('E-mail Marketing')
                    ->url(admin_url('newsletter'))
                    ->group('Shop Settings')
                    ->sort(2)
                    ->icon('heroicon-o-megaphone'),
                NavigationItem::make('Back to admin')
                    ->url(admin_url())
                    ->group('System Settings')
                    ->sort(20000)
                    ->icon('heroicon-o-arrow-right-end-on-rectangle'),

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
            hook: fn() => view('microweber-module-newsletter::livewire.filament.admin.sidebar.create-new-campaign-btn')
        );

        $panel->plugin(new MicroweberFilamentTheme());


        //  MicroweberFilamentTheme::configure();


        return $panel;
    }
}
