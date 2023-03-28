<?php

namespace MicroweberPackages\Editor\TemplateSettingsV2\Http\Controllers;

use Illuminate\Routing\Controller;

class EditorTemplateSettingsV2Controller extends Controller
{
    public function getSettings()
    {
        $template_settings_config = mw()->template->get_config();
        $template_settings = [];
        if (isset($template_settings_config['template_settings'])) {
            $template_settings = $template_settings_config['template_settings'];
        }

        return response()->json($template_settings);
    }
}
