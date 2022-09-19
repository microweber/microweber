<?php

namespace MicroweberPackages\Shop\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Product\Repositories\ProductRepository;

class AdminShopController extends AdminController
{

    public function dashboard(Request $request)
    {
        return $this->view('shop::admin.dashboard');
    }


}
