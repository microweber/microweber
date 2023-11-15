<?php

namespace MicroweberPackages\Modules\CallToAction\Http\Controllers;

use Illuminate\Http\Request;

class CallToActionLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-call-to-action::live-edit.settings',['moduleId' => $params['id']]);
    }
}
