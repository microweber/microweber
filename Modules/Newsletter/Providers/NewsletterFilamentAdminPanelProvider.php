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
use Modules\Newsletter\Filament\Admin\Pages\TemplateEditor;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource;
use Modules\Newsletter\Filament\Admin\Resources\ListResource;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource;
use Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;
use Modules\Newsletter\Filament\Admin\Resources\TemplatesResource;
use Modules\Newsletter\Filament\Admin\Resources\WorkflowResource;
use Modules\Newsletter\Filament\Widgets\CampaignsChart;
use Modules\Newsletter\Filament\Widgets\MailsOverviewWidget;
use Modules\Newsletter\Filament\Widgets\StatsOverviewWidget;
use Modules\Newsletter\Filament\Widgets\RecentCampaignsWidget;
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
                ListResource::class,
                WorkflowResource::class,
            ])
            ->widgets([
                StatsOverviewWidget::class,
                MailsOverviewWidget::class,
                CampaignsChart::class,
                SubscribersChart::class,
                RecentCampaignsWidget::class,
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

        $panel->renderHook(
            name: PanelsRenderHook::SIDEBAR_NAV_END,
            hook: fn() => view('microweber-module-newsletter::livewire.filament.admin.sidebar.back-to-admin')
        );

        // task-2026-05-23-b59ecf / AI-1056 — duplicate the Back to Admin link at TOPBAR_START
        // task-2026-05-27-6e9cf4 / AI-1173: enlarged breadcrumb for orientation
        $panel->renderHook(
            name: PanelsRenderHook::TOPBAR_START,
            hook: fn(): string => '<a href="' . e(admin_url()) . '" style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;font-size:14px;font-weight:500;color:var(--gray-700,#374151);text-decoration:none;border-right:1px solid rgba(0,0,0,0.1);margin-right:10px;min-height:44px;" title="Back to Admin Settings"><svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\' style=\'width:18px;height:18px;\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18\' /></svg><span>Admin Settings</span></a>'
        );

        $panel->plugin(new MicroweberFilamentTheme());


        //  MicroweberFilamentTheme::configure();


        return $panel;
    }
}
