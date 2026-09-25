<?php
/*
* This file is part of the Microweber framework.
*
* (c) Microweber CMS LTD
*
* For full license information see
* https://github.com/microweber/microweber/blob/master/LICENSE
*/

namespace MicroweberPackages\Filament\Providers;


use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Filament\Support\RegistersMwDialogMacro;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentThemeServiceProvider;
use MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider;

class MicroweberFilamentServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        // Register core Filament v5 panel providers
        $this->app->register(MicroweberFilamentThemeServiceProvider::class);
        $this->app->register(FilamentAdminPanelProvider::class);
    }

    public function boot()
    {
        RegistersMwDialogMacro::register();

        // Global-search entries are now registered by each module's own
        // ServiceProvider via FilamentRegistry::registerGlobalSearchEntry();
        // the old hardcoded GlobalSearchRegistrar has been removed.

        // Register Livewire's JavaScript via a render hook so it is injected at
        // render time (with full data attributes like data-update-uri, data-csrf, etc.).
        // The custom Livewire fork does not register itself with Filament – we do it here.
        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_BEFORE,
            fn (): HtmlString => new HtmlString(
                \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts()
            ),
        );

        // Inject Microweber admin JS assets (admin.js, libs, etc.) into the Filament head.
        // Using FilamentView::registerRenderHook (global) so it fires for all admin pages.
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_START,
            function (): HtmlString {
                $renderer = new \MicroweberPackages\MetaTags\AdminFilamentMetaTagsRenderer();
                return new HtmlString($renderer->getHeadMetaTags());
            },
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            function (): HtmlString {
                $renderer = new \MicroweberPackages\MetaTags\AdminFilamentMetaTagsRenderer();
                return new HtmlString($renderer->getFooterMetaTags());
            },
        );

        // Roomier list-table rows. Filament sets the record content container's
        // block padding as a Tailwind @layer utility; the theme bundle's own
        // rules are ALSO layered, so even !important cannot outrank it. An
        // UNLAYERED <style> injected after the panel styles wins cleanly — this
        // is the only reliable lever (verified: layered overrides stay at 4px).
        FilamentView::registerRenderHook(
            PanelsRenderHook::STYLES_AFTER,
            fn (): HtmlString => new HtmlString(
                '<style id="mw-roomier-table-rows">'
                . 'body.fi-panel-admin .fi-ta-content .fi-ta-record > .fi-ta-record-content-ctn,'
                . 'body.fi-panel-checkout .fi-ta-content .fi-ta-record > .fi-ta-record-content-ctn,'
                . 'body.fi-panel-profile .fi-ta-content .fi-ta-record > .fi-ta-record-content-ctn{'
                . 'padding-top:16px !important;padding-bottom:16px !important;}'
                . '</style>'
            ),
        );

        // Register custom Filament panel component views (e.g. layout.live-edit) under 'filament-panels' namespace
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views/filament',
            'filament-panels'
        );

        // Register custom Filament component views under 'mw-filament' namespace
        // This replaces the deprecated 'filament-forms::components.' pattern
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views/filament-forms',
            'mw-filament'
        );

        // Register custom Filament table column views - prepend to 'filament-tables' namespace
        // so our custom columns (ClickableColumn, SVGColumn, BadgesColumn, etc.) are found
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views/filament-tables',
            'filament-tables'
        );

        // Register custom Filament action/infolist views
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views/filament-actions',
            'filament-actions'
        );

        $this->loadViewsFrom(
            __DIR__ . '/../resources/views/filament-infolists',
            'filament-infolists'
        );
    }
}
