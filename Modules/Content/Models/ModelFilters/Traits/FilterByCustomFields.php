<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace Modules\Content\Models\ModelFilters\Traits;

trait FilterByCustomFields
{
    public function customFields($customFields)
    {

        $this->query->whereCustomField($customFields);

        return $this->query;

    }
}
