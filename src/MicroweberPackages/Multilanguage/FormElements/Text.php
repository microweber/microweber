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
        $this->randId = 'ml_editor_element_'.md5(str_random());
        $fieldName = $this->getAttribute('name');

        $fieldValue = '';
        if ($this->readValueFromField) {
            if (isset($this->model->{$this->readValueFromField})) {
                $fieldValue = $this->model->{$this->readValueFromField};
            }
        } else {
            if (isset($this->model->{$fieldName})) {
                $fieldValue = $this->model->{$fieldName};
            }
        }

        $locales = [];
        $supportedLanguages = get_supported_languages(true);
        foreach ($supportedLanguages as $language) {
            $locales[] = $language['locale'];
        }
        $localesJson = json_encode($locales);

        $modelTranslations = [];
        if ($this->model && method_exists($this->model, 'getTranslationsFormated')) {
            $modelTranslations = $this->model->getTranslationsFormated();
        }

        $translations = [];
        // Fill with empty values
        foreach ($locales as $locale) {
            $translations[$locale] = '';
        }
        // Fill the translations if available
        $findTranslationsFromField = $fieldName;
        if ($this->readValueFromField) {
            $findTranslationsFromField = $this->readValueFromField;
        }

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
        $translationsJson = json_encode($translations);
        $attributes = json_encode($this->getAttributes());

        return "<script>
            mw.lib.require('multilanguage');
            $(document).ready(function () {
                $('#$this->randId').mlInput({
                    name: '$fieldName',
                    currentLocale: '$this->currentLanguage',
                    locales: $localesJson,
                    attributes: $attributes,
                    translations: $translationsJson,
                });
            });
        </script>

        <input type=\"text\" name=\"$fieldName\" value=\"$fieldValue\" class=\"form-control\" id=\"$this->randId\" "." />";

    }
}
