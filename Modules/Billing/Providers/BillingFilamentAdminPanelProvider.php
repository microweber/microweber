<?php

namespace Modules\Billing\Providers;

use Filament\Panel;
use Filament\Support\Colors\Color;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;
use MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider;
use Modules\Billing\Filament\Pages\Settings;
use Modules\Billing\Filament\Resources\BillingUserResource;
use Modules\Billing\Filament\Resources\SubscriptionPlanGroupsResource;
use Modules\Billing\Filament\Resources\SubscriptionPlanResource;

class BillingFilamentAdminPanelProvider extends FilamentAdminPanelProvider
{
    public string $filamentId = 'admin-billing';
    public string $filamentPath = 'admin/billing';

    public function panel(Panel $panel): Panel
    {
        $panel
            ->id('admin-billing')
            ->path('admin/billing')
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->font('Inter')
            ->brandLogoHeight('34px')
            ->brandLogo(fn () => mw()->ui->admin_logo())
            ->brandName(fn () => mw()->ui->brand_name() . ' Billing')
            ->unsavedChangesAlerts()
            ->sidebarWidth('15rem')
            ->databaseNotifications(true)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(
                in: __DIR__ . '/../Filament/Resources',
                for: 'Modules\\Billing\\Filament\\Resources'
            )
            ->discoverPages(
                in: __DIR__ . '/../Filament/Pages',
                for: 'Modules\\Billing\\Filament\\Pages'
            )
            ->discoverWidgets(
                in: __DIR__ . '/../Filament/Widgets',
                for: 'Modules\\Billing\\Filament\\Widgets'
            )
            ->middleware($this->getPanelMiddlewares())
            ->authGuard('web')
            ->authMiddleware([

                //  Authenticate::class,
                \MicroweberPackages\Filament\Http\Middleware\AuthenticateAdmin::class,
                //  Admin::class,

            ]);

        $panel->plugin(new MicroweberFilamentTheme());

      //  MicroweberFilamentTheme::configure();

        return $panel;
    }
}
