<?php

namespace MicroweberPackages\Editor\TemplateSettingsV2\Http\Controllers;

use Illuminate\Routing\Controller;

class EditorTemplateSettingsV2Controller extends Controller
{
    public function getSettings()
    {
        $getTemplateConfig = mw()->template->get_config();
        $settings = [];
        if (isset($getTemplateConfig['template_settings'])) {
            foreach ($getTemplateConfig['template_settings'] as $key => $value) {
                if (is_numeric($key)) {
                    $key = $value['type'] . '_' . $key;
                }
                $settings[$key] = $value;
            }
        }

        $optionGroup = 'mw-template-' . $getTemplateConfig['dir_name'] . '-settings';

        return response()->json([
            'settings' => $settings,
            'optionGroup'=> $optionGroup
        ]);
    }
}
