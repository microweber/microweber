<?php

namespace MicroweberPackages\Modules\PDF\Http\Controllers;

use Illuminate\Http\Request;

class PDFLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-pdf::live-edit.settings',['moduleId' => $params['id']]);
    }
}
