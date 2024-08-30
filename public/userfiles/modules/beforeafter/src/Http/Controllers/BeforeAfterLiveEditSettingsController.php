<?php

namespace MicroweberPackages\Modules\BeforeAfter\Http\Controllers;

use Illuminate\Http\Request;

class BeforeAfterLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-before-after::live-edit.settings',['moduleId' => $params['id']]);
    }
}
