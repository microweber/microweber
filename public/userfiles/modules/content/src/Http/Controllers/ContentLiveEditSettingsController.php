<?php

namespace MicroweberPackages\Modules\Content\Http\Controllers;

use Illuminate\Http\Request;

class ContentLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-content::live-edit.settings',['moduleId' => $params['id']]);
    }
}
