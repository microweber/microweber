<?php

namespace MicroweberPackages\MicroweberFilamentTheme;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Theme;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use Filament\Support\Assets\Js;

class MicroweberFilamentTheme implements Plugin
{
    public function getId(): string
    {
        return 'microweber-filament-theme';
    }

    public function register(Panel $panel): void
    {

        $panel
            ->font('DM Sans')
            ->colors(static::getColors())
            ->theme('microweber-filament-theme');
    }

    public function boot(Panel $panel): void
    {
        static::configureColorShades();
        static::configureComponents();
        static::configureAssets();

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_START,
            fn (): string => '<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:p-4 focus:bg-white focus:text-primary-600 dark:focus:bg-gray-900 dark:focus:text-primary-400">Skip to main content</a>',
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::CONTENT_START,
            fn (): string => '<div id="main-content"></div>',
        );

            }

    public static function configureAssets(): void
    {
        // Register filament assets. The built bundles are served at a stable,
        // UN-versioned URL, so browsers cache them across deploys and admins see
        // stale CSS/JS after an update. Append ?v=<filemtime> so each rebuild
        // busts the cache (dev + prod) without changing the path.
        $build = 'vendor/microweber-packages/microweber-filament-theme/build/';
        $asset = static function (string $file) use ($build): string {
            $url = public_asset($build . $file);
            $path = public_path($build . $file);
            return is_file($path) ? $url . '?v=' . filemtime($path) : $url;
        };

        FilamentAsset::register([
            Theme::make('microweber-filament-theme', $asset('microweber-filament-theme.css')),
            Js::make('microweber-filament-theme-js', $asset('microweber-filament-theme.js')),
            Js::make('mw-media-browser-js', $asset('mw-media-browser.js')),
            Js::make('mw-tree-component-js', $asset('mw-tree-component.js')),
        ]);
    }

    public static function configure(): void
    {
        static::configureColors();
        static::configureColorShades();
        static::configureComponents();
        static::configureIcons();
    }

    public static function configureColors(): void
    {
        FilamentColor::register(static::getColors());
    }

    public static function configureColorShades(): void
    {
        FilamentColor::addShades('badge', [100, 300, 700]);
        FilamentColor::removeShades('badge', [50, 600]);
        FilamentColor::addShades('badge.icon', [400, 600]);
        FilamentColor::removeShades('badge.icon', [500]);
        FilamentColor::addShades('notifications::notification.icon', [500]);
    }

    public static function configureComponents(): void
    {
        //  Wizard::configureUsing(fn(Wizard $wizard): Wizard => $wizard->contained(false));
    }

    public static function configureIcons(): void
    {
        FilamentIcon::register(static::getIcons());
    }

    public static function getColors(): array
    {
        return Palette::colors();
    }

    public static function getIcons(): array
    {
        return [
            'breadcrumbs.separator' => new HtmlString('/'),
            'breadcrumbs.separator.rtl' => new HtmlString('\\'),
            'modal.close-button' => 'heroicon-s-x-mark',

            'actions::delete-action.modal' => 'heroicon-s-trash',
            'actions::detach-action.modal' => 'heroicon-s-x-mark',
            'actions::dissociate-action.modal' => 'heroicon-s-x-mark',
            'actions::force-delete-action.modal' => 'heroicon-s-trash',
            'actions::restore-action.modal' => 'heroicon-s-arrow-uturn-left',

            'forms::components.wizard.completed-step' => 'heroicon-m-check',

            'infolists::components.icon-entry.false' => 'heroicon-s-x-circle',
            'infolists::components.icon-entry.true' => 'heroicon-s-check-circle',

            'notifications::database.modal.empty-state' => 'heroicon-s-bell-slash',

            'panels::pages.dashboard.navigation-item' => 'heroicon-m-home',
            'panels::resources.pages.edit-record.navigation-item' => 'heroicon-m-pencil-square',
            'panels::resources.pages.manage-related-records.navigation-item' => 'heroicon-m-rectangle-stack',
            'panels::resources.pages.view-record.navigation-item' => 'heroicon-m-eye',
            'panels::sidebar.collapse-button' => 'heroicon-s-chevron-left',
            'panels::sidebar.collapse-button.rtl' => 'heroicon-s-chevron-right',
            'panels::sidebar.expand-button' => 'heroicon-s-chevron-right',
            'panels::sidebar.expand-button.rtl' => 'heroicon-s-chevron-left',
            'panels::topbar.open-database-notifications-button' => 'heroicon-s-bell',
            'panels::topbar.open-sidebar-button' => 'heroicon-s-bars-3',
            'panels::topbar.close-sidebar-button' => 'heroicon-s-x-mark',

            'tables::columns.icon-column.false' => 'heroicon-s-x-circle',
            'tables::columns.icon-column.true' => 'heroicon-s-check-circle',
            'tables::empty-state' => 'heroicon-s-x-mark',
        ];
    }
}
