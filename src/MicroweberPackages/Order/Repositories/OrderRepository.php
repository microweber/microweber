<?php

namespace MicroweberPackages\Order\Repositories;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

class OrderRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = Order::class;

    public function getOrderCurrenciesForPeriod($params = [])
    {
        $orders = $this->getDefaultQueryForStats($params);
        $orders->groupBy('currency');
        $orders->select('currency', DB::raw('count(*) as orders_count'));

        $data = $orders->get();
        if ($data) {
            $data = $data->toArray();
            return $data;
        }
    }

    public function getOrdersTotalSumForPeriod($params = [])
    {

        $orders = $this->getDefaultQueryForStats($params);

        $sum = $orders->sum('amount');
        if ($sum) {
            return ceil($sum);
        }
        return 0;
    }
    public function getOrdersCountForPeriod($params = [])
    {

        $orders = $this->getDefaultQueryForStats($params);

        $sum = $orders->count('id');
        if ($sum) {
            return intval($sum);
        }
        return 0;
    }
    public function getOrderItemsCountForPeriod($params = [])
    {
 // todo  finish the query
      $orders = $this->getDefaultQueryForStats($params);

        $orders->joinRelationship('cart');
        $orders->where('cart.rel_type', 'content');
       // $orders->select(DB::raw('COUNT( cart.rel_id ) as "count"') );
       // $orders->groupBy('cart.rel_id');
      //  $sum =   $orders->count('cart.order_id');
        $sum =   $orders->count('cart.order_id' );
      //  dd($orders->toSql(),$sum);
//
//
//
//
//
//        $data = $orders->groupBy('rel_id')
//          //  ->orderBy('date', 'desc')
//            ->where('rel_type', 'content')
//            ->get([
//                 DB::raw('COUNT( * ) as "count"')
//            ])->toArray();
//
//        dd($data);
//
//
//
//      //  $orders->select(DB::raw('COUNT( * ) as "count"') );
//        $orders->where('rel_type', 'content');
//        $orders->groupBy('rel_id');
//        $sum = $orders-> count('id');
//      //  $sum = $orders->sum('count');
        if ($sum) {
            return intval($sum);
        }
        return 0;
    }
    public function getOrdersCountGroupedByDate($params = [])
    {
        $orders = $this->getDefaultQueryForStats($params);

        $data = $orders->groupBy('date')
            ->orderBy('date', 'desc')
            ->get([
                DB::raw('sum( amount ) as amount'),
                DB::raw('DATE( created_at ) as date'),
                DB::raw('COUNT( * ) as "count"')
            ])->toArray();

        if (!empty($data)) {
            array_walk($data, function (&$a, $b) {
                if (isset($a['amount'])) {
                    $a['amount_rounded'] = ceil($a['amount']);
                }
            });

        }

        return $data;
    }

    public function getDefaultQueryForStats($params)
    {

        $orders = $this->getModel()->newQuery();
        $params = array_merge($params, [
                 //  'isPaid' => 1,
                //  'isCompleted' => 1,
        ]);
        $dateSting = '';
        if (isset($params['from'])) {
            $dateSting = $params['from'];
        }
        if (isset($params['to'])) {
            $dateSting .= ',' . $params['to'];
        }
        if ($dateSting) {
            $params['dateBetween'] = $dateSting;
        }

        $orders->filter(
            $params
        );
        return $orders;
    }
}
