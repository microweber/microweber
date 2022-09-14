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

trait FilterBySaleTrait
{

    /**
     * Filter by qty
     *
     * @param $sales
     * @return mixed
     */
    public function sales($sales)
    {
        $this->sales = intval($sales);
    }

    /**
     * @param $opeator
     * @return void
     */
    public function salesOperator($opeator)
    {
        $this->salesOperator = $opeator;

    }

    /**
     * @param $direction
     * @return void
     */
    public function sortSales($direction)
    {
        $this->sortSales = $direction;
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
                ->whereHas('orders')
                ->groupBy('id')
                ->withCount('orders')
                ->having('orders_count', $operator, $sales);
        }

        if (isset($params['sales_sort']) && $params['sales_sort'] != '') {
            $this->query->orderBy('orders_count', $params['sales_sort']);
        }

    }
}
