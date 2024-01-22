<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace MicroweberPackages\User\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByDateBetweenTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByKeywordTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByUrlTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\OrderByTrait;
use MicroweberPackages\Helper\XSSClean;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class UserFilter extends ModelFilter
{
    use OrderByTrait;
    use FilterByUrlTrait;
    use FilterByDateBetweenTrait;

    public function keyword($keyword)
    {
        $model = $this->getModel();
        $table = $model->getTable();

        $keyword = trim($keyword);

        $this->query->where('id', intval($keyword));
        $this->query->orWhere($table.'.username', 'LIKE', '%' . $keyword . '%');
        $this->query->orWhere($table.'.email', 'LIKE', '%' . $keyword . '%');

        $this->query->orWhere($table.'.first_name', 'LIKE', '%' . $keyword . '%');
        $this->query->orWhere($table.'.last_name', 'LIKE', '%' . $keyword . '%');

        $keywordExp = explode(' ', $keyword);
        if (isset($keywordExp[0]) && isset($keywordExp[1])) {
            $this->query->orWhere($table.'.first_name', 'LIKE', '%' . $keywordExp[0] . '%');
            $this->query->orWhere($table.'.last_name', 'LIKE', '%' . $keywordExp[1] . '%');
        }

        $this->query->orWhere($table.'.phone', 'LIKE', '%' . $keyword . '%');

        return $this->query;
    }

    public function isAdmin($isAdmin)
    {
        return $this->query->where('is_admin', $isAdmin);
    }
}
