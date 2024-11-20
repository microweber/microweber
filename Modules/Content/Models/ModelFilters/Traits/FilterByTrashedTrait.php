<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace Modules\Content\Models\ModelFilters\Traits;

trait FilterByTrashedTrait
{
    public function trashed($isDeleted = 1)
    {
        $isDeleted = intval($isDeleted);
        $this->where('content.is_deleted', $isDeleted);
    }
}
