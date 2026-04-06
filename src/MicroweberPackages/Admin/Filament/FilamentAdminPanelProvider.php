<?php

namespace MicroweberPackages\Admin\Filament;

use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Blade;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Filament\Plugins\MicroweberFilamentSocialitePlugin;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\AdminLiveEditPage;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;
use MicroweberPackages\Multilanguage\MultilanguageFilamentPlugin;
use MicroweberPackages\User\Filament\UsersFilamentPlugin;
use Modules\Product\Filament\Admin\Resources\ProductResource;
use Filament\Http\Middleware\Authenticate;

use Filament\Pages;

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Modules\Checkout\Filament\Resources\CheckoutResource;
use Modules\Checkout\Filament\Resources\Pages\CheckoutPage;


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

    public function getPanelClusters(): array
    {
        return FilamentRegistry::getClusters(self::class, $this->filamentId);
    }

    public function getPanelMiddlewares(): array
    {
        return [
            'web',


            VerifyCsrfToken::class,
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
            /*  ->spa()
              ->spaUrlExceptions(fn (): array => [
                   AdminLiveEditPage::getUrl(),
              ])*/
            ->login()
            // ->registration()
            ->font('Inter')
            // ->sidebarCollapsibleOnDesktop()
            ->brandLogoHeight('34px')
            ->brandLogo(function () {
                $logo = mw()->ui->admin_logo();
                if (empty($logo)) {
                    $logo = mw()->ui->admin_logo_login();
                }
                return $logo;
            })
            ->brandName(function () {
                return mw()->ui->brand_name();
            })
            ->sidebarWidth('16rem')
            ->colors([
                'primary' => MwColors::Blue,
                'danger' => Color::Rose,
                'gray' => Color::Neutral,
                'info' => Color::Sky,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
            ])
            ->maxContentWidth(Width::ScreenTwoExtraLarge)
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
                    ->icon('heroicon-o-globe-alt')
                    ->label('Website')
                    ->collapsible(true)
                    ->collapsed(true),

                'Shop' => NavigationGroup::make()
                    ->icon('heroicon-o-shopping-bag')
                    ->label('Shop')
                    ->collapsible(true)
                    ->collapsed(true),

                'Marketplace' => NavigationGroup::make()
                    ->label('Marketplace')
                    ->collapsible(false)
                    ->extraSidebarAttributes([
                        'class' => 'mw-nav-group-no-label',
                    ]),

                'Modules' => NavigationGroup::make()
                    ->label('Modules')
                    ->collapsible(false)
                    ->extraSidebarAttributes([
                        'class' => 'mw-nav-group-no-label',
                    ]),

                'Settings' => NavigationGroup::make()
                    ->icon('heroicon-o-cog-6-tooth')
                    ->label('Settings')
                    ->collapsible(true)
                    ->collapsed(true),

                'Users' => NavigationGroup::make()
                    ->icon('heroicon-o-users')
                    ->label('Users')
                    ->collapsible(true)
                    ->collapsed(true),

                'Website Settings' => NavigationGroup::make()
                    ->extraSidebarAttributes([
                        'class' => 'hidden bg-gray-50 dark:bg-gray-900',
                    ])
                    ->label('Website Settings')
                    ->collapsed(false)
                    ->collapsible(true),

                'Shop Settings' => NavigationGroup::make()
                    ->extraSidebarAttributes([
                        'class' => 'hidden bg-gray-50 dark:bg-gray-900',
                    ])
                    ->label('Shop Settings')
                    ->collapsed(false)
                    ->collapsible(true),

                'Email Settings' => NavigationGroup::make()
                    ->extraSidebarAttributes([
                        'class' => 'hidden bg-gray-50 dark:bg-gray-900',
                    ])
                    ->label('Email Settings')
                    ->collapsed(false)
                    ->collapsible(true),

                'Customization Settings' => NavigationGroup::make()
                    ->extraSidebarAttributes([
                        'class' => 'hidden bg-gray-50 dark:bg-gray-900',
                    ])
                    ->label('Customization Settings')
                    ->collapsed(false)
                    ->collapsible(true),

                'System Settings' => NavigationGroup::make()
                    ->extraSidebarAttributes([
                        'class' => 'hidden bg-gray-50 dark:bg-gray-900',
                    ])
                    ->label('System Settings')
                    ->collapsed(false)
                    ->collapsible(true),

                'Language Settings' => NavigationGroup::make()
                    ->extraSidebarAttributes([
                        'class' => 'hidden bg-gray-50 dark:bg-gray-900',
                    ])
                    ->label('Language Settings')
                    ->collapsed(false)
                    ->collapsible(true),
            ])
            ->navigationItems([
           /*     NavigationItem::make('E-mail Marketing')
                    ->url(admin_url('newsletter'))
                    ->group('Other')
                    ->sort(2)
                    ->icon('heroicon-o-megaphone'),*/


            ])
            ->widgets([
                // Widgets\AccountWidget::class,
                //  Widgets\FilamentInfoWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware($this->getPanelMiddlewares())
         //   ->authGuard('web')
            ->authMiddleware([
                // Authenticate::class,
               \MicroweberPackages\Filament\Http\Middleware\AuthenticateAdmin::class,
                //  Admin::class,
            ])->bootUsing(function (Panel $panel) {
                //  dd($panel);


            });

        $panel->renderHook(
            name: PanelsRenderHook::TOPBAR_START,
            hook: fn(): string => Blade::render('@livewire(\'admin-top-navigation-actions\')')
        );

        $panel->renderHook(
            name: PanelsRenderHook::GLOBAL_SEARCH_AFTER,
            hook: fn(): string => view('admin::livewire.filament.top-navigation-go-live-edit')
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
        // $tableToggle->persistLayoutInLocalStorage(true);
        $tableToggle->shareLayoutBetweenPages(false);
        $tableToggle->displayToggleAction();
        $tableToggle->toggleActionHook('tables::toolbar.search.after');
        $tableToggle->listLayoutButtonIcon('heroicon-o-list-bullet');
        $tableToggle->gridLayoutButtonIcon('heroicon-o-squares-2x2');
        $panel->plugin($tableToggle);

        $panel->plugin(new MicroweberFilamentTheme());
        $panel->plugin(new UsersFilamentPlugin());
        //    $panel->plugin(new MarketplaceFilamentPlugin());
        $panel->plugin(new MultilanguageFilamentPlugin());
         $panel->plugin(MicroweberFilamentSocialitePlugin::make()->admin()->configure());

        if ($registeredPlugins) {
            foreach ($registeredPlugins as $registeredPlugin) {
                $plugin = new $registeredPlugin;

                $panel->plugin($plugin);
            }
        }

        $panel->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverPages(in: app_path('MicroweberPackages/Menu/Filament/Admin/Pages'), for: 'MicroweberPackages\\Menu\\Filament\\Admin\\Pages')
            ->discoverWidgets(
                in: app_path('Filament/Admin/Widgets'),
                for: 'App\\Filament\\Admin\\Widgets'
            )
            ->discoverResources(
                in: app_path('Filament/Admin/Resources'),
                for: 'App\\Filament\\Admin\\Resources');


        $panel->resources($this->getPanelResources())
            ->pages($this->getPanelPages());;

        $panel->livewireComponents($this->getPanelClusters());


        //  MicroweberFilamentTheme::configure();

        return $panel;
    }
}
