<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterByStockTrait
{
    /**
     * Filter by in stock
     *
     * @param bool isInStock
     * @return mixed
     */
    public function inStock($isInStock)
    {
         return $this->query->whereHas('contentData', function (Builder $query) use ($isInStock) {
            if ($isInStock == 1) {
                // in of stock
                $query->where('field_name', '=', 'qty');
                $query->where(function ($contentDataFieldValueQuery) {
                    $contentDataFieldValueQuery->where('field_value', '>', 0);
                    $contentDataFieldValueQuery->orWhere('field_value', '=', 'nolimit');
                });
            } else {
                // out of stock
                $query->where('field_name', '=', 'qty');
                $query->where('field_value', '=', 0);
                $query->where('field_value', '!=', 'nolimit');
            }
        });

    }

    public function stockStatus($status)
    {
        if ($status == 'inStock') {
            return $this->inStock(1);
        }
        if ($status == 'outOfStock') {
            return $this->inStock(0);
        }
    }
}
