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

        // task-2026-05-27-902c15 / AI-1179: breadcrumb trail "Admin > Newsletter"
        $panel->renderHook(
            name: PanelsRenderHook::TOPBAR_START,
            // task-2026-05-30-nlbread — the inner "Back to Admin" anchor must
            // carry its own 44x44 touch surface; the nav wrapper's min-height
            // sized the visual band but the anchor itself rendered 40x38 at
            // 390x844, failing WCAG 2.5.5 / iOS HIG.
            hook: fn(): string => '<nav aria-label="Breadcrumb" style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;font-size:14px;font-weight:500;color:var(--gray-700,#374151);border-right:1px solid rgba(0,0,0,0.1);margin-right:10px;min-height:44px;"><a href="' . e(admin_url()) . '" style="color:var(--gray-500,#6b7280);text-decoration:none;display:inline-flex;align-items:center;min-height:44px;min-width:44px;padding:0 8px;" title="Back to Admin"><svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\' style=\'width:18px;height:18px;vertical-align:middle;margin-right:4px;\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18\' /></svg>Admin</a><span style="color:var(--gray-400,#9ca3af);margin:0 2px;">/</span><span style="color:var(--gray-700,#374151);">Newsletter</span></nav>'
        );

        $panel->plugin(new MicroweberFilamentTheme());


        //  MicroweberFilamentTheme::configure();


        return $panel;
    }
}
