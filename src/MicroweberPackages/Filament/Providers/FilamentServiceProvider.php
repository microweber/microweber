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


use Filament\Facades\Filament;
use Filament\FilamentServiceProvider as BaseFilamentPackageServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Core\Providers\Concerns\MergesConfig;


class FilamentServiceProvider extends BaseFilamentPackageServiceProvider
{
    use MergesConfig;

    public function packageRegistered(): void
    {
        parent::packageRegistered();
        //register FilamentManager
        $this->mergeConfigFrom(__DIR__ . '/../config/filament.php', 'filament');
        $this->mergeConfigFrom(__DIR__ . '/../config/money.php', 'money');

        $this->app->scoped('filament', function (): FilamentManager {
            return new FilamentManager();
        });


        $this->app->register(TablesServiceProvider::class);
        $this->app->register(NotificationsServiceProvider::class);
        $this->app->register(FormsServiceProvider::class);
        $this->app->register(WidgetsServiceProvider::class);

    }

    public function packageBooted(): void
    {
        parent::packageBooted();
        $originalFolder = new \ReflectionClass(\Filament\FilamentServiceProvider::class);
        $originalFolder = dirname(dirname(dirname($originalFolder->getFileName())));
        $originalFolder = normalize_path($originalFolder, true);

        if (is_dir($originalFolder)) {
            View::addNamespace('filament', $originalFolder . 'filament/resources/views/components');
            View::addNamespace('filament-panels', $originalFolder . 'filament/resources/views');
            View::addNamespace('filament-forms', $originalFolder . 'forms/resources/views');
            View::addNamespace('filament-notifications', $originalFolder . 'notifications/resources/views');
            View::addNamespace('filament-tables', $originalFolder . 'tables/resources/views');
            View::addNamespace('filament-widgets', $originalFolder . 'widgets/resources/views');

            $this->loadTranslationsFrom($originalFolder . 'filament/resources/lang', 'filament-panels');
//            $this->loadTranslationsFrom($originalFolder . 'forms/resources/lang', 'filament-forms');
//            $this->loadTranslationsFrom($originalFolder . 'notifications/resources/lang', 'filament-notifications');
//            $this->loadTranslationsFrom($originalFolder . 'tables/resources/lang', 'filament-tables');
//            $this->loadTranslationsFrom($originalFolder . 'widgets/resources/lang', 'filament-widgets');

        }
        View::prependNamespace('filament-panels', dirname(__DIR__) . '/resources/views/filament');
        View::prependNamespace('filament-tables', dirname(__DIR__) . '/resources/views/filament-tables');
        View::prependNamespace('filament-infolists', dirname(__DIR__) . '/resources/views/filament-infolists');
        View::prependNamespace('filament-forms', dirname(__DIR__) . '/resources/views/filament-forms');
        View::prependNamespace('filament-actions', dirname(__DIR__) . '/resources/views/filament-actions');
        //View::prependNamespace('filament-panels', base_path() . '/resources/views');

        View::prependNamespace('radio-deck', dirname(__DIR__) . '/resources/views/radio-deck');

        View::prependNamespace('filament-title-with-slug', dirname(__DIR__) . '/resources/packages/filament-title-with-slug/views');
        $this->loadTranslationsFrom(dirname(__DIR__) . '/resources/packages/filament-title-with-slug/lang', 'filament-title-with-slug');

        Filament::serving(function () {
            \Livewire\Livewire::forceAssetInjection();
//            Filament::registerViteTheme(
//                'resources/css/microweber-admin-filament.scss',
//                'public/build'
//            );
//            Filament::registerViteTheme(
//                'resources/css/filament/admin/theme.css',
//                'public/build'
//            );
        });




        if(mw_is_installed()) {
            // TODO move to the multilanguage module
//            $defaultLocales = [];
//            $defaultLocaleFlags = [];
//            $getSupportedLocales = DB::table('multilanguage_supported_locales')
//                ->whereNotNull('locale')
//                ->where('locale', '!=', '')
//                ->where('is_active', 'y')->get();
//            if ($getSupportedLocales->count() > 0) {
//                foreach ($getSupportedLocales as $locale) {
//                    $flagUrl = modules_url() . 'microweber/api/libs/flag-icon-css/flags/1x1/' . get_flag_icon($locale->locale) . '.svg';
//                    $defaultLocaleFlags[$locale->locale] = $flagUrl;
//                    $defaultLocales[] = $locale->locale;
//                }
//            }
//
//            LanguageSwitch::configureUsing(function (LanguageSwitch $switch) use ($defaultLocales, $defaultLocaleFlags) {
//                $switch->flags($defaultLocaleFlags);
//                $switch->locales($defaultLocales); // also accepts a closure
//            });
        }
        // Register filament assets
        FilamentAsset::register([
            AlpineComponent::make('mw-media-browser', __DIR__ . '/../resources/js/components/dist/mw-media-browser.js'),
        ], 'mw-filament/forms');

    }



}
