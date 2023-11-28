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

trait FilterByOffersTrait
{
    public function offers($type = '')
    {
        if ($type=='only-offers') {
            $this->query->whereHas('offer');
        }
    }

}
