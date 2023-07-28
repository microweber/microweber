<?php

namespace MicroweberPackages\Modules\Tabs\Http\Controllers;

use Illuminate\Http\Request;

class TabsLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-tabs::live-edit.settings',['moduleId' => $params['id']]);
    }
}
