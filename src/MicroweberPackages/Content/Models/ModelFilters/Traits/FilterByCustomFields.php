<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterByCustomFields
{
    public function customFields($customFields)
    {

        $this->query->whereCustomField($customFields);

        return $this->query;

    }
}
