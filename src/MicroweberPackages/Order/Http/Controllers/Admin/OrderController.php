<?php

namespace MicroweberPackages\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Cart\Models\Cart;
use MicroweberPackages\Export\Formats\XlsxExport;
use MicroweberPackages\Order\Models\Order;

class OrderController extends AdminController
{
    public $pageLimit = 15;

    public function index(Request $request)
    {
        $orderBy = $request->get('orderBy', 'id');
        $productId = $request->get('productId', false);
        $productKeyword = $request->get('productKeyword', false);
        $orderDirection = $request->get('orderDirection', 'desc');
        $orderStatus = $request->get('orderStatus', false);
        $isPaid = $request->get('isPaid', '');

        $minPrice = $request->get('minPrice', false);
        $maxPrice = $request->get('maxPrice', false);

        $minDate = $request->get('minDate', false);
        $maxDate = $request->get('maxDate', false);

        $id = $request->get('id', false);

        $exportResults = $request->get('exportResults', false);
        $filteringResults = $request->get('filteringResults', false);

        $keyword = $request->get('keyword', '');
        if (!empty($keyword)) {
            $keyword = trim($keyword);
            $filteringResults = true;
        }

        $filterFields = $request->all();
        if ($maxPrice) {
            $filterFields['priceBetween'] = $minPrice . ',' . $maxPrice;
        }

        if ($minDate || $maxDate) {
            $filterFields['dateBetween'] = $minDate . ',' . $maxDate;
        }

        if (!isset($filterFields['orderBy'])) {
            $filterFields['orderBy'] = 'created_at';
            $filterFields['orderDirection'] = 'desc';
        }

        $ordersQuery = Order::filter($filterFields);

        if ($exportResults) {
            $orders = $ordersQuery->get();

            $exportExcel = new XlsxExport();
            $exportExcel->data['mw_export_orders_' . date('Y-m-d-H-i-s')] = $orders->toArray();
            $exportExcel = $exportExcel->start();
            $exportExcelFile = $exportExcel['files']['0']['filepath'];

            return response()->download($exportExcelFile);

        } else {
            $orders = $ordersQuery
                ->paginate($request->get('limit', $this->pageLimit))
                ->appends($request->except('page'));
        }


/*
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
        }*/

        $exportUrl = $request->fullUrlWithQuery(['exportResults'=>true]);

        return $this->view('order::admin.orders.index', [
            'id'=>$id,
            'productId'=>$productId,
            'productKeyword'=>$productKeyword,
            'orderStatus'=>$orderStatus,
            'isPaid'=>$isPaid,
            'exportUrl'=>$exportUrl,
            'orderBy'=>$orderBy,
            'minPrice'=>$minPrice,
            'maxPrice'=>$maxPrice,
            'minDate'=>$minDate,
            'maxDate'=>$maxDate,
            'orderDirection'=>$orderDirection,
            'filteringResults'=>$filteringResults,
            'keyword'=>$keyword,
            'orders'=>$orders,
            'abandoned_count'=>Cart::where('order_completed', '=', '0')->groupBy('session_id')->count(),
            'orders_count'=>Cart::where('order_completed', 1)->count()
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
            'orders'=>$orders,
            'abandoned_count'=>Cart::where('order_completed', '=', '0')->groupBy('session_id')->count(),
            'orders_count'=>Cart::where('order_completed', 1)->count()
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
