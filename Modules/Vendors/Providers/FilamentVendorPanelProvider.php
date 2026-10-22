<?php

namespace Modules\Vendors\Providers;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
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
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): string => view('modules.vendors::filament.panel-styles')->render()
            )
            ->renderHook(
                PanelsRenderHook::TOPBAR_START,
                fn (): string => '<nav aria-label="Breadcrumb" style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;font-size:14px;font-weight:500;min-height:44px;">'
                    . '<a href="' . e(url('/')) . '" style="color:#6b7280;text-decoration:none;display:inline-flex;align-items:center;min-height:44px;min-width:44px;padding:0 8px;" title="' . e(__('Back to site')) . '">← ' . e(__('Back to site')) . '</a>'
                    . '</nav>'
            )
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
