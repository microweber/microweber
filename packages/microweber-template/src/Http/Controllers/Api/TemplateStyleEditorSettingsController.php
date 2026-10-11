<?php

namespace MicroweberPackages\Template\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Template\Http\Livewire\Admin\LiveEditTemplateSettingsSidebar;
use MicroweberPackages\Template\Http\Livewire\Admin\StyleSettingsFirstLevelConvertor;
use Modules\Backup\SessionStepper;
use MicroweberPackages\Template\TemplateInstaller;
use MicroweberPackages\Zip\Unzip;

class TemplateStyleEditorSettingsController
{

    public function templateStyleSettings()
    {
        $getTemplateConfig = app()->template_manager->get_config();
        if (!is_array($getTemplateConfig)) {
            // Active template directory is missing or its config
            // couldn't be parsed (e.g. options.current_template points
            // at a theme that has since been uninstalled). Return an
            // empty-but-well-formed payload so the Vue sidebar simply
            // renders nothing — throwing an ErrorException on the
            // array-offset access below would break the whole
            // live-edit sidebar, not just the style picker.
            $getTemplateConfig = [];
        }

        $templateDirName = isset($getTemplateConfig['dir_name'])
            ? (string)$getTemplateConfig['dir_name']
            : '';
        $optionGroup = 'mw-template-' . $templateDirName . '-settings';
        $optionGroupLess = 'mw-template-' . $templateDirName;

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

        $templateDir = template_dir();

        $getStyleSettings = app()->template_manager->getStyleSettings($templateDir);


        if (!$getStyleSettings) {
            // check if we are in child template
            $parentTemplate = app()->template_manager->getParentTemplate();
            if ($parentTemplate) {


                if ($parentTemplate) {
                    $templateDir = templates_dir() . $parentTemplate . '/';
                    if (is_dir($templateDir)) {
                        $getStyleSettings = app()->template_manager->getStyleSettings($templateDir);
                    }

                }
            }
        }

        $styleSettingsVars = [];
        if (isset($getStyleSettings['settings'])) {
            if (!empty($getStyleSettings['settings']) && is_array($getStyleSettings['settings'])) {

                $convert = new StyleSettingsFirstLevelConvertor();
                $styleSettingsVars = $convert->getFirstLevelSettings($getStyleSettings['settings']);


            }
        }

        return response()->json([
            'styleSheetSourceFile' => $styleSheetSourceFile,
            'settingsGroups' => $settingGroups,
            'options' => $options,
            'optionGroup' => $optionGroup,
            'optionGroupLess' => $optionGroupLess,
            'styleSettingsVars' => $styleSettingsVars,
        ]);
    }


}
