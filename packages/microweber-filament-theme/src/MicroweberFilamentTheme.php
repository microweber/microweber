<?php

namespace MicroweberPackages\MicroweberFilamentTheme;

use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Assets\Theme;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use MicroweberPackages\MetaTags\AdminFilamentMetaTagsRenderer;
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
//            ->primaryColor(Color::Amber)
//            ->secondaryColor(Color::Gray)
//            ->warningColor(Color::Amber)
//            ->dangerColor(Color::Rose)
//            ->successColor(Color::Green)
//            ->grayColor(Color::Gray)
            ->theme('microweber-filament-theme');
    }

    public function boot(Panel $panel): void
    {
        static::configureColorShades();
        static::configureComponents();
        static::configureAssets();
    }

    public static function configureAssets(): void
    {


        FilamentAsset::register([
            //  Theme::make('microweber-filament-theme', __DIR__ . '/../resources/dist/css/microweber-filament-theme.css'),
            Theme::make('microweber-filament-theme', public_asset('vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css')),
            Js::make('microweber-filament-theme-js', public_asset('vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.js')),

        ]);


        $head = new AdminFilamentMetaTagsRenderer();

        $headTags = $head->getHeadMetaTags();
        $footerTags = $head->getFooterMetaTags();

        Filament::serving(function () use ($headTags, $footerTags) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::HEAD_START,
                fn(): string => $headTags
            );
            FilamentView::registerRenderHook(
                PanelsRenderHook::BODY_END,
                fn(): string => $footerTags
            );
//                    FilamentAsset::register([
//                        //Js::make('example-external-script', 'external.js'),
//                        //Css::make('custom-stylesheet', __DIR__ . '/../../resources/css/custom.css')->loadedOnRequest(),
//                    ]);
//
//          //            FilamentAsset::register([
//                        //Js::make('example-external-script', 'external.js'),
//                        //Css::make('custom-stylesheet', __DIR__ . '/../../resources/css/custom.css')->loadedOnRequest(),
//                    ]);
        });

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
        return [
            'mw-secondary' => Color::rgb('rgb(24,36,51)'),
            'mw-primary' => Color::rgb('rgb(69, 146, 255)'),
            'mw-sky-blue' => Color::rgb('rgb(255, 191, 0)'),
            'mw-light-green' => Color::rgb('rgb(226, 249, 230)'),
        ];
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
