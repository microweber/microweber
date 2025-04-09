<?php

namespace Modules\Billing\Providers;

use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\Support\Colors\Color;
use MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;

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
            ->brandName('Billing')
            ->unsavedChangesAlerts()
            ->sidebarWidth('15rem')
            ->databaseNotifications(true)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(
                in: __DIR__ . '/../Filament/Resources/Admin',
                for: 'Modules\\Billing\\Filament\\Resources\\Admin'
            )
            ->discoverPages(
                in: __DIR__ . '/../Filament/Pages/Admin',
                for: 'Modules\\Billing\\Filament\\Pages\\Admin'
            )
            ->discoverWidgets(
                in: __DIR__ . '/../Filament/Widgets/Admin',
                for: 'Modules\\Billing\\Filament\\Widgets\\Admin'
            )
            ->navigationItems([
                NavigationItem::make('Back to admin')
                    ->url(admin_url())
                    ->group('Other')
                    ->sort(20000)
                    ->icon('mw-login'),

            ])
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
