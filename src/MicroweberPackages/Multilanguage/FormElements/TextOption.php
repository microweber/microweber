<?php
namespace MicroweberPackages\Multilanguage\FormElements;

use MicroweberPackages\Multilanguage\FormElements\Traits\JavascriptOptionChangerTrait;

class TextOption extends \MicroweberPackages\Form\Elements\TextOption
{
    protected $attributes = [
        'type' => 'text',
        'class'=>'form-control mw_option_field '
    ];

    public $randId;

    public $currentLanguage;
    public $defaultLanguage;

    use JavascriptOptionChangerTrait;

    public function render()
    {
        $inputValue = '';

        $this->currentLanguage = mw()->lang_helper->current_lang();
        $this->defaultLanguage = mw()->lang_helper->default_lang();

        $supportedLanguages = get_supported_languages(true);

        $modelAttributes = [];
        if ($this->model) {
            $modelAttributes = $this->model->getAttributes();
            $inputValue = $this->model->option_value;
            $this->setValue($inputValue);
        }

        if ($this->model && method_exists($this->model, 'getTranslationsFormated')) {
            $modelAttributes['multilanguage'] = $this->model->getTranslationsFormated();
        }

        $this->randId = random_int(111,999).time();

        $html = $this->getJavaScript();

        $html .= '<div class="input-group mb-3">

        <div class="input-group-prepend">
            <span>
                <select class="selectpicker" id="js-multilanguage-select-lang-'.$this->randId.'" data-width="100%">';

        foreach($supportedLanguages as $language) {
            $selected = '';
            if ($this->currentLanguage == $language['locale']) {
                $selected = 'selected="selected"';
            }

            $langData = \MicroweberPackages\Translation\LanguageHelper::getLangData($language['locale']);
            $flagIcon = "<i class='flag-icon flag-icon-".$language['icon']."'></i> " . strtoupper($langData['language']);
            $html .= '<option '.$selected.' data-content="'.$flagIcon.'" value="'.$language['locale'].'"></option>';
        }

        $this->lang($this->currentLanguage);

        $html .= '</select>
           </span>
        </div>
        <input type="text" '.$this->renderAttributes().' id="js-multilanguage-text-' . $this->randId . '">';

        $html .= '</div>';

        foreach($supportedLanguages as $language) {
            $value = '';
            if (isset($modelAttributes['multilanguage'])) {
                foreach ($modelAttributes['multilanguage'] as $locale => $multilanguageFields) {
                    if (isset($multilanguageFields['option_value'])) {
                        if ($locale == $language['locale']) {
                            $value = $multilanguageFields['option_value'];
                            break;
                        }
                    }
                }
            }
            $html .= '<input type="hidden" class="js-multilanguage-value-lang-'.$this->randId.'"  lang="'.$language['locale'].'" value="'.$value.'">';
        }

       return $html;
    }

}
