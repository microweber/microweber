<?php
namespace MicroweberPackages\Multilanguage\FormElements;

class MwModuleSettings extends \MicroweberPackages\Form\Elements\MwModuleSettings
{
    public $randId;
    public $currentLanguage;
    public $defaultLanguage;

    public function render()
    {
        $fieldName = $this->getAttribute('name');

        $this->currentLanguage = mw()->lang_helper->current_lang();
        $this->defaultLanguage = mw()->lang_helper->default_lang();

        $this->randId = 'ml_editor_element_'.md5(str_random());

        $schema = json_encode($this->getAttribute('schema'));

        $supportedLanguages = get_supported_languages(true);

        $modelAttributes = [];
        if ($this->model) {
            $modelAttributes = $this->model->getAttributes();
        }

        if ($this->model && method_exists($this->model, 'getTranslationsFormated')) {
            $modelAttributes['multilanguage'] = $this->model->getTranslationsFormated();
        }

        $html = '
            <script>mw.lib.require(\'flag_icons\')</script>
            <script>mw.require(\'prop_editor.js\')</script>
            <script>mw.require(\'module_settings.js\')</script>
            <script>mw.require(\'icon_selector.js\')</script>
            <script>mw.require(\'wysiwyg.css\')</script>
            <div class="bs-component">
            <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-1" dir="ltr">
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

            $inputValue = json_encode([]);
            if (isset($modelAttributes['multilanguage'])) {
                foreach ($modelAttributes['multilanguage'] as $locale => $multilanguageFields) {
                    if ($locale == $language['locale']) {
                        if (isset($multilanguageFields['option_value'])) {
                            $inputValue = $multilanguageFields['option_value']; // its hardcoded only for module options
                        }
                    }
                }
            }

            $mwModuleSettingsId = $this->randId . $language['locale'];


            if(!$inputValue or $inputValue == ''){
                $inputValue = "[]";
            }



            $html .= '<div class="tab-pane fade '.$showTab.' js-multilanguage-tab-'.$this->randId.'" id="mlfield' . $this->randId . $language['locale'] . '">

                <script>
                $(window).on(\'load\', function () {

                    var data'.$mwModuleSettingsId.' = '.$inputValue.';

                    $.each(data'.$mwModuleSettingsId.', function (key) {
                        if (typeof data'.$mwModuleSettingsId.'[key].images === \'string\') {
                            data'.$mwModuleSettingsId.'[key].images = data'.$mwModuleSettingsId.'[key].images.split(\',\');
                        }
                    });

                    this.mwModuleSettings'.$mwModuleSettingsId.' = new mw.moduleSettings({
                        element: \'#settings-box'.$mwModuleSettingsId.'\',
                        header: \'<i class="mw-icon-drag"></i> Content #{count} <b data-reflect="primaryText"></b> <a class="pull-right" data-action="remove"><i class="mdi mdi-delete"></i></a>\',
                        data: data'.$mwModuleSettingsId.',
                        key: \'settings\',
                        group: \'id\',
                        autoSave: true,
                        schema: '.$schema.'
                    });

                    $(mwModuleSettings'.$mwModuleSettingsId.').on(\'change\', function (e, val) {
                        var final = [];
                        $.each(val, function () {
                            var current = $.extend({}, this);
                            current.images = (current.images||[]).join(\',\');
                            final.push(current)
                        });
                        $(\'#settingsfield'.$mwModuleSettingsId.'\').val(JSON.stringify(final)).trigger(\'change\')
                    });
                });
                </script>

                <!-- Settings Content -->
                <div class="module-live-edit-settings module-'.$mwModuleSettingsId.'-settings">
                    <input type="hidden" name="'.$this->getAttribute('name').'" lang="'.$language['locale'].'" id="settingsfield'.$mwModuleSettingsId.'" value="" class="mw_option_field" />
                    <div class="mb-3">
                        <span class="btn btn-primary btn-rounded" onclick="mwModuleSettings'.$mwModuleSettingsId.'.addNew(0, \'blank\');"> '. _e('Add new', true) . '</span>
                    </div>
                    <div id="settings-box'.$mwModuleSettingsId.'"></div>
                </div>
                <!-- Settings Content - End -->



           </div>';
        }

        $html .= '
                    </div>
                  </div>';

        return $html;
    }
}
