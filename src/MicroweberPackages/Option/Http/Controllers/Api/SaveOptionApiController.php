<?php

namespace MicroweberPackages\Option\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Helper\HTMLClean;
use MicroweberPackages\Option\Http\Requests\SaveOptionRequest;

class SaveOptionApiController
{
    public $whitelistedGroupKeys = [
        'website' => [
            'website_head',
            'website_footer'
        ],
        'template' => [
            'animations-global',
        ]
    ];

    public $whitelistedModulesKeys = [
        'video' => ['embed_url']
    ];

    public function __construct()
    {
        // Get allowed html option keys from configs
        foreach (get_modules_from_db() as $module) {
            if (isset($module['settings']['allowed_html_option_keys'])) {
                $this->whitelistedModulesKeys[$module['module']] = $module['settings']['allowed_html_option_keys'];
            }
        }
    }

    public function saveOption(Request $request)
    {
        $request->validate([
            'option_key' => 'required|max:50000',
            'option_group' => 'required|max:50000',
        ]);

        $cleanFromXss = true;
        $option = $request->all();

        // Allow for this keys and groups
        if (isset($option['option_key'])) {
            foreach ($this->whitelistedGroupKeys as $group => $keys) {
                if ($option['option_group'] == $group) {
                    foreach ($keys as $key) {
                        if ($option['option_key'] == $key) {
                            $cleanFromXss = false;
                            break;
                        }
                    }
                }
            }
        }

        // Allow for this modules and keys
        if (isset($option['option_key']) && isset($option['module'])) {
            foreach ($this->whitelistedModulesKeys as $module => $keys) {
                if ($option['module'] == $module) {
                    foreach ($keys as $key) {
                        if ($option['option_key'] == $key) {
                            $cleanFromXss = false;
                            break;
                        }
                    }
                }
            }
        }

        if ($cleanFromXss) {
//            $clean = new HTMLClean();
//            $option = $clean->cleanArray($option);
            $option = xss_clean($option);
        }

        return response()->json([
            'is_saved' => save_option($option),
        ]);
    }

}
