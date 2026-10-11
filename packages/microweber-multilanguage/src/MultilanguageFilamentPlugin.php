<?php

namespace MicroweberPackages\Multilanguage;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
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

                // Pick the per-language view for the field type. Unsupported types
                // are returned unchanged rather than throwing — a single non-text
                // translatable field must never break the whole form for direct
                // callers (BtnModuleSettings, etc.).
                $view = match (class_basename($this)) {
                    'TextInput' => 'mw-filament::components.text-input-option-translatable',
                    'Textarea'  => 'mw-filament::components.textarea-option-translatable',
                    default     => null,
                };
                if ($view === null) {
                    return $this;
                }

                // Mutate the field IN PLACE rather than rebuilding it:
                //  - rebinds the state from options.* to translatableOptions.* so
                //    the per-language values land in the form's $translatableOptions
                //    property (read by the save handlers),
                //  - swaps to the per-language view (rendered inside the standard
                //    field wrapper, so belowContent/helper text still show).
                // Filament v5 removed the getHelperText() getter (helperText() now
                // registers a belowContent component with no public reader), so the
                // old "recreate + ->helperText($this->getHelperText())" approach
                // fatals. Mutating preserves helperText, label, placeholder,
                // required, etc. for free — no getter needed.
                // NB: the per-language view binds via getStatePath() (the separate
                // `statePath` property), NOT getName(), so we MUST set statePath() —
                // name() alone does not change where the field reads/writes state.
                $translatablePath = str_replace('options.', 'translatableOptions.', $this->getName());

                return $this
                    ->name($translatablePath)
                    ->statePath($translatablePath)
                    ->live(debounce: 300)
                    ->view($view, [
                        'supportedLanguages' => $supportedLanguages,
                    ]);
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
