<?php

namespace MicroweberPackages\Modules\Pdf\Http\Controllers;

use Illuminate\Http\Request;

class PdfLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-pdf::live-edit.settings',['moduleId' => $params['id']]);
    }
}
