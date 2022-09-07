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

    /**
     * Filter by qty
     *
     * @param $qty
     * @return mixed
     */
    public function qty($qty)
    {
        return $this->query->whereHas('contentData', function (Builder $query) use ($qty) {
            $query->where('field_name', '=', 'qty');
            $query->where('field_value', '=', $qty);
        });
    }
}
