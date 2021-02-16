<?php

namespace MicroweberPackages\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\App\Http\Controllers\AdminController;
use MicroweberPackages\Order\Models\Order;

class OrderController extends AdminController
{
    public function index(Request $request)
    {

        $filteringResults = false;

        $keyword = $request->get('keyword', '');

        if (!empty($keyword)) {
            $filteringResults= true;
        }


        $orders = Order::filter($request->all())->paginate($request->get('limit', 1))->appends($request->except('page'));

        return $this->view('order::admin.orders.index', ['filteringResults'=>$filteringResults, 'keyword'=>$keyword, 'orders'=>$orders]);
    }
}