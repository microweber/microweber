<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

trait OrderByTrait
{
    public function orderBy($orderBy)
    {

        $orderColumn = $orderBy;
        $orderDirection = 'desc';

        $orderBy = str_replace(' ', ',', $orderBy);

        if (strpos($orderBy, ',') !== false) {
            $orderBy = explode(',', $orderBy);
            $orderColumn = $orderBy[0];
            $orderDirection = $orderBy[1];
        }

        if (isset($this->input['orderDirection'])) {
            $orderDirection = $this->input['orderDirection'];
        }


        switch ($orderColumn) {
            case 'price':
                 $this->query->whereHas('customFieldsPrices', function ($query) use ($orderColumn, $orderDirection) {
                    return $query->orderBy('custom_fields_values.value', $orderDirection);
                })->orderByPowerJoins('customFieldsPrices.value', $orderDirection);

                break;
            case 'sales':
                $this->query->whereHas('orders', function ($query) use ($orderColumn, $orderDirection) {

                 })->orderByPowerJoinsCount('orders.id', $orderDirection);

                break;
            default:
                $this->query->orderBy($orderColumn, $orderDirection);
                break;
        }




         return $this->query;
    }


}
