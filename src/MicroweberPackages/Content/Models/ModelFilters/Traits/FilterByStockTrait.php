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


        $in_stock = $isInStock;
//
//        $this->query->whereIn('content.id', function ($subQuery) use ($in_stock) {
//            $subQuery->select('content_data.rel_id')->from('content_data');
//            $subQuery->where(  'content_data.rel_type', '=', 'content');
//            $subQuery->where(function ($contentDataFieldValueQuery) use ($in_stock) {
//                $contentDataFieldValueQuery->where('content_data.field_name', '=', 'qty');
//                if ($in_stock) {
//                    $contentDataFieldValueQuery->where('content_data.field_value', '>', 0);
//                    $contentDataFieldValueQuery->orWhere('content_data.field_value', '=', 'nolimit');
//                } else {
//                    $contentDataFieldValueQuery->where('content_data.field_value', '=', 0);
//                }
//            });
//            $subQuery->groupBy(  'content_data.rel_id');
//
//            return $subQuery;
//        });



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

}
