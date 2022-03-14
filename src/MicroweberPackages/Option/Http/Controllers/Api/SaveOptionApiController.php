<?php

namespace MicroweberPackages\Option\Http\Controllers\Api;

use Illuminate\Http\Request;

class SaveOptionApiController
{
    public function saveOption(Request $request) {

        $cleanFromXss = true;
        $option = $request->all();

        // Allow for this keys
        if (isset($option['option_key'])) {
            if ($option['option_key'] == 'website_head') {
                $cleanFromXss = false;
            }
            if ($option['option_key'] == 'website_footer') {
                $cleanFromXss = false;
            }
        }

        if ($cleanFromXss) {
            $clean = new HTMLClean();
            $option = $clean->cleanArray($option);
        }

        return save_option($option);
    }

}
