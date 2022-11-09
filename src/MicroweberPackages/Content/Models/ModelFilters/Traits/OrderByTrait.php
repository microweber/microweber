<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Support\Facades\DB;

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
            case 'orders':

                $table = app()->database_manager->real_table_name('cart');
                $this->query->orderByLeftPowerJoinsCount('cart.order')
                    ->select(
                        'content.*',
                        DB::raw("count(" . $table . ".order_id) AS total_orders"))
                    ->orderBy('total_orders', $orderDirection);

                break;
            default:
                 $this->query->orderBy($this->query->getModel()->getTable().'.'.$orderColumn, $orderDirection);
                break;
        }

         return $this->query;
    }


}
