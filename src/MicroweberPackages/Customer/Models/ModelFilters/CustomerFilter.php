<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace MicroweberPackages\Customer\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use MicroweberPackages\Helper\XSSClean;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class CustomerFilter extends ModelFilter
{


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

    public function createdAt($createdAt)
    {
        $this->query->whereDate('created_at', $createdAt);
        return $this->query;
    }

}
