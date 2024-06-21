<?php

namespace MicroweberPackages\Admin\Providers\Filament;

use App\Filament\Admin\Resources\ContentResource;
use App\Filament\Admin\Resources\PostResource;
use App\Filament\Admin\Resources\ProductResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\MinimalTheme;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use MicroweberPackages\Admin\Http\Middleware\Admin;
use MicroweberPackages\Filament\MicroweberTheme;
use MicroweberPackages\Filament\Plugins\FilamentTranslatableFieldsPlugin;
use MicroweberPackages\Marketplace\Filament\MarketplaceFilamentPlugin;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Logo\Http\Livewire\LogoModuleSettings;
use MicroweberPackages\Multilanguage\Models\MultilanguageSupportedLocales;
use MicroweberPackages\Multilanguage\MultilanguageFilamentPlugin;
use MicroweberPackages\User\Filament\UsersFilamentPlugin;
use SolutionForest\FilamentTranslateField\FilamentTranslateFieldPlugin;

class FilamentAdminPanelProvider extends PanelProvider
{

    public string $filamentId = 'admin';
    public string $filamentPath = 'admin';

    public function __construct($app)
    {
        parent::__construct($app);
        $this->filamentPath = mw_admin_prefix_url();
    }

    public function getPanelPages(): array
    {
        return ModuleAdmin::getPanelPages();
    }

    public function getPanelResources(): array
    {
        return ModuleAdmin::getPanelResources();
    }

    public function getPanelMiddlewares(): array
    {
        return [
            //  EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,

        ];
    }

    public function panel(Panel $panel): Panel
    {
        $panel

            // ->viteTheme('resources/css/microweber-admin-filament.scss', 'public/build')
            ->viteTheme('resources/css/filament/admin/theme.css', 'public/build')
            ->id($this->filamentId)
            ->path($this->filamentPath)
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->databaseNotifications()
            ->default()
            ->login()
            ->registration()
            ->font('Inter')
            ->brandLogoHeight('34px')
            ->brandLogo(function () {
                return site_url('userfiles/modules/microweber/api/libs/mw-ui/assets/img/logo.svg');
            })
            ->unsavedChangesAlerts()
            ->navigationGroups([
                'Dashboard' => NavigationGroup::make()
                    ->label('')
                    ->collapsible(false),
                'Website' => NavigationGroup::make()
                    ->icon('mw-website')
                    ->label('Website'),
                'Shop' => NavigationGroup::make()
                    ->icon('mw-shop')
                    ->label('Shop'),
                'Other' => NavigationGroup::make()
                    ->label('Other')
                    ->collapsible(false),
            ])
            ->sidebarWidth('15rem')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(
                in: app_path('Filament/Admin/Resources'),
                for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages($this->getPanelPages())
            ->discoverWidgets(
                in: app_path('Filament/Admin/Widgets'),
                for: 'App\\Filament\\Admin\\Widgets'
            )
            ->resources($this->getPanelResources())
            ->widgets([
                // Widgets\AccountWidget::class,
                //  Widgets\FilamentInfoWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware($this->getPanelMiddlewares())
            ->authGuard('web')
            ->authMiddleware([
                //  Authenticate::class,
                \MicroweberPackages\Filament\Http\Middleware\Authenticate::class,
                //  Admin::class,
            ]);

        $panel->renderHook(
            name: PanelsRenderHook::TOPBAR_START,
            hook: fn(): string => Blade::render('@livewire(\'admin-top-navigation-actions\')')
        );


        $panel->renderHook(
            name: \Filament\Tables\View\TablesRenderHook::TOOLBAR_SEARCH_BEFORE,
            hook: fn(): string => view('content::admin.content.filament.render-category-tree'),
            scopes: [
                ContentResource\Pages\ListContents::class,
                PostResource\Pages\ListPosts::class,
                ProductResource\Pages\ListProducts::class
            ]
        );


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
        $panel->plugin(new MultilanguageFilamentPlugin());


        return $panel;
    }
}
