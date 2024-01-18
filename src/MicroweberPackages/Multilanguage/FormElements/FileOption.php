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

        $this->randId = 'ml_editor_element_'.md5(str_random());

        $supportedLanguages = get_supported_languages(true);

        $modelAttributes = [];
        if ($this->model) {
            $modelAttributes = $this->model->getAttributes();
        }

        if ($this->model && method_exists($this->model, 'getTranslationsFormated')) {
            $modelAttributes['multilanguage'] = $this->model->getTranslationsFormated();
        }

        $html = ' <div class="bs-component">
                <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-1">
                ';

                foreach($supportedLanguages as $language) {

                    $showTab= '';
                    if ($this->currentLanguage == $language['locale']) {
                        $showTab = 'active';
                    }

                    $langData = \MicroweberPackages\Translation\LanguageHelper::getLangData($language['locale']);
                    $flagIcon = "<i class='flag-icon flag-icon-".$language['icon']."'></i> " . strtoupper($langData['language']);
                    $html .= '<a class="btn btn-outline-secondary btn-sm justify-content-center '.$showTab.'" data-bs-toggle="tab" href="#' . $this->randId . $language['locale'] . '">'.$flagIcon.'</a>';
                }

                $html .='</nav>
                <div id="js-multilanguage-tab-'.$this->randId.'" class="tab-content py-3">
                ';
                    foreach($supportedLanguages as $language) {
                        $showTab= '';
                        if ($this->currentLanguage == $language['locale']) {
                            $showTab = 'show active';
                        }

                        $textareaValue = '';

                        if (isset($modelAttributes['multilanguage'])) {
                            foreach ($modelAttributes['multilanguage'] as $locale => $multilanguageFields) {
                                if ($locale == $language['locale']) {
                                    if (isset($multilanguageFields['option_value'])) {
                                        $textareaValue = $multilanguageFields['option_value'];
                                    }
                                }
                            }
                        }

                        $html .= '<div class="tab-pane fade '.$showTab.'" id="' . $this->randId . $language['locale'] . '">


                                    <div class="form-group mb-4 p-4" style="background:#fff;">
                                        <label class="form-label">
                                            Atachments
                                        </label>
                                        <small class="text-muted d-block mb-2">
                                        You can attach a file to this field.
                                        </small>
                                        <button type="button" id="mw_uploader" lang="'.$language['locale'] . '" class="btn btn-sm btn-outline-primary">
                                        Upload file <span id="upload_info"></span>
                                        </button>
                                    </div>


                                   </div>';
                    }

                    $html .= '
                      <script type="text/javascript">

                        $(document).ready(function () {
                             var uploader = mw.uploader({
                                filetypes: "images,videos",
                                multiple: false,
                                element: "#mw_uploader"
                            });
                        });

                        function applyMlFieldChanges(element) {
                            var applyToElement = document.getElementById("js-multilanguage-textarea-' . $this->randId . '");
                            applyToElement.value = element.value
                            applyToElement.setAttribute("lang", element.getAttribute("lang"));

                            var changeEvent = new Event("change");
                            applyToElement.dispatchEvent(changeEvent);
                        }
                   </script>
                    ';

                    $this->id('js-multilanguage-textarea-' . $this->randId);

                    $html .= '

                    <textarea '.$this->renderAttributes().' style="display:none"></textarea>
                    ';
                    $html .= '
                    </div>
                  </div>';

        return $html;
    }

}
