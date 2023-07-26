<?php

namespace MicroweberPackages\Modules\Slider\Http\Controllers;

use Illuminate\Http\Request;

class SliderLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-slider::live-edit.settings',['moduleId' => $params['id']]);
    }
}
