<?php

namespace MicroweberPackages\Multilanguage;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Vite;
use MicroweberPackages\Filament\Plugins\FilamentTranslatableFieldsPlugin;
use SolutionForest\FilamentTranslateField\FilamentTranslateFieldPlugin;

class MultilanguageFilamentPlugin implements Plugin
{

    public function getId(): string
    {
        return 'multilanguage';
    }

    public function register(Panel $panel): void
    {

        if (!MultilanguageHelpers::multilanguageIsEnabled()) {
            return;
        }


        // TODO
        $defaultLocales = [];

        try {
            if (Schema::hasTable('multilanguage_locales')) {
                $getSupportedLocales = DB::table('multilanguage_supported_locales')
                    ->where('is_active', 'y')->get();
                if ($getSupportedLocales->count() > 0) {
                    foreach ($getSupportedLocales as $locale) {
                        $defaultLocales[] = $locale->locale;
                    }
                }
            }
        } catch (\Exception $e) {
            $defaultLocales = [];
        }


        if (empty($defaultLocales)) {
            //@todo disable multilanguage
            $defaultLocales = ['en_US'];
        }


        $panel->plugin(SpatieLaravelTranslatablePlugin::make()->defaultLocales($defaultLocales));
        $panel->plugin(FilamentTranslateFieldPlugin::make()->defaultLocales($defaultLocales));
        if (MultilanguageHelpers::multilanguageIsEnabled()) {
            $panel->plugin(FilamentTranslatableFieldsPlugin::make()->supportedLanguages(get_supported_languages()));
            self::configureLanguageSwitch();
        }

    }

    public function boot(Panel $panel): void
    {
        if (!MultilanguageHelpers::multilanguageIsEnabled()) {
            return;
        }
        FilamentAsset::register([
            //  Js::make('mw-filament-translatable', Vite::asset('src/MicroweberPackages/Multilanguage/resources/js/filament-translatable.js')),
        ]);


        $multilanguageSharedData = [
            'translationLocale' => get_supported_language_by_locale(current_lang()),
            'supportedLocales' => get_supported_languages(),
        ];

        FilamentAsset::registerScriptData([
            'multilanguage' => $multilanguageSharedData,
        ]);
    }


    public static function configureLanguageSwitch(): void
    {

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $langs = get_supported_languages();

            if ($langs) {
                $locales = [];
                $flags = [];
                foreach ($langs as $lang) {
                    $locales[] = $lang['locale'];
                    if (isset($lang['iconUrl']) and $lang['iconUrl']) {
                        $flags[$lang['locale']] = $lang['iconUrl'];
                    }
                }
                $switch->locales($locales);

                $switch->flags($flags);
            }


        });
    }

}
