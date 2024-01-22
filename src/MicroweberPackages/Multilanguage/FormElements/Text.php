<?php

namespace MicroweberPackages\Multilanguage\FormElements;

class Text extends \MicroweberPackages\FormBuilder\Elements\Text
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

        $currentLanguageData = [];
        foreach ($supportedLanguages as $language) {
            if ($language['locale'] == $this->currentLanguage) {
                $currentLanguageData = $language;
            }
        }

//        return view('multilanguage::admin.form-elements.input-text', [
//            'randId' => $this->randId,
//            'fieldName' => $fieldName,
//            'fieldValue' => $fieldValue,
//            'defaultLanguage' => $this->defaultLanguage,
//            'supportedLanguages' => $supportedLanguages,
//            'currentLanguageData' => $currentLanguageData,
//            'translations' => $translations,
//        ]);

        $html = '';
        if (isset($this->prepend) and $this->prepend) {
            //temp fix
   //         $html .= $this->prepend;
        }

        $html .= "<script>
            mw.lib.require('multilanguage');
            window.initMlInput$this->randId = function() {

                if( window.initMlInputInit$this->randId ){
                    return;
                }
                 window.initMlInputInit$this->randId = true;

                $('#$this->randId').mlInput({
                        name: '$fieldName',
                        currentLocale: '$this->currentLanguage',
                        defaultLocale: '$this->defaultLanguage',
                        locales: $localesJson,
                        attributes: $attributes,
                        translations: $translationsJson,
                    });
            }
            window.addEventListener('load',
                  function() {
                    window.initMlInput$this->randId();

                  }, false);


            $(document).ready(function () {
                window.initMlInput$this->randId();
            });
        </script>

        <input type=\"text\" name=\"$fieldName\" value=\"$fieldValue\" class=\"form-control\" id=\"$this->randId\" "." />";

        if (isset($this->append) and $this->append) {
            //temp fix
       //     $html .= $this->append;
        }

        return $html;

    }
}
