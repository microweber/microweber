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
        $qtyOperator = $this->qtyOperator;

        return $this->query->whereHas('contentData', function (Builder $query) use ($qty, $qtyOperator) {

            $query->where('field_name', '=', 'qty');

            if ($qtyOperator == 'greater') {

                $query->where('field_value', '>', $qty);
            }  else if ($qtyOperator =='lower') {
                $query->where('field_value', '<', $qty);
            } else {
                $query->where('field_value', '=', $qty);
            }

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
