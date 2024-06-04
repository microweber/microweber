<?php

namespace MicroweberPackages\Multilanguage;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\DB;
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
        // TODO
        $defaultLocales = [];
        $getSupportedLocales = DB::table('multilanguage_supported_locales')
            ->where('is_active', 'y')->get();
        if ($getSupportedLocales->count() > 0) {
            foreach ($getSupportedLocales as $locale) {
                $defaultLocales[] = $locale->locale;
            }
        }
        $panel->plugin(SpatieLaravelTranslatablePlugin::make()->defaultLocales($defaultLocales));
        $panel->plugin(FilamentTranslateFieldPlugin::make()->defaultLocales($defaultLocales));
        $panel->plugin(FilamentTranslatableFieldsPlugin::make()->supportedLocales($defaultLocales));
    }

    public function boot(Panel $panel): void
    {
        FilamentAsset::registerScriptData([
            'multilanguage' => [
                'currentLanguage' => current_lang(),
            ],
        ]);
    }

}
