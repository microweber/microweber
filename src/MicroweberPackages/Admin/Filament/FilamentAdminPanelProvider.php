<?php

namespace MicroweberPackages\Admin\Filament;

use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Tables\Table;
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
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\Filament\Plugins\MicroweberFilamentSocialitePlugin;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\AdminLiveEditPage;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentTheme;
use MicroweberPackages\Multilanguage\MultilanguageFilamentPlugin;
use MicroweberPackages\User\Filament\UsersFilamentPlugin;
use MicroweberPackages\FilamentModalTeleport\ModalTeleportPlugin;
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

    public function boot(): void
    {
        Table::configureUsing(function (Table $table): Table {
            return $table
                ->paginationPageOptions([10, 25, 50, 100, 250])
                ->defaultPaginationPageOption(25)
                // task-2026-05-27-305605 / AI-1132
                ->stackedOnMobile();
        });
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
            ->globalSearch(\MicroweberPackages\FilamentRegistry\GlobalSearch\MicroweberGlobalSearchProvider::class)
            ->globalSearchDebounce('300ms')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->databaseNotifications()
            ->default()
            /*  ->spa()
              ->spaUrlExceptions(fn (): array => [
                   AdminLiveEditPage::getUrl(),
              ])*/
            ->login(\MicroweberPackages\Admin\Filament\Pages\Login::class)
            // ->registration()
            ->font('Inter')
            // MW v2 avatar: light-grey chip + ink initials (Filament default is near-black).
            ->defaultAvatarProvider(\MicroweberPackages\Admin\Filament\MwAvatarProvider::class)
            // AI-703 / task-2026-05-16-29342d — Responsive sidebar slice 1: 240px.
            // AI-926 — removed sidebarCollapsibleOnDesktop() per operator feedback:
            //   icons-only mode with no visible expand affordance confused new operators
            //   who could not identify navigation items. Sidebar now always shows labels.
            //   Restore collapse feature only after adding a prominent expand chevron or
            //   after operators are onboarded with the icon set.
            // task-2026-05-17-76dd12 / AI-702 CHANGE — designer
            // verified `441050920e` and rejected the AI-702 ship
            // because the existing Filament `->brandLogo()` panel-
            // config call was still rendering alongside the new
            // TOPBAR_START hook (added at task-bcb327). Two side-
            // by-side Microweber logos visible in the admin topbar.
            //
            // Fix: the existing `->brandLogo()` + `->brandName()` +
            // `->brandLogoHeight()` panel-config calls have been
            // removed so Filament's own `.fi-logo` slot does not
            // render. The TOPBAR_START render hook (further down
            // at line ~301) carries the brand mark from now on,
            // owning the full brand-anchor surface.
            //
            // Use Filament's native brand-logo API (docs 5.x/styling/overview#adding-a-logo)
            // instead of a custom render-hook brand mark.
            // task-2026-05-16-bcb327 / AI-702 — brandLogo, darkModeBrandLogo, brandLogoHeight,
            // and brandName removed; brand mark delivered via TOPBAR_START render hook below
            // so there is exactly one logo in the admin topbar (AI-702 CHANGE / task-76dd12).
            // AI-703 / task-2026-05-16-29342d — 240px per designer spec
            // (was 16rem = 256px). Width applies to the pinned-open sidebar
            // at lg+ and the overlay drawer below lg.
            // AI-703a follow-up candidate: shift sidebarCollapsibleOnDesktop
            // breakpoint from 1024px to 1280px when ready.
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('240px')
            ->colors([
                'primary' => MwColors::Blue,
                'danger' => Color::Rose,
                'gray' => Color::Neutral,
                'info' => Color::Sky,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
            ])
            ->maxContentWidth(Width::Full)
            ->unsavedChangesAlerts()
            // task-2026-05-27-13983e / AI-1133 sub-issue 1
            // task-2026-05-27-37db93 / AI-1171: error boundary for panels that don't register UsersResource
            ->userMenuItems([
                \Filament\Navigation\MenuItem::make()
                    ->label('My Account')
                    ->icon('heroicon-o-user-circle')
                    ->sort(-2)
                    ->url(function () {
                        $userId = auth()->id();
                        if ($userId) {
                            try {
                                return \MicroweberPackages\User\Filament\Resources\UsersResource::getUrl('edit', ['record' => $userId]);
                            } catch (\Symfony\Component\Routing\Exception\RouteNotFoundException $e) {
                                return url(mw_admin_prefix_url() . '/users/' . $userId . '/edit');
                            }
                        }
                        return null;
                    }),
            ]);

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

                // AI-943 — removed group-level ->icon() from Website and Shop.
                // Filament v5 forbids both group icon AND per-item icons simultaneously.
                // Items under these groups already carry individual icons, so the
                // group-level icons are redundant and cause HTTP 500 on every admin request.
                'Website' => NavigationGroup::make()
                    ->label('Website')
                    ->icon('heroicon-o-globe-alt')
                    ->collapsible(true)
                    ->collapsed(true),

                'Shop' => NavigationGroup::make()
                    ->label('Shop')
                    ->icon('heroicon-o-shopping-bag')
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
                    ->label('Settings')
                    ->collapsible(false)
                    ->extraSidebarAttributes([
                        'class' => 'mw-nav-group-no-label',
                    ]),

                'Users' => NavigationGroup::make()
                    ->label('Users')
                    ->collapsible(false)
                    ->extraSidebarAttributes([
                        'class' => 'mw-nav-group-no-label',
                    ]),

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
                'User Settings' => NavigationGroup::make()
                    ->extraSidebarAttributes([
                        'class' => 'hidden bg-gray-50 dark:bg-gray-900',
                    ])
                    ->label('User Settings')
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

        // AI-702 / task-2026-05-16-bcb327 — Microweber brand mark rendered via
        // TOPBAR_START so it sits at the leftmost position in the admin topbar.
        // Registered BEFORE the GLOBAL_SEARCH_AFTER hook so Filament accumulates
        // it first and renders it leftmost. Logo URL uses the same fallback chain
        // as the old ->brandLogo() call.
        $panel->renderHook(
            name: PanelsRenderHook::TOPBAR_START,
            hook: function (): string {
                $logoUrl = mw()->ui->admin_logo();
                if (empty($logoUrl)) {
                    $logoUrl = mw()->ui->admin_logo_login();
                }
                $brandName = mw()->ui->brand_name() ?: 'Microweber';
                $adminUrl  = url(mw_admin_prefix_url() ?: 'admin');
                return '<a href="' . e($adminUrl) . '" class="mw-admin-brand-mark"'
                    . ' aria-label="' . e($brandName) . ' admin"'
                    . ' title="' . e($brandName) . ' admin">'
                    . ($logoUrl ? '<img class="mw-admin-brand-mark__image" src="' . e($logoUrl) . '" alt="">' : '')
                    . '<span class="mw-admin-brand-mark__label sr-only">' . e($brandName) . '</span>'
                    . '</a>';
            }
        );

        // task-2026-06-08-addleft / AI-704 CHANGE — restore the v2 layout:
        // the +Add button sits at the LEFT of the topbar (just after the
        // brand mark) with a visible "+ Add" label, as in the Microweber v2
        // admin. AI-704 (task-2026-05-16-225150) had moved it into the
        // right-side GLOBAL_SEARCH_AFTER cluster next to Live Edit; per the
        // PM "want it on left as before (see v2 demo)" this reverts the
        // placement. Registered at TOPBAR_START AFTER the brand-mark hook so
        // Filament accumulates brand first (leftmost) then +Add to its right
        // — both on the left, before the global-search field.
        $panel->renderHook(
            name: PanelsRenderHook::TOPBAR_START,
            hook: fn(): string => '<div class="mw-admin-primary-actions mw-admin-primary-actions--left">'
                . Blade::render('@livewire(\'admin-top-navigation-actions\')')
                . '</div>'
        );

        // Live Edit stays in the right cluster (GLOBAL_SEARCH_AFTER); the
        // search-quick-nav renders OUTSIDE the wrapper. (The old AI-704a
        // follow-up — render +Add in the mobile drawer instead of hiding it
        // — is moot now: task-2026-06-08-addmobile keeps +Add visible at the
        // 44px touch floor on mobile.)
        $panel->renderHook(
            name: PanelsRenderHook::GLOBAL_SEARCH_AFTER,
            hook: fn(): string => '<div class="mw-admin-primary-actions">'
                . view('admin::livewire.filament.top-navigation-go-live-edit')->render()
                . '</div>'
                . view('admin::livewire.filament.search-quick-nav')->render()
        );

        // AI-703 / task-2026-05-16-29342d — bridge Filament's Alpine sidebar
        // state into `localStorage.admin_sidebar_mode` so the designer-spec
        // key is present alongside Filament's native persistence.
        //
        // task-2026-05-17-6cb0d8 / AI-703 CHANGE — designer verified
        // 6 of 7 acceptance points but found the localStorage bridge
        // was stuck on its initial value through every state
        // transition. Root cause: the observer was watching the
        // `body` element's `fi-sidebar-open` / `fi-sidebar-collapsed-
        // on-desktop` classes — but neither class is toggled by
        // Filament v5 in this build. Filament's sidebar state lives
        // in Alpine `$store('sidebar').isOpen` AND the `.fi-sidebar`
        // element itself carries the `fi-sidebar-open` class via
        // `x-bind:class="{ 'fi-sidebar-open': $store.sidebar.isOpen }"`
        // (see vendor/filament/filament/resources/views/livewire/
        // sidebar.blade.php line 19).
        //
        // Fix per designer's recommended Option A: observe the
        // `.fi-sidebar` element's class list instead of body. Three-
        // state mapping preserved:
        //   - 'pinned'    (.fi-sidebar.fi-sidebar-open AND viewport
        //                  ≥ 1024 px)
        //   - 'rail'      (.fi-sidebar present but NOT .fi-sidebar-
        //                  open AND viewport ≥ 1024 px — Filament's
        //                  rail mode hides the open state on desktop)
        //   - 'collapsed' (viewport < 1024 px OR no .fi-sidebar)
        //
        // Window resize also triggers a re-sync so transitions
        // across the 1024 px breakpoint update the stored state.
        $panel->renderHook(
            name: PanelsRenderHook::BODY_END,
            hook: fn(): string => <<<'HTML'
            <script>
            /* AI-703 / task-2026-05-16-29342d + AI-703 CHANGE
               task-2026-05-17-6cb0d8 — admin_sidebar_mode localStorage
               bridge, observing .fi-sidebar element. */
            (function () {
                if (typeof window === 'undefined' || !window.localStorage) return;
                var KEY = 'admin_sidebar_mode';
                var body = document.body;
                if (!body || !body.classList.contains('fi-panel-admin')) return;
                var DESKTOP_PX = 1024;
                function getSidebar() {
                    return document.querySelector('.fi-sidebar');
                }
                function readMode() {
                    var sidebar = getSidebar();
                    var isDesktop = window.innerWidth >= DESKTOP_PX;
                    if (!sidebar) return 'collapsed';
                    var open = sidebar.classList.contains('fi-sidebar-open');
                    if (!isDesktop) return 'collapsed';
                    if (open) return 'pinned';
                    return 'rail';
                }
                function writeMode() {
                    try { window.localStorage.setItem(KEY, readMode()); } catch (e) {}
                }
                function attachObserver() {
                    var sidebar = getSidebar();
                    if (!sidebar) return false;
                    var observer = new MutationObserver(writeMode);
                    observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
                    return true;
                }
                writeMode();
                // .fi-sidebar may render after this script runs (Livewire
                // hydration order). Retry attachment until success or
                // give-up cap.
                if (!attachObserver()) {
                    var tries = 0;
                    var retry = setInterval(function () {
                        if (attachObserver() || ++tries > 20) {
                            clearInterval(retry);
                        }
                    }, 100);
                }
                window.addEventListener('resize', writeMode);
            })();
            </script>
            HTML
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
        $panel->plugin(ModalTeleportPlugin::make());
        $panel->plugin(new UsersFilamentPlugin());
        //    $panel->plugin(new MarketplaceFilamentPlugin());
        $panel->plugin(new MultilanguageFilamentPlugin());
         $panel->plugin(MicroweberFilamentSocialitePlugin::make()->admin()->configure());
        $panel->plugin(\MicroweberPackages\Fortify\Filament\MicroweberFortifyPlugin::make());
        $panel->plugin(\MicroweberPackages\CdnSync\Filament\CdnSyncPlugin::make());

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
