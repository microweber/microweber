<?php

namespace MicroweberPackages\Modules\Breadcrumb\Http\Controllers;

use Illuminate\Http\Request;

class BreadcrumbLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-breadcrumb::live-edit.settings',['moduleId' => $params['id']]);
    }
}
