<?php
namespace MicroweberPackages\Multilanguage\FormElements\Deprecated;

class MwEditor extends \MicroweberPackages\Form\Elements\TextArea
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
                    $html .= '<a class="btn btn-outline-secondary btn-sm justify-content-center '.$showTab.'" data-toggle="tab" href="#' . $this->randId . $language['locale'] . '">'.$flagIcon.'</a>';
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

                        $html .= '<div class="tab-pane fade '.$showTab.'" id="' . $this->randId . $language['locale'] . '">
                                   <textarea id="js-multilanguage-mw-editor-' . $this->randId . $language['locale'] . '" name="multilanguage['.$fieldName.']['.$language['locale'].']" onchange="applyMlFieldChanges(this)" lang="'.$language['locale'] . '" class="form-control">'.$textareaValue . '</textarea>
                                   </div>';
                    }

                    $html .= '
                    <script>
                        function applyMlFieldChanges(element) {

                            if (element.getAttribute("lang") == "'. $this->defaultLanguage .'") {
                                var applyToElement = document.getElementById("js-multilanguage-textarea-' . $this->randId . '");
                                applyToElement.innerHTML = element.value

                                var changeEvent = new Event("change");
                                applyToElement.dispatchEvent(changeEvent);
                            }
                        }
                   </script>
                    ';

                    foreach($supportedLanguages as $language) {

                        $mwEditorId = $this->randId;
                        $mwEditorTextareaId = "#js-multilanguage-mw-editor-" . $this->randId . $language['locale'];

                        $html .= "<script>
                                mw.require('editor.js');
                                $(mwd).ready(function () {
                                mweditor$mwEditorId = mw.Editor({
                                selector: '$mwEditorTextareaId',
                                mode: 'div',
                                smallEditor: false,
                                minHeight: 250,
                                maxHeight: '70vh',
                                controls: [
                                    [
                                        'undoRedo', '|', 'image', '|',
                                        {
                                            group: {
                                                controller: 'bold',
                                                controls: ['italic', 'underline', 'strikeThrough']
                                            }
                                        },
                                        '|',
                                        {
                                            group: {
                                                icon: 'mdi mdi-format-align-left',
                                                controls: ['align']
                                            }
                                        },
                                        '|', 'format',
                                        {
                                            group: {
                                                icon: 'mdi mdi-format-list-bulleted-square',
                                                controls: ['ul', 'ol']
                                            }
                                        },
                                        '|', 'link', 'unlink', 'wordPaste', 'table', 'removeFormat'
                                    ],
                                ]
                            });
                            });
                            </script>";
                    }

                    $this->id('js-multilanguage-textarea-' . $this->randId);

                    $html .= '<textarea id="'.$this->getAttribute('id').'" name="'.$this->getAttribute('name').'" style="display:none">'.$this->escape($this->value).'</textarea>';
                    $html .= '
                    </div>
                  </div>';

        return $html;
    }

}
