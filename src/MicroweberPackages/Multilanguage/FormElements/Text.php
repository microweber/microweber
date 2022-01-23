<?php

namespace MicroweberPackages\Multilanguage\FormElements;

class Text extends \MicroweberPackages\Form\Elements\Text
{
    public $randId;
    public $defaultLanguage;

    public function render()
    {
        $this->defaultLanguage = mw()->lang_helper->default_lang();
        $this->currentLanguage = mw()->lang_helper->current_lang();
        $this->randId = str_random();
        $fieldName = $this->getAttribute('name');

        $fieldValue = '';
        if (isset($this->model->{$fieldName})) {
            $fieldValue = $this->model->{$fieldName};
        }

        $locales = [];
        $supportedLanguages = get_supported_languages(true);
        foreach ($supportedLanguages as $language) {
            $locales[] = $language['locale'];
        }
        $localesJson = json_encode($locales);

        $modelTranslations = [];
        if (method_exists($this->model, 'getTranslationsFormated')) {
            $modelTranslations = $this->model->getTranslationsFormated();
        }

        $translations = [];
        // Fill with empty values
        foreach ($locales as $locale) {
            $translations[$locale] = '';
        }
        // Fill the translations if available
        if (!empty($modelTranslations)) {
            foreach ($modelTranslations as $modelTranslationLocale=>$modelTranslation) {
                if (isset($modelTranslation[$fieldName])) {
                    $translations[$modelTranslationLocale] = $modelTranslation[$fieldName];
                    if ($this->currentLanguage == $modelTranslationLocale) {
                        $fieldValue = $modelTranslation[$fieldName];
                    }
                }
            }
        }
        $translationsJson = json_encode($translations);

        return "<script>
            mw.lib.require('multilanguage');
            $(document).ready(function () {
                $('#$this->randId').mlInput({
                    name: '$fieldName',
                    currentLocale: '$this->currentLanguage',
                    locales: $localesJson,
                    translations: $translationsJson,
                });
            });
        </script>
        <input type=\"text\" name=\"$fieldName\" value=\"$fieldValue\" class=\"form-control\" id=\"$this->randId\" />";

    }
}
