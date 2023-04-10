<?php

namespace MicroweberPackages\LiveEdit\Http\Controllers;

use Illuminate\Http\Request;


class ModuleSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();
        $type = module_name_decode($params['type']);
        $id = $params['id'];

        return view('live_edit::module_settings', ['moduleId' => $id, 'moduleType' => $type]);
    }
}
