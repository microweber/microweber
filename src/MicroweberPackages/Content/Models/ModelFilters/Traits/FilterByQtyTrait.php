<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;
use Illuminate\Database\Eloquent\Builder;

trait FilterByQtyTrait {

    public $qtyOperator = 'equal';

    /**
     * Filter by qty
     *
     * @param $qty
     * @return mixed
     */
    public function qty($qty)
    {
        $qty = intval($qty);

        $qtyOperator = $this->qtyOperator;

        $this->query->whereHas('contentData', function (Builder $query) use ($qty, $qtyOperator) {

            $query->where('field_name', '=', 'qty');

            if ($qtyOperator == 'greater') {
                $query->whereRaw('CAST(field_value as SIGNED) > '.$qty);
            }  else if ($qtyOperator =='lower') {
                $query->whereRaw('CAST(field_value as SIGNED) < '.$qty);
            } else {
                $query->whereRaw('CAST(field_value as SIGNED) = '.$qty);
            }


        });

        $this->query->orWhereHas('contentData', function (Builder $query) {
            $query->where('field_name', '=', 'qty');
            $query->where('field_value', '=','nolimit');
        });

    }

    /**
     * @param $operator
     * @return void
     */
    public function qtyOperator($operator) {
        $this->qtyOperator = $operator;
    }
}
