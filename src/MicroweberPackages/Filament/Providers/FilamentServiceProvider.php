<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Filament\Providers;


use Arcanedev\Support\Providers\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Forms\FormsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Core\Providers\Concerns\MergesConfig;


class FilamentServiceProvider extends ServiceProvider
{

    use MergesConfig;

    public function register()
    {
        $this->app->register(TablesServiceProvider::class);
        $this->app->register(NotificationsServiceProvider::class);
        $this->app->register(FormsServiceProvider::class);
        $this->app->register(FilamentPackageServiceProvider::class);

        $this->mergeConfigFrom(__DIR__ . '/../config/filament.php', 'filament');
        $this->mergeConfigFrom(__DIR__ . '/../config/notifications.php', 'notifications');
    }


    public function boot()
    {
        $originalFolder = new \ReflectionClass(\Filament\FilamentServiceProvider::class);
        $originalFolder = dirname(dirname($originalFolder->getFileName()));
        $originalViewsFolder = normalize_path($originalFolder . '/resources/views', true);
        $originalLangFolder = normalize_path($originalFolder . '/resources/lang', true);
        if (is_dir($originalViewsFolder)) {
            View::addNamespace('filament', $originalViewsFolder);
        }

        View::prependNamespace('filament', dirname(__DIR__).'/resources/views');
        View::prependNamespace('filament', base_path() . '/resources/views');

        if (is_dir($originalLangFolder)) {
            $this->loadTranslationsFrom($originalLangFolder, 'filament');
        }

        Filament::serving(function () {
            Filament::registerViteTheme(
                'resources/css/microweber-admin-filament.css',
            'public/build'
            );
        });
    }
}
