<?php

namespace MicroweberPackages\Filament;


use Filament\Contracts\Plugin;
use Filament\Forms\Components\Wizard;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentIcon;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use MicroweberPackages\MetaTags\AdminFilamentMetaTagsRenderer;
use MicroweberPackages\MetaTags\AdminMetaTagsRenderer;
use MicroweberPackages\User\Filament\UsersFilamentPlugin;

class MicroweberTheme implements Plugin
{
    public function getId(): string
    {
        return 'microweber-theme';
    }

    public function register(Panel $panel): void
    {

    }

    public function boot(Panel $panel): void
    {
        static::configureColorShades();
        static::configureComponents();
        static::configureAssets();

        $head = new AdminFilamentMetaTagsRenderer();
        $headTags = $head->getHeadMetaTags();
        $footerTags = $head->getFooterMetaTags();

        $panel->renderHook(
            name: PanelsRenderHook::HEAD_START,
            hook: fn(): string => $headTags
        );
        $panel->renderHook(
            name: PanelsRenderHook::BODY_END,
            hook: fn(): string => $footerTags
        );
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

    public static function configureAssets(): void
    {
        // Assets are now registered via $panel->renderHook() in the boot() method
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
        $all = Color::all();
        $theme = [
            'danger' => Color::Rose,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => Color::Blue,
            'success' => Color::Emerald,
            'warning' => Color::Yellow,

        ];
        return array_merge($all, $theme);
    }

    public static function getIcons(): array
    {
        return [
            'breadcrumbs.separator' => new HtmlString('/'),
            'breadcrumbs.separator.rtl' => new HtmlString('\\'),
            'modal.close-button' => 'heroicon-o-x-mark',

            'actions::delete-action.modal' => 'heroicon-o-trash',
            'actions::detach-action.modal' => 'heroicon-o-x-mark',
            'actions::dissociate-action.modal' => 'heroicon-o-x-mark',
            'actions::force-delete-action.modal' => 'heroicon-o-trash',
            'actions::restore-action.modal' => 'heroicon-o-arrow-uturn-left',

            'forms::components.wizard.completed-step' => 'heroicon-m-check',

            'infolists::components.icon-entry.false' => 'heroicon-o-x-circle',
            'infolists::components.icon-entry.true' => 'heroicon-o-check-circle',

            'notifications::database.modal.empty-state' => 'heroicon-o-bell-slash',
            'notifications::database.modal.empty-state' => 'heroicon-o-bell-slash',

            'panels::pages.dashboard.navigation-item' => 'heroicon-m-home',
            'panels::resources.pages.edit-record.navigation-item' => 'heroicon-m-pencil-square',
            'panels::resources.pages.manage-related-records.navigation-item' => 'heroicon-m-rectangle-stack',
            'panels::resources.pages.view-record.navigation-item' => 'heroicon-m-eye',
            'panels::sidebar.collapse-button' => 'heroicon-o-chevron-left',
            'panels::sidebar.collapse-button.rtl' => 'heroicon-o-chevron-right',
            'panels::sidebar.expand-button' => 'heroicon-o-chevron-right',
            'panels::sidebar.expand-button.rtl' => 'heroicon-o-chevron-left',
            'panels::topbar.open-database-notifications-button' => 'heroicon-o-bell',
            'panels::topbar.open-sidebar-button' => 'heroicon-o-bars-3',
            'panels::topbar.close-sidebar-button' => 'heroicon-o-x-mark',

            'tables::columns.icon-column.false' => 'heroicon-o-x-circle',
            'tables::columns.icon-column.true' => 'heroicon-o-check-circle',
            'tables::empty-state' => 'heroicon-o-x-mark',
        ];
    }
}
