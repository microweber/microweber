<?php

namespace MicroweberPackages\Admin\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use MicroweberPackages\Admin\Http\Middleware\Admin;

class FilamentAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
             // ->viteTheme('resources/css/microweber-admin-filament.scss', 'public/build')
            ->viteTheme('resources/css/filament/admin/theme.css', 'public/build')
            ->id('admin')
            ->path('aaaaaaaa')
            ->default()
              ->login()
             ->registration()
            ->brandLogoHeight('35px')
            ->brandLogo(function () {
                return site_url('userfiles/modules/microweber/api/libs/mw-ui/assets/img/logo.svg');
            })
            ->sidebarWidth(20)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(
                in: app_path('Filament/Admin/Resources'),
                for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                //  Pages\Dashboard::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/Admin/Widgets'),
                for: 'App\\Filament\\Admin\\Widgets'
            )
            ->widgets([
                // Widgets\AccountWidget::class,
                //  Widgets\FilamentInfoWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                //  EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authGuard('web')
            ->authMiddleware([
                //  Authenticate::class,
                \MicroweberPackages\Filament\Http\Middleware\Authenticate::class,
                //  Admin::class,
            ]);
    }
}
