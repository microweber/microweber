<?php

namespace MicroweberPackages\Modules\Faq\Http\Controllers;

use Illuminate\Http\Request;

class FaqLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-faq::live-edit.settings',['moduleId' => $params['id']]);
    }
}
