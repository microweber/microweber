<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace Modules\Content\Models\ModelFilters\Traits;

trait FilterByOffersTrait
{
    public function offers($type = '')
    {
        if ($type=='only-offers') {
            $this->query->whereHas('offer');
        }
    }

}
