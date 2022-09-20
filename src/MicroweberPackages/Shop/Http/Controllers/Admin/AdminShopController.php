<?php

namespace MicroweberPackages\Shop\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Product\Repositories\ProductRepository;

class AdminShopController extends AdminController
{

    public function dashboard(Request $request)
    {
        $period = [];
        $period['from'] = Carbon::parse(strtotime('-1 year'))->format('Y-m-d') . ' 00:00:01';
        $period['to'] = Carbon::parse(strtotime('today'))->format('Y-m-d') . ' 23:59:59';

      //  $data = $this->getStatsForPeriod($period);
        $data =[];

        return $this->view('shop::admin.dashboard', ['data' => $data]);
    }


    private function getStatsForPeriod($period = [])
    {
        $data = [];
        $orders = (new Order())->query();
        $filters = [
            'isPaid' => 0,
            'isCompleted' => 1,
        ];
        $dateSting = '';
        if (isset($period['from'])) {
            $dateSting = $period['from'];
        }
        if (isset($period['to'])) {
            $dateSting .= ',' . $period['to'];
        }
        if ($dateSting) {
            $filters['dateBetween'] = $dateSting;
        }

        $orders->filter(
            $filters
        );


       // $getAmount = $orders->groupBy('payment_currency')->sum('payment_amount');

       $data = $orders->get()->toArray();

       // $data['amount'] = $getAmount;

        return $data;
    }


}
