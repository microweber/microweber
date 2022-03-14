<?php

namespace MicroweberPackages\Option\Http\Controllers\Api;

use Illuminate\Http\Request;
use MicroweberPackages\Helper\HTMLClean;

class SaveOptionApiController
{
    public $whitelistedGroupKeys = [
        'website' => [
            'website_head',
            'website_footer'
        ]
    ];

    public function saveOption(Request $request)
    {
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

        if ($cleanFromXss) {
            $clean = new HTMLClean();
            $option = $clean->cleanArray($option);
        }

        return save_option($option);
    }

}
