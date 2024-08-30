<?php

namespace MicroweberPackages\Modules\Categories\CategoryImages\Http\Controllers;

use Illuminate\Http\Request;

class CategoryImagesLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-category-images::live-edit.settings',['moduleId' => $params['id']]);
    }
}
