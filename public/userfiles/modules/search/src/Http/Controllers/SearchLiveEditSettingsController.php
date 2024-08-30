<?php

namespace MicroweberPackages\Modules\Search\Http\Controllers;

use Illuminate\Http\Request;

class SearchLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-search::live-edit.settings',['moduleId' => $params['id']]);
    }
}
