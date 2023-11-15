<?php

namespace MicroweberPackages\Modules\Categories\Http\Controllers;

use Illuminate\Http\Request;

class CategoryLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-category::live-edit.settings',['moduleId' => $params['id']]);
    }
}
