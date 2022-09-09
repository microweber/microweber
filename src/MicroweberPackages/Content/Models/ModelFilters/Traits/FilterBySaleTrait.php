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
        $sales = intval($sales);
        if ($sales == 0) {
             return;
        }


        $params = [];
        $params['sales_count'] = $sales;
        $this->applySalesFilter($params);

    }

    public function salesOperator($opeator)
    {
        $params = [];
        $params['sales_operator'] = $opeator;
        $this->applySalesFilter($params);

    }
    public function sortSales($direction)
    {
        $params = [];
        $params['sales_sort'] = $direction;
        $this->applySalesFilter($params);

    }

    public function applySalesFilter($params)
    {
        if (isset($params['sales_count']) && $params['sales_count'] != '') {
            $operator = '=';
            if (isset($params['sales_operator']) && $params['sales_operator'] != '') {
                switch ($params['sales_operator']) {
                    case 'greater':
                    case 'more_than':
                        $operator = '>=';
                        break;
                    case 'lower':
                    case 'lower_than':
                    case 'less_than':
                        $operator = '<=';
                        break;
                }
            }

            $sales = intval($params['sales_count']);
            $this->query
                //->with('orders' )
                 ->whereHas('orders')
             //   ->with('cart' )
                ->groupBy('id' )
                ->withCount('orders')
                ->having('orders_count', $operator, $sales);

   //       $this->query->where('orders_count', '=', $sales);
        }

//        if (isset($params['sales_count_min']) && $params['sales_count_min'] != '') {
//            $sales = intval($params['sales_count_min']);
//
//            $this->query->where('orders_count', '<=', $sales);
//        }
//        if (isset($params['sales_count_max']) && $params['sales_count_max'] != '') {
//            $sales = intval($params['sales_count_max']);
//            $this->query->where('orders_count', '>=', $sales);
//        }


        if(isset($params['sales_sort']) && $params['sales_sort'] != '') {
            $this->query->orderBy('orders_count', $params['sales_sort']);
        }


    }
}
