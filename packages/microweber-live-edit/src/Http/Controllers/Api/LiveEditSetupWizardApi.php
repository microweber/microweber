<?php

namespace MicroweberPackages\LiveEdit\Http\Controllers\Api;

use MicroweberPackages\App\Http\Controllers\Controller;
use MicroweberPackages\LiveEdit\Facades\LiveEditManager;

class LiveEditSetupWizardApi extends Controller
{    public function getWebsiteInfo()
    {
        $website_title = get_option('website_title', 'website');
        $website_description = get_option('website_description', 'website');
        $website_keywords = get_option('website_keywords', 'website');
        $brand_personality = get_option('brand_personality', 'website');

        $website_info = [
            'title' => $website_title,
            'description' => $website_description,
            'keywords' => $website_keywords,
            'brand_personality' => $brand_personality ?: 'Professional',
        ];

        return response()->json($website_info);
    }
}
