<?php
namespace MicroweberPackages\Multilanguage\View\Components\FormElements;

use Illuminate\View\Component;

class InputText extends Component
{

    public $randId;
    public $defaultLanguage;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $this->defaultLanguage = mw()->lang_helper->default_lang();
        $this->currentLanguage = mw()->lang_helper->current_lang();
        $this->randId = 'ml_editor_element_'.md5(str_random());
        $fieldName = 'auuu____ebasi';

        $fieldValue = '';

        $locales = [];
        $supportedLanguages = get_supported_languages(true);
        foreach ($supportedLanguages as $language) {
            $locales[] = $language['locale'];
        }
        $localesJson = json_encode($locales);

        $modelTranslations = [];


        $translations = [];
        // Fill with empty values
        foreach ($locales as $locale) {
            $translations[$locale] = '';
        }
        // Fill the translations if available
        $findTranslationsFromField = $fieldName;


        if (!empty($modelTranslations)) {
            foreach ($modelTranslations as $modelTranslationLocale=>$modelTranslation) {
                if (isset($modelTranslation[$findTranslationsFromField])) {
                    $translations[$modelTranslationLocale] = $modelTranslation[$findTranslationsFromField];
                    if ($this->currentLanguage == $modelTranslationLocale) {
                        $fieldValue = $modelTranslation[$findTranslationsFromField];
                    }
                }
            }
        }
//        $translationsJson = json_encode($translations);
//        $attributes = json_encode($this->getAttributes());

        $currentLanguageData = [];
        foreach ($supportedLanguages as $language) {
            if ($language['locale'] == $this->currentLanguage) {
                $currentLanguageData = $language;
            }
        }

        return view('multilanguage::components.form-elements.input-text', [
            'randId' => $this->randId,
            'fieldName' => $fieldName,
            'fieldValue' => $fieldValue,
            'defaultLanguage' => $this->defaultLanguage,
            'supportedLanguages' => $supportedLanguages,
            'currentLanguageData' => $currentLanguageData,
            'translations' => $translations,
        ]);
    }
}
