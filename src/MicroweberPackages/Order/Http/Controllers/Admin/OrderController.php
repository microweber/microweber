<?php

namespace MicroweberPackages\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\App\Http\Controllers\AdminController;

class OrderController extends AdminController
{
    public function index(Request $request)
    {


        return $this->view('order::admin.orders.index');

    }
}