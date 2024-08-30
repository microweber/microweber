<?php

namespace MicroweberPackages\Modules\Shop\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Page\Models\Page;

class LiveEditAdminController extends \MicroweberPackages\Shop\Http\Controllers\LiveEditAdminController
{

    public function index(Request $request)
    {
        $moduleId = $request->get('id');

        $shopIsEnabled = false;
        if (get_option('shop_disabled','website') == 'n') {
            $shopIsEnabled = true;
        }
        
        if (!$shopIsEnabled) {
            return view('microweber-module-shop::admin.enable_shop');
        }

        return view('microweber-module-shop::admin.live_edit', [
            'moduleId'=>$moduleId,
        ]);
    }
}
