<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;
use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\Product\Models\Product;

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

}
