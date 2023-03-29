<?php

namespace MicroweberPackages\Editor\TemplateSettingsV2\Http\Controllers;

use Illuminate\Routing\Controller;

class EditorTemplateSettingsV2Controller extends Controller
{
    public function getSettings()
    {
        $getTemplateConfig = mw()->template->get_config();

        $settingGroups = [];

        if (isset($getTemplateConfig['stylesheet_compiler']['settings'])) {

            $mainGroup = 'Other';
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

                $settingGroups[$mainGroup][$valuesGroup][$key] = $value;
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

                $settingGroups['Template Settings'][$valuesGroup][$key] = $value;
            }
        }

        $optionGroup = 'mw-template-' . $getTemplateConfig['dir_name'] . '-settings';
        
        return response()->json([
            'settingsGroups' => $settingGroups,
            'optionGroup'=> $optionGroup
        ]);
    }
}
