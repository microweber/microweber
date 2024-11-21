<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace Modules\Category\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use Modules\Category\Models\ModelFilters\Traits\FilterByAvailableProductsByCategoryTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByKeywordTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByStockTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByTitleTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByUrlTrait;
use Modules\Content\Models\ModelFilters\Traits\OrderByTrait;

class CategoryFilter extends ModelFilter
{
    use OrderByTrait;
    use FilterByAvailableProductsByCategoryTrait;
    use FilterByTitleTrait;
    use FilterByUrlTrait;
    use FilterByKeywordTrait;
    use FilterByStockTrait;

}
