<?php

namespace MicroweberPackages\Modules\Settings\Http\Controllers\Admin;

use Illuminate\Http\Request;

class SettingsController
{
    public function index(Request $request)
    {
        return view('microweber-module-settings::admin.index',[

        ]);
    }
}
