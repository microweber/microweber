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

        $locales = [];
        $supportedLanguages = get_supported_languages(true);
        foreach ($supportedLanguages as $language) {
            $locales[] = $language['locale'];
        }
        $localesJson = json_encode($locales);

        $translations = [];
        foreach ($locales as $locale) {
            $translations[$locale] = $locale . '-top';
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
        <input type=\"text\" class=\"form-control\" id=\"$this->randId\" />";

    }
}
