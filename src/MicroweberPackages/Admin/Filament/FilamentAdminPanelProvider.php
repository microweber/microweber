<?php

namespace MicroweberPackages\Admin\Filament;

use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Blade;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Marketplace\Filament\MarketplaceFilamentPlugin;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;
use MicroweberPackages\Multilanguage\MultilanguageFilamentPlugin;
use MicroweberPackages\User\Filament\UsersFilamentPlugin;
use Modules\Product\Filament\Admin\Resources\ProductResource;


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

        return FilamentRegistry::getPages(self::class, $this->filamentId);
    }

    public function getPanelResources(): array
    {
        return FilamentRegistry::getResources(self::class, $this->filamentId);
    }

    public function getPanelMiddlewares(): array
    {
        return [
           //   EncryptCookies::class,
         //   AddQueuedCookiesToResponse::class,
            //StartSession::class,
           // AuthenticateSession::class,
          //  ShareErrorsFromSession::class,
            //          VerifyCsrfToken::class, aways givev error to refresh
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,

        ];
    }

    public function getBasePanel(Panel $panel): Panel
    {
        $panel
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
                return asset('vendor/microweber-packages/frontend-assets-libs/img/logo.svg');
            })
            ->sidebarWidth('15rem')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->unsavedChangesAlerts();

        return $panel;
    }

    public function panel(Panel $panel): Panel
    {
        $panel = $this->getBasePanel($panel);

        $isIframe = false;

        if (request()->get('iframe') or request()->header('Sec-Fetch-Dest') === 'iframe') {
            $isIframe = true;
        }
        if ($isIframe) {
            $panel->navigation(false);
            $panel->topbar(false);
          //  $panel->spa();

        }

        $panel
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
            ->navigationItems([
                NavigationItem::make('E-mail Marketing')
                    ->url(admin_url('newsletter'))
                    ->group('Other')
                    ->sort(2)
                    ->icon('heroicon-o-megaphone'),
            ])


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
            ])->bootUsing(function (Panel $panel) {
                //  dd($panel);
            });

        $panel->renderHook(
            name: PanelsRenderHook::TOPBAR_START,
            hook: fn(): string => Blade::render('@livewire(\'admin-top-navigation-actions\')')
        );


        $panel->renderHook(
            name: \Filament\Tables\View\TablesRenderHook::TOOLBAR_SEARCH_BEFORE,
            hook: fn(): string => view('modules.content::filament.admin.list-records-render-category-tree'),
            scopes: [
                \Modules\Content\Filament\Admin\ContentResource\Pages\ListContents::class,
                \Modules\Post\Filament\Admin\Resources\PostResource\Pages\ListPosts::class,
                ProductResource\Pages\ListProducts::class
            ]
        );


        $registeredPlugins = FilamentRegistry::getPlugins(self::class);


        $tableToggle = new TableLayoutTogglePlugin();
        $tableToggle->defaultLayout('grid');
        $tableToggle->persistLayoutInLocalStorage(true);
        $tableToggle->shareLayoutBetweenPages(false);
        $tableToggle->displayToggleAction();
        $tableToggle->toggleActionHook('tables::toolbar.search.after');
        $tableToggle->listLayoutButtonIcon('heroicon-o-list-bullet');
        $tableToggle->gridLayoutButtonIcon('heroicon-o-squares-2x2');
        $panel->plugin($tableToggle);

        $panel->plugin(new MicroweberFilamentTheme());
        $panel->plugin(new UsersFilamentPlugin());
        $panel->plugin(new MarketplaceFilamentPlugin());
        $panel->plugin(new MultilanguageFilamentPlugin());

        if ($registeredPlugins) {
            foreach ($registeredPlugins as $registeredPlugin) {
                $plugin = new $registeredPlugin;

                $panel->plugin($plugin);
            }
        }


        $panel->resources($this->getPanelResources())
            ->pages($this->getPanelPages())
            ->discoverResources(
                in: app_path('Filament/Admin/Resources'),
                for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverPages(in: app_path('MicroweberPackages/Menu/Filament/Admin/Pages'), for: 'MicroweberPackages\\Menu\\Filament\\Admin\\Pages')

            ->discoverWidgets(
                in: app_path('Filament/Admin/Widgets'),
                for: 'App\\Filament\\Admin\\Widgets'
            );

        //  MicroweberFilamentTheme::configure();

        return $panel;
    }
}
