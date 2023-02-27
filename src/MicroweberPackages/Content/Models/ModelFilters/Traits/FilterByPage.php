<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use MicroweberPackages\Helper\XSSClean;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

trait FilterByPage
{
    public function page($pageId)
    {
        if ($pageId) {
            $this->query->where('parent', $pageId);
        }
    }
    public function pageAndParent($pageId)
    {
        if ($pageId) {
            $this->query->where('parent', $pageId);
            $this->query->orWhere('id', $pageId);
        }
    }
}
