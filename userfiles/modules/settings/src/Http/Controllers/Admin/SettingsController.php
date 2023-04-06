<?php

namespace MicroweberPackages\Modules\Settings\Http\Controllers\Admin;

use Illuminate\Http\Request;

class SettingsController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        return $this->view('modules.settings::admin.index',[

        ]);
    }
}
