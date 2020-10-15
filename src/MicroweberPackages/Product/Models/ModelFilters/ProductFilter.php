<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace MicroweberPackages\Product\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter extends ModelFilter
{
    public function url($url)
    {
        return $this->where('url', $url);
    }

    public function title($title)
    {
        return $this->where('title', 'LIKE', "%$title%");
    }

    public function qty($qty)
    {
       return $this->whereHas('contentData', function (Builder $query) use ($qty) {
            $query->where('field_name', '=', 'qty');
            $query->where('field_value', '=', $qty);
        });
    }
}