<?php

namespace MicroweberPackages\Shop\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;

class DashboardShopController extends AdminController
{
    public function dashboard(Request $request)
    {
        $data = [];
        return view('shop::admin.dashboard', ['data' => $data]);
    }
}
