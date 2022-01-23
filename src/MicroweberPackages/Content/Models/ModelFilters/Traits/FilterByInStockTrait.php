<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterByInStockTrait
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
            if (!$isInStock) {
                $query->where('field_name', '=', 'qty');
                $query->where('field_value', '=', 0);
            } else {
                $query->where('field_name', '=', 'qty');
                $query->where(function ($contentDataFieldValueQuery) {
                    $contentDataFieldValueQuery->where('field_value', '>', 0);
                    $contentDataFieldValueQuery->orWhere('field_value', '=', 'nolimit');
                });
            }
        });

    }

}
