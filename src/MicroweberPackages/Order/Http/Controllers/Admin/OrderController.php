<?php

namespace MicroweberPackages\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\App\Http\Controllers\AdminController;
use MicroweberPackages\Order\Models\Order;

class OrderController extends AdminController
{
    public function index(Request $request)
    {

        $keyword = $request->get('keyword', '');

        $orders = Order::filter($request->all())->paginate($request->get('limit', 1))->appends($request->except('page'));

        return $this->view('order::admin.orders.index', ['keyword'=>$keyword, 'orders'=>$orders]);
    }
}