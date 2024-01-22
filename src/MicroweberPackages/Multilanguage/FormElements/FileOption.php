<?php

namespace MicroweberPackages\Multilanguage\FormElements;

class FileOption extends \MicroweberPackages\FormBuilder\Elements\TextAreaOption
{
    public $randId;

    public $currentLanguage;
    public $defaultLanguage;

    public function render()
    {
        $this->currentLanguage = mw()->lang_helper->current_lang();
        $this->defaultLanguage = mw()->lang_helper->default_lang();

        $this->randId = 'ml_editor_element_' . md5(str_random());

        $supportedLanguages = get_supported_languages(true);

        $modelAttributes = [];
        if ($this->model) {
            $modelAttributes = $this->model->getAttributes();
        }

        if ($this->model && method_exists($this->model, 'getTranslationsFormated')) {
            $modelAttributes['multilanguage'] = $this->model->getTranslationsFormated();
        }

        $this->id('js-multilanguage-fileo-option-' . $this->randId);

        $renderAttributes = $this->renderAttributes();

        $html = view('multilanguage::admin.form-elements.file-option', [
            'randId' => $this->randId,
            'defaultLanguage' => $this->defaultLanguage,
            'supportedLanguages' => $supportedLanguages,
            'currentLanguage' => $this->currentLanguage,
            'renderAttributes' => $renderAttributes,
            'optionKey' => $this->optionKey,
            'optionGroup' => $this->optionGroup,
        ]);

        return $html->render();
    }

}
