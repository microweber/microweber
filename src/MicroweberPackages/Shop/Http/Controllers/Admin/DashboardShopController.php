<?php

namespace MicroweberPackages\Shop\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Product\Repositories\ProductRepository;

class DashboardShopController extends AdminController
{
    public function dashboard(Request $request)
    {
        $data = [];
        return $this->view('shop::admin.dashboard', ['data' => $data]);
    }
}
