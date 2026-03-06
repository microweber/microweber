<?php

namespace MicroweberPackages\Multilanguage;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Contracts\Plugin;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Panel;
// use Filament\SpatieLaravelTranslatablePlugin;
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
            //     return;
        }


        // TODO
        $defaultLocales = [];
        if (mw_is_installed() and function_exists('get_supported_languages')) {
            try {
                if (Schema::hasTable('multilanguage_supported_locales')) {
                    $getSupportedLocales = DB::table('multilanguage_supported_locales')
                        ->where('is_active', 1)
                        ->get();
                    if ($getSupportedLocales->count() > 0) {
                        foreach ($getSupportedLocales as $locale) {
                            $defaultLocales[] = $locale->locale;
                        }
                    }
                }
            } catch (\Exception $e) {
                $defaultLocales = [];
            }
        }
        if (empty($defaultLocales)) {
            //@todo disable multilanguage
            $defaultLocales = ['en_US'];
        }

        // $panel->plugin(SpatieLaravelTranslatablePlugin::make()->defaultLocales($defaultLocales));
        $panel->plugin(FilamentTranslateFieldPlugin::make()->defaultLocales($defaultLocales));




        if (mw_is_installed() and function_exists('get_supported_languages')) {

            $supportedLanguages = get_supported_languages();

            Field::macro('mwTranslatableOption', function () use ($supportedLanguages) {

                if (empty($supportedLanguages)) {
                    return $this;
                }
                if (!MultilanguageHelpers::multilanguageIsEnabled()) {
                    return $this;
                }


                $fieldName = $this->getName();
                $fieldName = str_replace('options.', 'translatableOptions.', $fieldName);

                if (class_basename($this) == 'TextInput') {

                    $textInput = TextInput::make($fieldName)
                        ->live(debounce:300)
                        ->helperText($this->getHelperText())
                        ->placeholder($this->getPlaceholder())
                        ->view('mw-filament::components.text-input-option-translatable', [
                            'supportedLanguages' => $supportedLanguages,
                        ]);

                    return $textInput;
                } else if (class_basename($this) == 'Textarea') {

                    $textarea = Textarea::make($fieldName)
                        ->helperText($this->getHelperText())
                        ->placeholder($this->getPlaceholder())
                        ->live(debounce:300)
                        ->view('mw-filament::components.textarea-option-translatable', [
                            'supportedLanguages' => $supportedLanguages
                        ]);

                    return $textarea;
                }

                throw new \Exception('Unsupported field type: ' . class_basename($this));

                return $this;
            });

            $panel->plugin(FilamentTranslatableFieldsPlugin::make()->supportedLanguages($supportedLanguages));




             // TODO
           // MultilanguageHelpers::setMultilanguageEnabled(true);

            if (MultilanguageHelpers::multilanguageIsEnabled()) {
                self::configureLanguageSwitch();
            }
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
