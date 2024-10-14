<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace Modules\Customer\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByDateBetweenTrait;

class CustomerFilter extends ModelFilter
{

    use FilterByDateBetweenTrait;

    public function keyword($keyword)
    {
        $model = $this->getModel();
        $table = $model->getTable();

        $this->query->where($table.'.first_name', 'LIKE', '%' . $keyword . '%');
        $this->query->orWhere($table.'.last_name', 'LIKE', '%' . $keyword . '%');
        $this->query->orWhere($table.'.email', 'LIKE', '%' . $keyword . '%');
        $this->query->orWhere($table.'.phone', 'LIKE', '%' . $keyword . '%');

        return $this->query;
    }

}
