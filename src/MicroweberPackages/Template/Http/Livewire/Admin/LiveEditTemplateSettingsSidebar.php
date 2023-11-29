<?php

namespace MicroweberPackages\Template\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class LiveEditTemplateSettingsSidebar extends AdminComponent
{

    public $styleSheetSourceFile;
    public $settingsGroups;
    public $options;
    public $optionGroup;
    public $optionGroupLess;

    public $styleSettings = [];

    public function getSettings()
    {
        $getTemplateConfig = mw()->template->get_config();

        $optionGroup = 'mw-template-' . $getTemplateConfig['dir_name'] . '-settings';
        $optionGroupLess = 'mw-template-' . $getTemplateConfig['dir_name'];

        $options = [];
        $settingGroups = [];

        if (isset($getTemplateConfig['stylesheet_compiler']['settings'])) {

            $mainGroup = 'Styles';
            $valuesGroup = 'Other';
            foreach ($getTemplateConfig['stylesheet_compiler']['settings'] as $key => $value) {

                if ($value['type'] == 'delimiter') {
                    continue;
                }
                if ($value['type'] == 'group') {
                    $mainGroup = $value['label'];
                    continue;
                }
                if ($value['type'] == 'title') {
                    $valuesGroup = $value['label'];
                    continue;
                }

                $value['optionGroup'] = $optionGroupLess;
                $value['value'] = get_option($key, $optionGroupLess);
                if (empty($value['value']) && isset($value['default'])) {
                    $value['value'] = $value['default'];
                }

                $options[$optionGroupLess][$key] = $value['value'];

                $settingGroups[$mainGroup]['type'] = 'stylesheet';
                $settingGroups[$mainGroup]['values'][$valuesGroup][$key] = $value;
            }
        }

        if (isset($getTemplateConfig['template_settings'])) {
            $valuesGroup = 'Other';
            foreach ($getTemplateConfig['template_settings'] as $key => $value) {
                if ($value['type'] == 'delimiter') {
                    continue;
                }
                if ($value['type'] == 'title') {
                    $valuesGroup = $value['label'];
                    continue;
                }

                $value['optionGroup'] = $optionGroup;
                $value['value'] = get_option($key, $optionGroup);
                if (empty($value['value']) && isset($value['default'])) {
                    $value['value'] = $value['default'];
                }

                $options[$optionGroup][$key] = $value['value'];

                $settingGroups['Template Settings']['type'] = 'template';
                $settingGroups['Template Settings']['values'][$valuesGroup][$key] = $value;
            }
        }


        $styleSheetSourceFile = false;
        if (isset($getTemplateConfig['stylesheet_compiler']['source_file'])) {
            $styleSheetSourceFile = $getTemplateConfig['stylesheet_compiler']['source_file'];
        }

        $this->styleSheetSourceFile = $styleSheetSourceFile;
        $this->settingsGroups = $settingGroups;
        $this->options = $options;
        $this->optionGroup = $optionGroup;
        $this->optionGroupLess = $optionGroupLess;

    }

    public function mount()
    {

        $this->getSettings();

        $templateDir = template_dir();

        $getStyleSettings = app()->template->getStyleSettings($templateDir);
        if (isset($getStyleSettings['settings'])) {
            if (!empty($getStyleSettings['settings']) && is_array($getStyleSettings['settings'])) {

                $convert = new StyleSettingsFirstLevelConvertor();
                $this->styleSettings = $convert->getFirstLevelSettings($getStyleSettings['settings']);

            }
        }

    }

    public function render()
    {
        return view('template::livewire.live-edit.template-settings-sidebar');
    }
}
