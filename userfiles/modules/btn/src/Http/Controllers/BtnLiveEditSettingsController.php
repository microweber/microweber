<?php

namespace MicroweberPackages\Modules\Btn\Http\Controllers;

use Illuminate\Http\Request;

class BtnLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();


        return view('modules.btn::live-edit.settings',['moduleId' => $params['id']]);
    }
}
