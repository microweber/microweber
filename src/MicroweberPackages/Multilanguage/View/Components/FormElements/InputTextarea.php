<?php
namespace MicroweberPackages\Multilanguage\View\Components\FormElements;

use Illuminate\View\Component;

class InputTextarea extends Component
{

    public $randId;
    public $defaultLanguage;
    public $wireModelName = 'input_element_name';
    public $wireModelDefer = 1;
    public $labelText = '';

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($labelText = '', $wireModelName = '', $wireModelDefer = '')
    {
        $this->labelText = $labelText;
        $this->wireModelName = $wireModelName;
        $this->wireModelDefer = $wireModelDefer;
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

        $fieldName = $this->wireModelName;

        $supportedLanguages = get_supported_languages(true);

        $currentLanguageData = [];
        foreach ($supportedLanguages as $language) {
            if ($language['locale'] == $this->currentLanguage) {
                $currentLanguageData = $language;
            }
        }

        return view('multilanguage::components.form-elements.input-textarea-native', [
            'randId' => $this->randId,
            'fieldName' => $fieldName,
            'defaultLanguage' => $this->defaultLanguage,
            'supportedLanguages' => $supportedLanguages,
            'currentLanguageData' => $currentLanguageData,
            'wireModelName' => $this->wireModelName,
            'wireModelDefer' => $this->wireModelDefer,
            'labelText' => $this->labelText,
        ]);
    }
}
