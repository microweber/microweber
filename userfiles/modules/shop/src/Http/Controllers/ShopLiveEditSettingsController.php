<?php

namespace MicroweberPackages\Modules\Shop\Http\Controllers;

use Illuminate\Http\Request;

class ShopLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-shop::live-edit.settings',['moduleId' => $params['id']]);
    }
}
