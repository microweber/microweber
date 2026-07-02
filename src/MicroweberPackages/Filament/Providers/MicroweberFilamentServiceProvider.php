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
