<?php

namespace MicroweberPackages\Order\Repositories;

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


    public function getStatsForPeriod($period = [])
    {
        $data = [];
        $orders = $this->getModel();
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
