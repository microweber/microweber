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

trait FilterByDate
{
    public function createdAt($createdAt = false)
    {
        if ($createdAt) {
            $this->query->where('created_at', '>=', $createdAt);
        }
    }

    public function updatedAt($updatedAt = false)
    {
        if ($updatedAt) {
            $this->query->where('updated_at', '>=', $updatedAt);
        }
    }

}
