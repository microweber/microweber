<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace Modules\Content\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use Modules\Content\Models\ModelFilters\Traits\FilterByAuthor;
use Modules\Content\Models\ModelFilters\Traits\FilterByCategory;
use Modules\Content\Models\ModelFilters\Traits\FilterByContentData;
use Modules\Content\Models\ModelFilters\Traits\FilterByContentFields;
use Modules\Content\Models\ModelFilters\Traits\FilterByDateBetweenTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByKeywordTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByPage;
use Modules\Content\Models\ModelFilters\Traits\FilterByTagsTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByTitleTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByTrashedTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByUrlTrait;
use Modules\Content\Models\ModelFilters\Traits\FilterByVisibleTrait;
use Modules\Content\Models\ModelFilters\Traits\OrderByTrait;

class ContentFilter extends ModelFilter
{
    use OrderByTrait;
    use FilterByAuthor;
    use FilterByTitleTrait;
    use FilterByTagsTrait;
    use FilterByPage;
    use FilterByCategory;
    use FilterByUrlTrait;
    use FilterByKeywordTrait;
    use FilterByContentData;
    use FilterByContentFields;
    use FilterByVisibleTrait;
    use FilterByTrashedTrait;
    use FilterByDateBetweenTrait;

    public function fields($fields)
    {
        $table = $this->getModel()->getTable();

     //   $this->query->without('media,categories');

        if (isset($fields) and $fields != false) {
            $is_fields = $fields;
            if (!is_array($is_fields)) {
                $is_fields = explode(',', $is_fields);
            }
            if (is_array($is_fields) and !empty($is_fields)) {
                foreach ($is_fields as $is_field) {
                    if (is_string($is_field)) {
                        $is_field = trim($is_field);
                        if ($is_field != '') {
                            $this->query->select($table . '.' . $is_field);
                        }
                        $this->query->select($table . '.*');
                    }
                }
            }
        } else {
            $this->query->select($table . '.*');
        }
    }
}
