<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace Modules\Product\Models\ModelFilters;

use Modules\Content\Models\ModelFilters\Traits\FilterByAuthor;
use Modules\Content\Models\ModelFilters\Traits\FilterByCategory;
use Modules\Content\Models\ModelFilters\Traits\FilterByContentData;
use Modules\Content\Models\ModelFilters\Traits\FilterByContentFields;
use Modules\Content\Models\ModelFilters\Traits\FilterByCustomFields;
use Modules\Content\Models\ModelFilters\Traits\FilterByDateBetweenTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByKeywordTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByOffersTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByOrdersTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByPage;
use Modules\Content\Models\ModelFilters\Traits\FilterByPriceTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByQtyTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByStockTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByTagsTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByTitleTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByTrashedTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByUrlTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByVisibleTrait;
use Modules\Content\Models\ModelFilters\Traits\OrderByTrait;

class ProductFilter extends \Modules\Content\Models\ModelFilters\ContentFilter
{
    use OrderByTrait;
    use FilterByAuthor;
    use FilterByTitleTrait;
    use FilterByTagsTrait;
    use FilterByPage;
    use FilterByCategory;
    use FilterByQtyTrait;
    use FilterByOrdersTrait;
    use FilterByUrlTrait;
    use FilterByPriceTrait;
    use FilterByOffersTrait;
    use FilterByKeywordTrait;
    use FilterByContentData;
    use FilterByContentFields;
    use FilterByCustomFields;
    use FilterByStockTrait;
    use FilterByVisibleTrait;
    use FilterByTrashedTrait;
    use FilterByDateBetweenTrait;

    public function sku($sku)
    {
        $this->contentData(['sku'=>$sku]);
    }

}
