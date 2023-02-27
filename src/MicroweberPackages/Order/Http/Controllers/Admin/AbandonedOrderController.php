<?php

namespace MicroweberPackages\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Cart\Models\Cart;
use MicroweberPackages\Order\Models\Order;

class AbandonedOrderController extends AdminController
{
    public $pageLimit = 15;

    public function abandoned(Request $request)
    {
        $filteringResults = false;

        $orderBy = $request->get('orderBy', 'id');
        $orderDirection = $request->get('orderDirection', 'desc');
        $priceBetween = $request->get('priceBetween', false);

        $keyword = $request->get('keyword', '');
        if (!empty($keyword)) {
            $filteringResults = true;
        }

        $orders = Cart::filter($request->all())
            ->where('order_completed', '=', '0')
            ->groupBy('session_id')
            ->paginate($request->get('limit', $this->pageLimit))
            ->appends($request->except('page'));


        return $this->view('order::admin.orders.abandoned', [
            'abandoned'=>true,
            'priceBetween'=>$priceBetween,
            'orderBy'=>$orderBy,
            'orderDirection'=>$orderDirection,
            'filteringResults'=>$filteringResults,
            'keyword'=>$keyword,
            'orders'=>$orders,
            'abandoned_count'=>Cart::where('order_completed', '=', '0')->groupBy('session_id')->count(),
            'orders_count'=>Cart::where('order_completed', 1)->count()
        ]);
    }

}
