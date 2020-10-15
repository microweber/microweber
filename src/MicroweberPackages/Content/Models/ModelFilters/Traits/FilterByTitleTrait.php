<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;
use Illuminate\Database\Eloquent\Builder;

trait FilterByTitleTrait {

    public function title($title)
    {
        return $this->query->where('title', 'LIKE', "%$title%");
    }

}