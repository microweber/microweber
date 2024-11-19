<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace Modules\Product\Models\ModelFilters;

use MicroweberPackages\Content\Models\ModelFilters\ContentFilter;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByAuthor;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByCategory;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByContentData;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByContentFields;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByCustomFields;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByDateBetweenTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByKeywordTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByOffersTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByOrdersTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByPage;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByPriceTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByQtyTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByStockTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByTagsTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByTitleTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByTrashedTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByUrlTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByVisibleTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\OrderByTrait;

class ProductFilter extends ContentFilter
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
