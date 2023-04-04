<?php

namespace MicroweberPackages\LiveEdit\Http\Controllers;

use Illuminate\Http\Request;


class ModuleSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('live_edit::module_settings', ['moduleId' => $params['id'], 'moduleType' => $params['type']]);
    }
}
