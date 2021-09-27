<?php

namespace MicroweberPackages\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\App\Http\Controllers\AdminController;
use MicroweberPackages\Cart\Models\Cart;
use MicroweberPackages\Order\Models\Order;

class OrderController extends AdminController
{
    public $pageLimit = 15;

    public function index(Request $request)
    {
        $orderBy = $request->get('orderBy', 'id');
        $orderDirection = $request->get('orderDirection', 'desc');
        $minPrice = $request->get('minPrice', false);
        $maxPrice = $request->get('maxPrice', false);
        $filteringResults = $request->get('filteringResults', false);

        $keyword = $request->get('keyword', '');
        if (!empty($keyword)) {
            $filteringResults = true;
        }

        $filterFields = $request->all();
        if ($maxPrice) {
            $filterFields['priceBetween'] = $minPrice . ',' . $maxPrice;
        }

        if (!isset($filterFields['orderBy'])) {
            $filterFields['orderBy'] = 'created_at';
            $filterFields['orderDirection'] = 'desc';
        }

        $orders = Order::filter($filterFields)
            ->paginate($request->get('limit', $this->pageLimit))
            ->appends($request->except('page'));


        $getMinPriceOrder = Order::select(['amount'])->orderBy('amount','asc')->first();
        if ($getMinPriceOrder !== null) {
            if (!$minPrice) {
                $minPrice = $getMinPriceOrder->amount;
            }
        }
        $getMaxnPriceOrder = Order::select(['amount'])->orderBy('amount','desc')->first();
        if ($getMaxnPriceOrder !== null) {
            if (!$maxPrice) {
                $maxPrice = $getMaxnPriceOrder->amount;
            }
        }

        return $this->view('order::admin.orders.index', [
            'orderBy'=>$orderBy,
            'minPrice'=>$minPrice,
            'maxPrice'=>$maxPrice,
            'orderDirection'=>$orderDirection,
            'filteringResults'=>$filteringResults,
            'keyword'=>$keyword,
            'orders'=>$orders
        ]);
    }

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
            'orders'=>$orders
        ]);
    }

    public function show($id)
    {
        $order = Order::where('id',$id)->first();

        if ($order == false) {
            return redirect(route('admin.order.index'));
        }

        if ($order->order_status == 'new') {
            $order->order_status = 'pending';
            $order->save();
        }

        return $this->view('order::admin.orders.show', [
            'order'=>$order
        ]);
    }
}
