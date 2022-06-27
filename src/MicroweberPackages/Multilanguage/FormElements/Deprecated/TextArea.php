<?php
namespace MicroweberPackages\Multilanguage\FormElements\Deprecated;

class TextArea extends \MicroweberPackages\Form\Elements\TextArea
{
    public $randId;

    public $currentLanguage;
    public $defaultLanguage;

    public function render()
    {
        $fieldName = $this->getAttribute('name');

        $this->currentLanguage = mw()->lang_helper->current_lang();
        $this->defaultLanguage = mw()->lang_helper->default_lang();

        $this->randId = random_int(111,999).time();

        $supportedLanguages = get_supported_languages(true);

        $modelAttributes = [];
        if ($this->model) {
            $modelAttributes = $this->model->getAttributes();
        }

        if (method_exists($this->model, 'getTranslationsFormated')) {
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
                    $html .= '<a class="btn btn-outline-secondary btn-sm justify-content-center '.$showTab.'" data-bs-toggle="tab" href="#mlfield' . $this->randId . $language['locale'] . '">'.$flagIcon.'</a>';
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
                                    if (isset($multilanguageFields[$fieldName])) {
                                        $textareaValue = $multilanguageFields[$fieldName];
                                    }
                                }
                            }
                        }

                        $html .= '<div class="tab-pane fade '.$showTab.' js-multilanguage-tab-'.$this->randId.'" id="mlfield' . $this->randId . $language['locale'] . '">
                                   <textarea name="multilanguage['.$fieldName.']['.$language['locale'].']" onchange="applyMlFieldChanges(this)" lang="'.$language['locale'] . '" class="form-control">'.$textareaValue . '</textarea>
                                   </div>';
                    }

                    $html .= '
                    <script>
                        function applyMlFieldChanges(element) {
                             if (element.getAttribute("lang") == "'. $this->defaultLanguage .'") {
                                var applyToElement = document.getElementById("js-multilanguage-textarea-' . $this->randId . '");
                                applyToElement.value = element.value;
                                applyToElement.setAttribute("lang", element.getAttribute("lang"));

                                var changeEvent = new Event("change");
                                applyToElement.dispatchEvent(changeEvent);
                            }
                        }
                        $(document).ready(function() {

                            var tabElement = document.querySelector(".js-multilanguage-tab-'.$this->randId.'");
                            tabElement.addEventListener("shown.bs.tab", function (event) {
                                alert(87777);
                            });

                             mw.on("mlChangedLanguage", function (e, data) {
                                var triggerEl = document.getElementById("mlfield' . $this->randId . '" + data)


                             });
                         });
                   </script>
                    ';

                    $this->id('js-multilanguage-textarea-' . $this->randId);

                    $html .= '<textarea id="'.$this->getAttribute('id').'" name="'.$this->getAttribute('name').'" style="display:none">'.$this->escape($this->value).'</textarea>';
                    $html .= '
                    </div>
                  </div>';

        return $html;
    }

}
