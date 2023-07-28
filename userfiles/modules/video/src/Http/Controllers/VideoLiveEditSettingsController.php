<?php

namespace MicroweberPackages\Modules\Video\Http\Controllers;

use Illuminate\Http\Request;

class VideoLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-video::live-edit.settings',['moduleId' => $params['id']]);
    }
}
