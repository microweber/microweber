<?php

namespace MicroweberPackages\Modules\Posts\Http\Controllers;

use Illuminate\Http\Request;

class PostsLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-posts::live-edit.settings',['moduleId' => $params['id']]);
    }
}
