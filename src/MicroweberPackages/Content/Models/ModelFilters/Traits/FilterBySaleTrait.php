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
    public $salesOperator = false;
    public $sortSales = false;

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

    public function sales($sales)
    {
        $sales = intval($sales);

        $operator = '=';

        if ($this->salesOperator) {
            switch ($this->salesOperator) {
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
            ->having('orders_count', $operator, $sales);


        if ($this->sortSales) {
            $this->query->orderBy('orders_count', $this->sortSales);
        }

    }
}
