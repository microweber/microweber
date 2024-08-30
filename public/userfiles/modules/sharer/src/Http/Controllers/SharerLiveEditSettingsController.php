<?php

namespace MicroweberPackages\Modules\Sharer\Http\Controllers;

use Illuminate\Http\Request;

class SharerLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-sharer::live-edit.settings',['moduleId' => $params['id']]);
    }
}
