<?php

namespace Modules\Multilanguage\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Multilanguage\Filament\MultilanguageSettings;

class MultilanguageModule extends BaseModule
{
    public static string $name = 'Multilanguage';
    public static string $module = 'multilanguage';
    public static string $icon = 'modules.multilanguage-icon';
    public static string $categories = 'miscellaneous';
    public static int $position = 99;
    public static string $settingsComponent = MultilanguageSettings::class;
    public static string $templatesNamespace = 'modules.multilanguage::templates';

    public function render()
    {
        $viewData = $this->getViewData();
        
        // Get supported languages
        $supportedLanguages = get_supported_languages(true);
        $viewData['supported_languages'] = $supportedLanguages;

        // Current language
        $currentLanguageAbr = mw()->lang_helper->current_lang();
        $currentLanguage = [
            'locale' => $currentLanguageAbr,
            'language' => $currentLanguageAbr,
            'icon' => get_flag_icon($currentLanguageAbr)
        ];

        // Current language full text
        $langs = mw()->lang_helper->get_all_lang_codes();
        if (isset($langs[$currentLanguageAbr])) {
            $currentLanguage['language'] = $langs[$currentLanguageAbr];
        }

        // Get display name and icon
        $currentLanguage['display_name'] = '';
        $currentLanguage['display_icon'] = '';
        $detailsForLocale = get_supported_locale_by_locale($currentLanguage['locale']);
        if ($detailsForLocale) {
            $currentLanguage['display_name'] = $detailsForLocale['display_name'];
            $currentLanguage['display_icon'] = $detailsForLocale['display_icon'];
        }

        $viewData['current_language'] = $currentLanguage;

        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
