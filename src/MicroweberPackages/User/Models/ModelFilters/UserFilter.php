<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace MicroweberPackages\User\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByKeywordTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByUrlTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\OrderByTrait;

class UserFilter extends ModelFilter
{
    use OrderByTrait;
    use FilterByUrlTrait;
    use FilterByKeywordTrait;

    public function isAdmin($isAdmin)
    {
        return $this->query->where('is_admin', $isAdmin);
    }
}
