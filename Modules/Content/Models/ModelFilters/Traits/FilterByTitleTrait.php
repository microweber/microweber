<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace Modules\Content\Models\ModelFilters\Traits;

trait FilterByTitleTrait {

    public function title($title)
    {
        return $this->query->where('title', 'LIKE', "%$title%");
    }

}
