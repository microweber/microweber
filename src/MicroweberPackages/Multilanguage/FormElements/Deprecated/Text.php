<?php
namespace MicroweberPackages\Multilanguage\FormElements\Deprecated;

use MicroweberPackages\Multilanguage\FormElements\Traits\JavascriptOptionChangerTrait;

class Text extends \MicroweberPackages\Form\Elements\Text
{
    public $randId;
    public $defaultLanguage;

    public function render()
    {
        $this->defaultLanguage = mw()->lang_helper->default_lang();
        $this->currentLanguage = mw()->lang_helper->current_lang();

        $firstLanguages = [];
        $supportedLanguages = [];
        $unsortedSupportedLanguages = get_supported_languages(true);
        foreach ($unsortedSupportedLanguages as $language) {
            if ($language['locale'] == $this->currentLanguage) {
                $firstLanguages[] = $language;
            } else {
                $supportedLanguages[] = $language;
            }
        }
        $supportedLanguages = array_merge($firstLanguages, $supportedLanguages);

        $modelAttributes = [];
        if ($this->model) {
            $modelAttributes = $this->model->getAttributes();
        }

        if (method_exists($this->model, 'getTranslationsFormated')) {
            $modelAttributes['multilanguage'] = $this->model->getTranslationsFormated();
        }

        $fieldName = $this->getAttribute('name');

        $this->randId = random_int(111,999).time().rand(111,999);

        $html = '<div class="input-group">';

        if ($this->prepend) {
            $html .= $this->prepend;
        }

        $csli=0;
        foreach($supportedLanguages as $language) {

            $value = '';

            if (isset($modelAttributes['multilanguage'])) {
                foreach ($modelAttributes['multilanguage'] as $locale => $multilanguageFields) {
                    if ($locale == $language['locale']) {
                        if (isset($multilanguageFields[$fieldName])) {
                            $value = $multilanguageFields[$fieldName];
                        }
                    }
                }
            }

            $displayField = 'style="display:none;"';
            if ($csli == 0) {
                $displayField = '';
            }
            $defaultLangFieldOnChange = '';
            if ($language['locale'] == $this->defaultLanguage) {
                $defaultLangFieldOnChange = 'onChange="changeOriginalFieldValue' . $this->randId . '(this.value)"';
            }

            $html .= '<input type="text" '.$defaultLangFieldOnChange.' name="multilanguage['.$fieldName.']['.$language['locale'].']" '.$displayField.' class="js-multilanguage-value-lang-elements-'.$this->randId.' form-control" id="js-multilanguage-value-lang-'.$this->randId.'-'.$language['locale'].'"  lang="'.$language['locale'].'" value="'.$value.'">';
            $csli ++;
        }

        $originalFieldAttributes = '';
        if (isset($this->attributes['onkeyup'])) {
            $originalFieldAttributes .= ' onkeyup="'.$this->attributes['onkeyup'].'" ';
        }

        // Original field hidden
        $html .= '<input type="hidden" '.$originalFieldAttributes.' id="js-multilanguage-value-lang-original-field-'.$this->randId.'" name="'.$fieldName.'" value="'.$this->getAttribute('value').'" />';

        if ($this->append) {
            $html .= $this->append;
        }

        $html .= '
        <div class="input-group-append">
            <span>
                <select class="selectpicker" id="js-multilanguage-select-lang-'.$this->randId.'" data-width="100%">';

        foreach($supportedLanguages as $language) {
            $langData = \MicroweberPackages\Translation\LanguageHelper::getLangData($language['locale']);
            $flagIcon = "<i class='flag-icon flag-icon-".$language['icon']."'></i> " . strtoupper($langData['language']);
            $html .= '<option data-content="'.$flagIcon.'" value="'.$language['locale'].'"></option>';
        }

        $html .= '</select>
           </span>
        </div>
    </div>';


        $html .='<script>

                   function changeOriginalFieldValue' . $this->randId . '(value) {
                        var valueOriginalFieldLangElement = document.getElementById("js-multilanguage-value-lang-original-field-' . $this->randId . '");
                        valueOriginalFieldLangElement.value = value;
                    }

                    function runMlTextField' . $this->randId . '() {
                        var selectLang = document.getElementById("js-multilanguage-select-lang-' . $this->randId . '");
                        selectLang.addEventListener("change", (event) => {
                            var valueLangElements = document.getElementsByClassName("js-multilanguage-value-lang-elements-'.$this->randId.'");
                            for (var i = 0; i < valueLangElements.length; i++) {
                               valueLangElements.item(i).style.display = "none";
                            }

                            var valueLangElement = document.getElementById("js-multilanguage-value-lang-' . $this->randId . '-" + selectLang.value);
                            valueLangElement.style.display = "block";
                            mw.trigger("mlChangedLanguage", selectLang.value);
                        });
                    }
                    $(document).ready(function() {
                         mw.on("mlChangedLanguage", function (e, data) {
                            var applyChangedLang = document.getElementById("js-multilanguage-select-lang-' . $this->randId . '");
                            $(applyChangedLang).selectpicker("val", data);
                             $(\'a[data-bs-toggle="tab"][href*="\'+data+\'"]\').tab(\'show\')
                         });
                        runMlTextField' . $this->randId . '();
                    });
                </script>';

        return $html;
    }
}
