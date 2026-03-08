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
     // Filament v5 moved all Tables\Actions\* classes to Filament\Actions\*.
     // Register backward-compatible class aliases so existing code continues to work.
     $tablesActionsAliases = [
         'Filament\\Tables\\Actions\\Action'          => 'Filament\\Actions\\Action',
         'Filament\\Tables\\Actions\\ActionGroup'     => 'Filament\\Actions\\ActionGroup',
         'Filament\\Tables\\Actions\\BulkAction'      => 'Filament\\Actions\\BulkAction',
         'Filament\\Tables\\Actions\\BulkActionGroup' => 'Filament\\Actions\\BulkActionGroup',
         'Filament\\Tables\\Actions\\CreateAction'    => 'Filament\\Actions\\CreateAction',
         'Filament\\Tables\\Actions\\DeleteAction'    => 'Filament\\Actions\\DeleteAction',
         'Filament\\Tables\\Actions\\DeleteBulkAction'=> 'Filament\\Actions\\DeleteBulkAction',
         'Filament\\Tables\\Actions\\EditAction'      => 'Filament\\Actions\\EditAction',
         'Filament\\Tables\\Actions\\ExportAction'    => 'Filament\\Actions\\ExportAction',
         'Filament\\Tables\\Actions\\ExportBulkAction'=> 'Filament\\Actions\\ExportBulkAction',
         'Filament\\Tables\\Actions\\ViewAction'      => 'Filament\\Actions\\ViewAction',
         'Filament\\Tables\\Actions\\ImportAction'    => 'Filament\\Actions\\ImportAction',
     ];
     foreach ($tablesActionsAliases as $alias => $original) {
         if (!class_exists($alias) && class_exists($original)) {
             class_alias($original, $alias);
         }
     }

 // Register core Filament v5 panel providers (no deprecated FilamentServiceProvider)
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
