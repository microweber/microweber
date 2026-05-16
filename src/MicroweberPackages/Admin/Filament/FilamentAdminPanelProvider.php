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

    public function boot(): void
    {
        Table::configureUsing(function (Table $table): Table {
            return $table
                ->paginationPageOptions([10, 25, 50, 100, 250])
                ->defaultPaginationPageOption(25);
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
            ->globalSearch(true)
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
            // AI-703 / task-2026-05-16-29342d — Responsive sidebar:
            //   - Desktop (Filament default lg+ = ≥1024px): drawer pinned-open
            //     as a 240px left sidebar with collapse-to-rail toggle.
            //   - Below lg: drawer overlays content + hamburger toggle.
            //   Designer spec calls for the breakpoint at 1280px specifically;
            //   Filament's native lg breakpoint (1024px) is shipped here for
            //   slice-1 simplicity. Tuning the breakpoint to 1280px is the
            //   AI-703a follow-up candidate (will require CSS overrides at
            //   1024-1279.98px to undo Filament's `lg:` pinned utilities).
            ->sidebarCollapsibleOnDesktop()
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
            // ->brandLogoHeight('34px')
            // ->brandLogo(function () {
            //     $logo = mw()->ui->admin_logo();
            //     if (empty($logo)) {
            //         $logo = mw()->ui->admin_logo_login();
            //     }
            //     return $logo;
            // })
            // ->brandName(function () {
            //     return mw()->ui->brand_name();
            // })
            // AI-703 / task-2026-05-16-29342d — 240px per designer spec
            // (was 16rem = 256px). Width applies to the pinned-open sidebar
            // at lg+ and the overlay drawer below lg.
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

        // task-2026-05-16-bcb327 / AI-702 — Restore Microweber brand
        // mark at the very top-left of the admin top bar per designer
        // spec (admin-shell-improvements-2026-05-16.md §2 AD1).
        //
        // Filament's TOPBAR_START render hook accumulates hooks in
        // registration order; this hook is registered BEFORE the
        // existing `admin-top-navigation-actions` hook below, so the
        // brand mark renders leftmost (the desired anchor position).
        //
        // Logo URL falls back through the same chain `brandLogo()`
        // uses earlier in this provider (admin_logo → admin_logo_login)
        // so the brand mark stays in sync with the configured admin
        // logo. Click routes to /admin per standard convention.
        //
        // Mobile (≤768px) collapses to mark-only via CSS in
        // general-styles.css — markup is identical; the wordmark
        // alt-text is hidden visually via .mw-admin-brand-mark__label.
        $panel->renderHook(
            name: PanelsRenderHook::TOPBAR_START,
            hook: function (): string {
                $logoUrl = mw()->ui->admin_logo();
                if (empty($logoUrl)) {
                    $logoUrl = mw()->ui->admin_logo_login();
                }
                $brandName = mw()->ui->brand_name() ?: 'Microweber';
                $adminUrl = url(mw_admin_prefix_url() ?: 'admin');
                return '<a href="' . e($adminUrl) . '"'
                    . ' class="mw-admin-brand-mark"'
                    . ' aria-label="' . e($brandName) . ' admin"'
                    . ' title="' . e($brandName) . ' — back to admin home">'
                    . '<img src="' . e($logoUrl) . '"'
                    . ' alt="' . e($brandName) . '"'
                    . ' class="mw-admin-brand-mark__image" />'
                    . '<span class="mw-admin-brand-mark__label sr-only">' . e($brandName) . '</span>'
                    . '</a>';
            }
        );

        // AI-704 / task-2026-05-16-225150 — Re-cluster +Add with Live Edit
        // on the right side of the admin top bar per designer spec
        // (admin-shell-improvements-2026-05-16.md §2 AD3).
        //
        // Previous: +Add was registered as its own TOPBAR_START hook so it
        // sat at the LEFT edge (just after the AI-702 brand mark, before
        // the hamburger), visually isolated and competing with the brand.
        //
        // Now:     both +Add and Live Edit render together via
        // GLOBAL_SEARCH_AFTER, wrapped in a `.mw-admin-primary-actions`
        // flex container with `gap: var(--space-sm)` — primary actions
        // cluster sits in the right half of the topbar after search,
        // before Filament's stock user/notifications cluster.
        //
        // Mobile collapse: see general-styles.css AI-704 block — at
        // ≤768px the +Add button is hidden (its functions remain
        // accessible via the sidebar drawer / resource pages). The
        // explicit "render +Add as a hamburger-menu item on mobile"
        // step from §2 AD3 is AI-704a follow-up.
        $panel->renderHook(
            name: PanelsRenderHook::GLOBAL_SEARCH_AFTER,
            hook: fn(): string => '<div class="mw-admin-primary-actions">'
                . Blade::render('@livewire(\'admin-top-navigation-actions\')')
                . view('admin::livewire.filament.top-navigation-go-live-edit')->render()
                . '</div>'
                . view('admin::livewire.filament.search-quick-nav')->render()
        );

        // AI-703 / task-2026-05-16-29342d — bridge Filament's Alpine sidebar
        // state into `localStorage.admin_sidebar_mode` so the designer-spec
        // key is present alongside Filament's native persistence. Watches the
        // `body` element's `fi-sidebar-open` / `fi-sidebar-collapsed-on-desktop`
        // class state and mirrors to one of three string values:
        //   - 'pinned'    (lg+ sidebar open and not collapsed)
        //   - 'rail'      (lg+ sidebar open and collapsed to rail)
        //   - 'collapsed' (sidebar closed — applies < lg by default)
        $panel->renderHook(
            name: PanelsRenderHook::BODY_END,
            hook: fn(): string => <<<'HTML'
            <script>
            /* AI-703 / task-2026-05-16-29342d — admin_sidebar_mode localStorage bridge */
            (function () {
                if (typeof window === 'undefined' || !window.localStorage) return;
                var KEY = 'admin_sidebar_mode';
                var body = document.body;
                if (!body || !body.classList.contains('fi-panel-admin')) return;
                function readMode() {
                    var classes = body.classList;
                    var collapsedRail = classes.contains('fi-sidebar-collapsed-on-desktop');
                    var open = classes.contains('fi-sidebar-open');
                    if (open && !collapsedRail) return 'pinned';
                    if (open && collapsedRail) return 'rail';
                    return 'collapsed';
                }
                function writeMode() {
                    try { window.localStorage.setItem(KEY, readMode()); } catch (e) {}
                }
                writeMode();
                var observer = new MutationObserver(writeMode);
                observer.observe(body, { attributes: true, attributeFilter: ['class'] });
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
