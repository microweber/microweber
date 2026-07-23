<?php

namespace Modules\Billing\Providers;

use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;

class BillingFilamentAdminPanelProvider extends FilamentAdminPanelProvider
{
    public string $filamentId = 'admin-billing';

    public function panel(Panel $panel): Panel
    {
        $panel = $this->getBasePanel($panel);

        $panel
            ->path(mw_admin_prefix_url().'/billing')
            ->default(false)
            ->login(false)
            ->globalSearch(\MicroweberPackages\FilamentRegistry\GlobalSearch\MicroweberGlobalSearchProvider::class)
            ->globalSearchDebounce('300ms')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->discoverResources(
                in: __DIR__ . '/../Filament/Admin/Resources',
                for: 'Modules\\Billing\\Filament\\Admin\\Resources'
            )
            ->discoverPages(
                in: __DIR__ . '/../Filament/Admin/Pages',
                for: 'Modules\\Billing\\Filament\\Admin\\Pages'
            )
            ->discoverWidgets(
                in: __DIR__ . '/../Filament/Admin/Widgets',
                for: 'Modules\\Billing\\Filament\\Admin\\Widgets'
            )
            ->navigationItems([
                NavigationItem::make('Billing')
                    ->url(admin_url('billing'))
                    ->icon('heroicon-o-currency-dollar')
                    ->group('Shop Settings')
                    ->sort(300),
                NavigationItem::make('Back to admin')
                    ->url(admin_url())
                    ->group('System Settings')
                    ->sort(20000)
                    ->icon('heroicon-o-arrow-right-end-on-rectangle'),
            ])
            ->middleware($this->getPanelMiddlewares())
            ->authGuard('web')
            ->authMiddleware([
                \MicroweberPackages\Filament\Http\Middleware\AuthenticateAdmin::class,
            ]);

        $panel->renderHook(
            name: PanelsRenderHook::TOPBAR_START,
            hook: fn(): string => Blade::render('@livewire(\'admin-top-navigation-actions\')')
        );

        $panel->renderHook(
            name: PanelsRenderHook::GLOBAL_SEARCH_AFTER,
            hook: fn(): string => view('admin::livewire.filament.top-navigation-go-live-edit') . view('admin::livewire.filament.search-quick-nav')
        );

        $panel->plugin(new MicroweberFilamentTheme());

        return $panel;
    }
}
