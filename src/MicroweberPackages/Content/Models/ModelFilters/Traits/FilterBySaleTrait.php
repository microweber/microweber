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

trait FilterBySaleTrait {

    /**
     * Filter by qty
     *
     * @param $sales
     * @return mixed
     */
    public function sales($sales)
    {
        $params = [];
        $params['sales_count'] = $sales;
        $this->applySalesFilter($params);

    }

    public function sortSales($direction)
    {
        $params = [];
        $params['sales_sort'] = $direction;
        $this->applySalesFilter($params);

    }

    private function applySalesFilter($params)
    {
        $this->query->whereHas('orders')->with('cart', function ($subQuery) {

        })->withCount('orders');



        if (isset($params['sales_count']) && $params['sales_count'] != '') {
            $sales = intval($params['sales_count']);
            $this->query->where('orders_count', '=', $sales);
        }

        if (isset($params['sales_count']) && $params['sales_count'] != '') {
            $sales = intval($params['sales_count']);
            $this->query->where('orders_count', '=', $sales);
        }
        if(isset($params['sales_sort']) && $params['sales_sort'] != '') {
            $this->query->orderBy('orders_count', $params['sales_sort']);
        }


    }
}
