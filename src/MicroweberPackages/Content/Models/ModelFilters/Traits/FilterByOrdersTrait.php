<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Cart\Models\Cart;

trait FilterByOrdersTrait
{
    public $ordersOperator = false;
    public $sortOrders = false;

    /**
     * @param $opeator
     * @return void
     */
    public function ordersOperator($opeator)
    {
        $this->ordersOperator = $opeator;
    }

    /**
     * @param $direction
     * @return void
     */
    public function sortOrders($direction)
    {
        $this->sortOrders = $direction;
    }

    public function orders($orders)
    {
        $orders = intval($orders);

        $operator = '=';

        if ($this->ordersOperator) {
            switch ($this->ordersOperator) {
                case 'greater':
                    $operator = '>';
                    break;
                case 'lower':
                    $operator = '<';
                    break;
            }
        }

        $this->query
            ->whereHas('orders')
            ->groupBy('id')
            ->withCount('orders')
            ->having('orders_count', $operator, $orders);


        if ($this->sortOrders) {
            $this->query->orderBy('orders_count', $this->sortOrders);
        }

    }
}
