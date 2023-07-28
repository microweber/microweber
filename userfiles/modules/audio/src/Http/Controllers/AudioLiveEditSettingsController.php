<?php

namespace MicroweberPackages\Modules\Audio\Http\Controllers;

use Illuminate\Http\Request;

class AudioLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-audio::live-edit.settings',['moduleId' => $params['id']]);
    }
}
