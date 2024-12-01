<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace Modules\Cart\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use Modules\Content\Models\ModelFilters\Traits\FilterByKeywordTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByTitleTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByUrlTrait;

class CartFilter extends ModelFilter
{
    use FilterByTitleTrait;
    use FilterByUrlTrait;
    use FilterByKeywordTrait;
}
