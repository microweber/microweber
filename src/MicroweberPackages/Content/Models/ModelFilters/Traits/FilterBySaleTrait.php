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
        $this->query->whereHas('cart', function ($subQuery) use ($sales) {
            $subQuery->has('order', '>=', $sales);
        });
    }

    public function sortSales($direction)
    {

        $this->query->whereHas('cart', function ($subQuery) {
            $subQuery->has('order');
        })
            ->join('cart', 'cart.rel_id', '=', 'content.id')
            ->select('content.*',  DB::raw("count('order.id') as sales"))
            ->where('cart.rel_type', 'content')
            ->orderBy('sales', $direction)
            ->groupBy('cart.rel_id');

    }
}
