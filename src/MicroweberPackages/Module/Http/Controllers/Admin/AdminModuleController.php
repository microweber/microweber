<?php

namespace MicroweberPackages\Module\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminModuleController
{
    public function index(Request $request)
    {
        return view('module::admin.index');
    }

    public function view(Request $request)
    {
        $type = $request->get('type', false);

        return view('module::admin.view', [
            'type' => $type,
        ]);

    }
}
