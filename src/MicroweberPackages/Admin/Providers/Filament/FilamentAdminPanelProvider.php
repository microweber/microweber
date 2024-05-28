<?php

namespace MicroweberPackages\Admin\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\MinimalTheme;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use MicroweberPackages\Admin\Http\Middleware\Admin;
use MicroweberPackages\Filament\MicroweberTheme;
use MicroweberPackages\Marketplace\Filament\MarketplaceFilamentPlugin;
use MicroweberPackages\Multilanguage\Models\MultilanguageSupportedLocales;
use MicroweberPackages\User\Filament\UsersFilamentPlugin;
use SolutionForest\FilamentTranslateField\FilamentTranslateFieldPlugin;

class FilamentAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel

             // ->viteTheme('resources/css/microweber-admin-filament.scss', 'public/build')
            ->viteTheme('resources/css/filament/admin/theme.css', 'public/build')
            ->id('admin')
            ->path('aaaaaaaa')
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->databaseNotifications()
            ->default()
            ->login()
            ->registration()
            ->brandLogoHeight('34px')
            ->brandLogo(function () {
                return site_url('userfiles/modules/microweber/api/libs/mw-ui/assets/img/logo.svg');
            })
            ->sidebarWidth('15rem')
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

        $tableToggle = new TableLayoutTogglePlugin();
        $tableToggle->defaultLayout('grid');
        $tableToggle->persistLayoutInLocalStorage(true);
        $tableToggle->shareLayoutBetweenPages(false);
        $tableToggle->displayToggleAction();
        $tableToggle->toggleActionHook('tables::toolbar.search.after');
        $tableToggle->listLayoutButtonIcon('heroicon-o-list-bullet');
        $tableToggle->gridLayoutButtonIcon('heroicon-o-squares-2x2');
        $panel->plugin($tableToggle);

        $panel->plugin(new MicroweberTheme());
        $panel->plugin(new UsersFilamentPlugin());
        $panel->plugin(new MarketplaceFilamentPlugin());

        // TODO
        $defaultLocales = [];
        $getSupportedLocales = DB::table('multilanguage_supported_locales')
            ->where('is_active', 'y')->get();
        if ($getSupportedLocales->count() > 0) {
            foreach ($getSupportedLocales as $locale) {
                $defaultLocales[] = $locale->locale;
            }
        }

        $panel->plugin(SpatieLaravelTranslatablePlugin::make()->defaultLocales($defaultLocales));
        $panel->plugin(FilamentTranslateFieldPlugin::make()->defaultLocales($defaultLocales));

        return $panel;
    }
}
