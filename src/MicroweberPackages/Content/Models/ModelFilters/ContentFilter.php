<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByInStockTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByKeywordTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByTagsTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByTitleTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByUrlTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\OrderByTrait;

class ContentFilter extends ModelFilter
{
    use OrderByTrait;
    use FilterByTitleTrait;
    use FilterByUrlTrait;
    use FilterByKeywordTrait;
    use FilterByTagsTrait;
    use FilterByInStockTrait;

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
