<?php

namespace MicroweberPackages\Modules\UnlockPackage\Http\Controllers;

use Illuminate\Http\Request;

class UnlockPackageLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-unlock-package::live-edit.settings',['moduleId' => $params['id']]);
    }
}
