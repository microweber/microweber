<?php

namespace MicroweberPackages\Editor\TemplateSettingsV2\Http\Controllers;

use Illuminate\Routing\Controller;
use MicroweberPackages\Option\Models\Option;

class EditorTemplateSettingsV2Controller extends Controller
{
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

        return response()->json([
            'styleSheetSourceFile' => $styleSheetSourceFile,
            'settingsGroups' => $settingGroups,
            'options'=> $options,
            'optionGroup'=> $optionGroup,
            'optionGroupLess'=> $optionGroupLess,
        ]);
    }
}
