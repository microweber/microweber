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

        /// peca ti si tuka 

    }
}
