<?php

namespace Modules\Vendors\Providers;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Routing\Middleware\SubstituteBindings;
use MicroweberPackages\Admin\Filament\MwColors;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;
use Modules\Vendors\Filament\Pages\MyVendorOrders;
use Modules\Vendors\Filament\Pages\MyVendorProducts;
use Modules\Vendors\Filament\Pages\MyVendorProfile;
use Modules\Vendors\Filament\Widgets\VendorSalesChart;
use Modules\Vendors\Filament\Widgets\VendorStatsOverview;

/**
 * The Vendor panel — a dedicated Filament panel (like the Newsletter panel)
 * that houses ALL vendor-facing functionality in one place: a stats
 * dashboard, the storefront editor, product management and orders.
 *
 * Lives at /vendor-panel (distinct from the public /vendor/{slug} shop and
 * /vendors directory). Access is gated to users holding the `vendor` role via
 * User::canAccessPanel('vendor').
 */
class FilamentVendorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('vendor')
            ->path('vendor-panel')
            ->login()
            ->colors([
                'primary' => MwColors::Blue,
            ])
            ->brandName(fn () => __('Vendor'))
            ->font('Inter')
            ->sidebarWidth('15rem')
            ->databaseNotifications(true)
            ->pages([
                Dashboard::class,
                MyVendorProfile::class,
                MyVendorProducts::class,
                MyVendorOrders::class,
            ])
            ->widgets([
                VendorStatsOverview::class,
                VendorSalesChart::class,
            ])
            // "Back to site" as the last sidebar nav item (sorted to the bottom).
            // Label is a CLOSURE — __() eagerly in make() crashes at boot (the
            // translation loader isn't ready during panel registration).
            ->navigationItems([
                NavigationItem::make('back-to-site')
                    ->label(fn (): string => __('Back to site'))
                    ->url(fn (): string => url('/'))
                    ->icon('heroicon-o-arrow-left')
                    ->group('Vendor')
                    ->sort(99),
            ])
            ->middleware([
                'web',
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authGuard('web')
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugin(new MicroweberFilamentTheme());
    }
}
