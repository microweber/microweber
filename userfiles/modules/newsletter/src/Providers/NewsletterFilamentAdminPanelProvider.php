<?php

namespace MicroweberPackages\Modules\Newsletter\Providers;

use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Illuminate\Support\Facades\Blade;
use MicroweberPackages\Admin\Providers\Filament\FilamentAdminPanelProvider;
use MicroweberPackages\Filament\MicroweberTheme;
use MicroweberPackages\Marketplace\Filament\MarketplaceFilamentPlugin;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages\Homepage;
use MicroweberPackages\Multilanguage\MultilanguageFilamentPlugin;
use MicroweberPackages\User\Filament\UsersFilamentPlugin;

class NewsletterFilamentAdminPanelProvider extends FilamentAdminPanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            // ->viteTheme('resources/css/microweber-admin-filament.scss', 'public/build')
            ->viteTheme('resources/css/filament/admin/theme.css', 'public/build')
            ->id('admin/newsletter')
            ->path('admin/newsletter')
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->databaseNotifications()
//            ->default()
//            ->login()
//            ->registration()
            ->font('Inter')
            ->brandLogoHeight('34px')
            ->brandLogo(function () {
                return site_url('userfiles/modules/microweber/api/libs/mw-ui/assets/img/logo.svg');
            })
            ->unsavedChangesAlerts()
            ->sidebarWidth('15rem')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->pages([
                Homepage::class
            ])
            ->widgets([
                // Widgets\AccountWidget::class,
                //  Widgets\FilamentInfoWidget::class,
//                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware($this->getPanelMiddlewares())
            ->authGuard('web')
            ->authMiddleware([
                //  Authenticate::class,
                \MicroweberPackages\Filament\Http\Middleware\Authenticate::class,
                //  Admin::class,
            ]);


        return $panel;
    }
}
